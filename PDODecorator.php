<?php
$base = dirname(__FILE__) . DIRECTORY_SEPARATOR;
$core = $base . 'core' . DIRECTORY_SEPARATOR . 'PDO' . DIRECTORY_SEPARATOR . 'Decorator' . DIRECTORY_SEPARATOR;
$decorator = $base . 'decorators' . DIRECTORY_SEPARATOR . 'PDO' . DIRECTORY_SEPARATOR . 'Decorator' . DIRECTORY_SEPARATOR;

// Core
include_once($core . 'PDO.php');
include_once($core . 'PDOStatement.php');
include_once($core . 'PDODecorator.php');
include_once($core . 'PDOStatementDecorator.php');

// Included decorators
include_once($decorator . 'TimedQueryDecorator.php');
include_once($decorator . 'ResultsFileCacheDecorator.php');
?>
