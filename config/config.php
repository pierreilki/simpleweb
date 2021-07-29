<?php

/**
 * Configuration for database connection
 *
 */

$host       = getenv('BDD_HOST');
$username   = getenv('BDD_USERNAME');
$password   = getenv('BDD_PASSWORD');
$dbname     = getenv('BDD_DATABASE');
$dsn        = "mysql:host=$host;dbname=$dbname";
$options    = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
              );
