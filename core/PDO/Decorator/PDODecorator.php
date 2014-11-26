<?php
namespace PDO\Decorator;

/*
 * Decorator which delegates all interface methods
 */
abstract class PDODecorator implements PDO {
  private $concretePDO;

  function __construct($concretePDO) { // PDO
    $this->concretePDO = $concretePDO;
  }

  final protected function getPDO() {
    return $this->concretePDO;
  }

  /* Delegates */
  function beginTransaction() {
    return $this->concretePDO->beginTransaction();
  }

  function commit() {
    return $this->concretePDO->commit();
  }

  function errorCode() {
    return $this->concretePDO->errorCode();
  }

  function errorInfo() {
    return $this->concretePDO->errorInfo();
  }

  function exec($statement) {
    return $this->concretePDO->exec($statement);
  }

  function getAttribute($attribute) {
    return $this->concretePDO->getAttribute($attribute);
  }

  function getAvailableDrivers() {
    return $this->concretePDO->getAvailableDrivers();
  }

  function inTransaction() {
    return $this->concretePDO->inTransaction();
  }

  function lastInsertId($name=null) {
    return $this->concretePDO->lastInsertId($name);
  }

  function prepare($statement, $driver_options=array()) {
    return $this->concretePDO->prepare($statement, $driver_options);
  }

  function query($statement) {
    return $this->concretePDO->query($statement);
  }

  function quote($string, $parameter_type=\PDO::PARAM_STR) {
    return $this->concretePDO->quote($string, $parameter_type);
  }

  function rollBack() {
    return $this->concretePDO->rollBack();
  }

  function setAttribute($attribute, $value) {
    return $this->concretePDO->setAttribute($attribute, $value);
  }
}
?>
