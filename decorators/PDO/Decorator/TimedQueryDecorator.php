<?php
namespace PDO\Decorator;

/*
 * A decorator which prints the amount of time taken to run each query and also
 * serves as an example of creating a PDO decorator
 */
class TimedQueryDecorator extends PDODecorator {
  private $timer;

  /*
   * Create a PDO decorated for query execution time, echoing the time if active
   */
  function __construct($concreteStatement, $active=true) {
    parent::__construct($concreteStatement);
    $this->timer = new Timer($active);
  }

  function query($statement) {
    $parent = $this->getPDO();
    return $this->timer->runTimedOperation(function() use ($parent, $statement) {
      return $parent->query($statement);
    });
  }

  function exec($statement) {
    $parent = $this->getPDO();
    return $this->timer->runTimedOperation(function() use ($parent, $statement) {
      return $parent->exec($statement);
    });
  }

  /*
   * Return a decorated statement
   */
  function prepare($statement, $driver_options=array()) {
    $statement = parent::prepare($statement, $driver_options);
    if ($statement === false) return $statement;
    return new TimedQueryStatementDecorator($statement, $this->timer);
  }
}

/*
 * Shared operations between both TimedQueryDecorator and
 * TimedQueryStatementDecorator
 */
class Timer {
  private $active;

  function __construct($active=true) {
    $this->active = $active;
  }

  function setActive($active=true) {
    $this->active = $active;
  }

  function runTimedOperation($fn) {
    if ($this->active) {
      $start = microtime(true);
      $ret = $fn();
      $time = microtime(true) - $start;

      echo "Query done in $time seconds\n";
      return $ret;
    }
    else {
      return $fn();
    }
  }
}

/*
 * Time all methods that run queries, echoing query time as query finishes
 */
class TimedQueryStatementDecorator extends PDOStatementDecorator {
  private $timer;

  function __construct($concreteStatement, $timer) {
    parent::__construct($concreteStatement);
    $this->timer = $timer;
  }

  /* Decorated overrides - all query methods */

  function execute($input_parameters=array()) {
    $parent = $this->getStatement();
    return $this->timer->runTimedOperation(function() use ($parent, $input_parameters) {
      return $parent->execute($input_parameters);
    });
  }
}
?>
