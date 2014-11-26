<?php
namespace PDO\Decorator;

/*
 * Decorator which delegates all interface methods
 */
abstract class PDOStatementDecorator implements \IteratorAggregate, PDOStatement {
  private $concreteStatement;

  function __construct($concreteStatement) { // PDOStatement
    $this->concreteStatement = $concreteStatement;
  }

  final protected function getStatement() {
    return $this->concreteStatement;
  }

  /* Delegates */
  function bindColumn($column, &$param, $type=null, $maxlen=null, $driverdata=null) {
    return $this->concreteStatement->bindColumn($column, $param, $type, $maxlen, $driverdata);
  }

  function bindParam($parameter, &$variable, $data_type=\PDO::PARAM_STR, $length=null, $driver_options=null) {
    return $this->concreteStatement->bindParam($parameter, $variable, $data_type, $length, $driver_options);
  }

  function bindValue($parameter, $value, $data_type=\PDO::PARAM_STR) {
    return $this->concreteStatement->bindValue($parameter, $value, $data_type);
  }

  function closeCursor() {
    return $this->concreteStatement->closeCursor();
  }

  function columnCount() {
    return $this->concreteStatement->columnCount();
  }

  function debugDumpParams() {
    return $this->concreteStatement->debugDumpParams();
  }

  function errorCode() {
    return $this->concreteStatement->errorCode();
  }

  function errorInfo() {
    return $this->concreteStatement->errorInfo();
  }

  function execute($input_parameters=array()) {
    return $this->concreteStatement->execute($input_parameters);
  }

  function fetch($fetch_style=null, $cursor_orientation=\PDO::FETCH_ORI_NEXT, $cursor_offset=0) {
    return $this->concreteStatement->fetch($fetch_style, $cursor_orientation, $cursor_offset);
  }

  function fetchAll($fetch_style=null, $fetch_argument=null, $ctor_args=array()) {
    return $this->concreteStatement->fetchAll($fetch_style, $fetch_argument, $ctor_args);
  }

  function fetchColumn($column_number=null) {
    return $this->concreteStatement->fetchColumn($column_number=0);
  }

  function fetchObject($class_name="stdClass", $ctor_args) {
    return $this->concreteStatement->fetchObject($class_name, $ctor_args);
  }

  function getAttribute($attribute) {
    return $this->concreteStatement->getAttribute($attribute);
  }

  function getColumnMeta($column) {
    return $this->concreteStatement->getColumnMeta($column);
  }

  function nextRowset() {
    return $this->concreteStatement->nextRowset();
  }

  function rowCount() {
    return $this->concreteStatement->rowCount();
  }

  function setAttribute($attribute, $value) {
    return $this->concreteStatement->setAttribute($attribute, $value);
  }

  function setFetchMode($mode) {
    return $this->concreteStatement->setFetchMode($mode);
  }

  function getIterator() { // from IteratorAggregate
    throw new \BadMethodCallException("PDO Decorator does not provide a definition for 'getIterator'");
  }
}
?>
