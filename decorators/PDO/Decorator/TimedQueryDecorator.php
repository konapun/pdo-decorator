<?php
namespace PDO\Decorator;

/*
 * A decorator which prints the amount of time taken to run each query
 */
class TimedQueryDecorator extends PDODecorator {
  private $active;

  function __construct($concreteStatement, $active=true) {
    parent::__construct($concreteStatement);
    $this->active = $active;
  }

  function query($statement) {
    $parent = $this->getPDO();
    return $this->time(function() use ($parent, $statement) {
      return $parent->query($statement);
    });
  }

  function exec($statement) {
    $parent = $this->getPDO();
    return $this->time(function() use ($parent, $statement) {
      return $parent->exec($statement);
    });
  }

  /*
   * Return a decorated statement
   */
  function prepare($statement, $driver_options=array()) {
    $statement = parent::prepare($statement, $driver_options);
    if ($statement === false) return $statement;
    return new TimedQueryStatementDecorator($statement, $this->active);
  }

  private function time($fn) {
    if ($this->active) {
      $start = microtime(true);
      $ret = $fn();
      $time = microtime(true) - $start;

      echo "Query done in $time seconds\n";
    }
  }
}

/*
 * Time all methods that run queries, echoing query time as query finishes
 */
class TimedQueryStatementDecorator extends PDOStatementDecorator {
  private $active;

  function __construct($concreteStatement, $active=true) {
    parent::__construct($concreteStatement);
    $this->active = $active;
  }

  /*
   * Print the amount of time it takes to run a query
   */
  private function time($fn) {
    if ($this->active) {
      $start = microtime(true);
      $ret = $fn();
      $time = microtime(true) - $start;

      echo "Query done in $time seconds\n";
      return $ret;
    }
  }

  /* Decorated overrides - all query methods */

  function execute($input_parameters=array()) {
    $parent = $this->getStatement();
    return $this->time(function() use ($parent, $input_parameters) {
      return $parent->execute($input_parameters);
    });
  }
}
?>
