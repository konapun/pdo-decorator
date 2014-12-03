<?php
namespace PDO\Decorator;

/*
 * Cache results to a file
 *
 */
class ResultsFileCacheDecorator extends PDODecorator {
  private $cache;

  function __construct($pdo, $cacheFile) {
    $this->cache = new ResultsFileCache($cacheFile);
    parent::__construct($pdo);
  }

  function prepare($statementString, $driver_options=array()) {
    $statement = parent::prepare($statementString, $driver_options);
    if ($statement === false) return $statement;
    return new ResultsFileCacheStatementDecorator($statement, $statementString, $this->cache);
  }

  /*
   * Execute a query and return the number of
   */
  function exec($statement) {
    if (($cached = $this->cache->getCachedResults) !== false) {

    }
    return parent::exec($statement);
  }

  function query($statement) {
    // TODO
    return parent::query($statement);
  }

  private function getHashKeyForQuery($query) {
    return md5($query);
  }

}

/*
 * The actual query cache
 */
class ResultsFileCache {
  private $file;
  private $fh;
  private $cache;

  function __construct($cacheFile) {
    $fh = fopen($cacheFile, 'w+');
    if ($fh == false) throw new \RuntimeException("Can't open file '$cacheFile' for reading/writing (mode w+)");
    $this->file = $cacheFile;
    $this->fh = $fh;
    $this->cache = $this->readCache($fh, $cacheFile);
  }

  function fetchCachedResults($query) {
    return $this->getCachedResultsForKey($this->generateHashKey($query));
  }

  private function getCachedResultsForKey($key) {
    return array_key_exists($key, $this->cache) ? $this->cache[$key] : false;
  }

  function cacheResults($query, $results) {
    $this->cacheResultsByKey($this->generateHashKey($query), $results);
  }

  private function cacheResultsByKey($key, $results) {
    $this->cache[$key] = $results;
  }

  private function generateHashKey($statement) {
    //echo "<br>\nSTATEMENT:---------<br>\n";
    //echo  "$statement<br>\n";
    //echo "-------------------<br>\n";
    //echo "Generating hash key: " . md5($statement) . "<br>\n";
    return md5($statement);
  }

  /*
   * Write the cache contents back to the cache file
   */
  private function writeCache($fh) {
    $json = json_encode($this->cache);
    if (fwrite($this->fh, json_encode($this->cache)) === false) throw new \RuntimeException("Can't  write cache to file '$cacheFile'");
  }

  private function readCache($fh, $filename) {
    $size = filesize($filename);
    if ($size == 0) return array (); // initialize cache if none exists
    $contents = fread($fh, filesize($filename));
    if ($contents === false) return array();
    return json_decode($contents, true);
  }

  private function argsToString($args) {
    $string = "";
    foreach ($args as $key => $val) {
      $string .= "$key-$val ";
    }
    return $string;
  }

  function __destruct() {
    $this->writeCache($this->fh);
    fclose($this->fh);
  }
}

/*
 * Statement decorated with cache
 */
class ResultsFileCacheStatementDecorator extends PDOStatementDecorator {
  private $cache;
  private $query;
  private $params;

  function __construct($statement, $query, $cache) {
    $this->query = $query;
    $this->cache = $cache;
    $this->params = array();
    parent::__construct($statement);
  }

  function bindColumn($column, &$param, $type=null, $maxlen=null, $driverdata=null) {
    array_push($this->params, $param);
    return parent::bindColumn($column, $param, $type, $maxlen, $driverdata);
  }

  function bindParam($parameter, &$variable, $data_type=\PDO::PARAM_STR, $length=null, $driver_options=null) {
    $this->params[$parameter] = $variable;

    return parent::bindParam($parameter, $variable, $data_type, $length, $driver_options);
  }

  function bindValue($parameter, $value, $data_type=\PDO::PARAM_STR) {
    $this->params[$parameter] = $value;

    return parent::bindValue($parameter, $value, $data_type);
  }

  function execute($input_parameters=array()) {
    foreach ($input_parameters as $key => $val) {
      $this->params[$key] = $val;
    }

    $insertedQuery = $this->insertQueryParams($this->query, $this->params);
    if (($results = $this->cache->fetchCachedResults($insertedQuery)) !== false) {
      echo "Fetching cached results<br>\n";
      return $results;
    }

    echo "Caching results to file<br>\n";
    $results = parent::execute($input_parameters);
    var_dump($results);
    $this->cache->cacheResults($insertedQuery, $results);
    return $results;
  }

  /*
   * Replace placeholders with real arguments in a prepared statement for
   * caching
   */
  private function insertQueryParams($query, $params) {
    $inserted = $query;
    foreach ($params as $pkey => $pval) {
      if ($pkey[0] != ':') $pkey = ':' . $pkey; // add initial : to query param
      $inserted = str_replace($pkey, $pval, $inserted);
    }

    return $inserted;
  }
}
?>
