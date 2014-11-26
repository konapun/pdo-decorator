<?php
namespace PDO\Decorator;

interface PDO {
  function beginTransaction();
  function commit();
  function errorCode();
  function errorInfo();
  function exec($statement);
  function getAttribute($attribute);
  function getAvailableDrivers();
  function inTransaction();
  function lastInsertId($name);
  function prepare($statement, $driver_options);
  function query($statement);
  function quote($string, $parameter_type);
  function rollBack();
  function setAttribute($attribute, $value);
}
?>
