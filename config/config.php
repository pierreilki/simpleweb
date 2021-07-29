<?php

/**
 * Configuration for database connection
 *
 */

$host       = "10.100.20.53";
$username   = "simpleweb";
$password   = "mysecretpass";
$dbname     = "simpleweb";
$dsn        = "mysql:host=$host;dbname=$dbname";
$options    = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
              );
