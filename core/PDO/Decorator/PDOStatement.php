<?php
namespace PDO\Decorator;

/*
 * Interface for a PDOStatement
 */
interface PDOStatement extends \Traversable {
  function bindColumn($column, &$param, $type, $maxlen, $driverdata);
  function bindParam($parameter, &$variable, $data_type, $length, $driver_options);
  function bindValue($parameter, $value, $data_type);
  function closeCursor();
  function columnCount();
  function debugDumpParams();
  function errorCode();
  function errorInfo();
  function execute($input_parameters);
  function fetch($fetch_style, $cursor_orientation, $cursor_offset);
  function fetchAll($fetch_style, $fetch_argument, $ctor_args);
  function fetchColumn($column_number);
  function fetchObject($class_name, $ctor_args);
  function getAttribute($attribute);
  function getColumnMeta($column);
  function nextRowset();
  function rowCount();
  function setAttribute($attribute, $value);
  function setFetchMode($mode);
}
?>
