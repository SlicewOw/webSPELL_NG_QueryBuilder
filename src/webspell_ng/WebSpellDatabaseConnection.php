<?php

namespace webspell_ng;

use Doctrine\DBAL\Connection;

class WebSpellDatabaseConnection {

    public static $PREFIX = null;

    public static function getDatabaseConnection(): Connection
    {
        return \Doctrine\DBAL\DriverManager::getConnection(
            WebSpellDatabaseConnection::readDatabaseConfiguration()
        );
    }

    private static function readDatabaseConfiguration(): array
    {

        $path_to_database_configuration_file = __DIR__ . '/../../../../db.php';

        if (!file_exists($path_to_database_configuration_file)) {
            throw new \UnexpectedValueException("cannot_read_database_configuration");
        }

        require_once($path_to_database_configuration_file);

        if (defined('PREFIX')) {
            self::$PREFIX = PREFIX;
        }

        return array(
            'dbname' => (isset($db)) ? $db : null,
            'user' => (isset($user)) ? $user : null,
            'password' => (isset($pwd)) ? $pwd : null,
            'host' => (isset($host)) ? $host : null,
            'driver' => 'pdo_mysql',
        );

    }

}