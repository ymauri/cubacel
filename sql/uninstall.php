<?php 
$queries = [];
$queries[] = "DROP TABLE IF EXISTS "._DB_PREFIX_."cubacel_log";
$queries[] = "DROP TABLE IF EXISTS "._DB_PREFIX_."cubacel_blacklist";

foreach ($queries as $query) {
    Db::getInstance()->execute($query);
  }