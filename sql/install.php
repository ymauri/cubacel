<?php 
$queries = [];
$queries[] = "CREATE TABLE IF NOT EXISTS "._DB_PREFIX_."cubacel_log (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `id_order` varchar(128) NOT NULL,
          `account` text NOT NULL,
          `type` int(11) NOT NULL,
          `attemps` int(11) NOT NULL,
          `reference` text,
          `amount` int(11) NOT NULL,
          `status` varchar(128) NOT NULL,
          `message` text,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";


$queries[] = "CREATE TABLE IF NOT EXISTS "._DB_PREFIX_."cubacel_blacklist (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `data` text NOT NULL,
          `type` text NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

foreach ($queries as $query) {
  Db::getInstance()->execute($query);
}