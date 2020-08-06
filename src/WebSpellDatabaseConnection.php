<?php

namespace webspell_ng;

use Doctrine\DBAL\Connection;
use Noodlehaus\Config;

class WebSpellDatabaseConnection {

    /** @var string $PREFIX */
    public static $PREFIX;

    public static function getDatabaseConnection(): Connection
    {
        return \Doctrine\DBAL\DriverManager::getConnection(
            WebSpellDatabaseConnection::readDatabaseConfiguration()
        );
    }

    /**
     * @return array<string>
     */
    private static function readDatabaseConfiguration(): array
    {

        $configuration = Config::load(
            self::getDatabaseConfigurationFile()
        );

        if (!isset($configuration["db_username"])) {
            throw new \InvalidArgumentException("cannot_read_database_user");
        }

        self::$PREFIX = $configuration["prefix"];

        if (!defined("PREFIX")) {
            define("PREFIX", self::$PREFIX);
        }

        return array(
            'dbname' => (isset($configuration["db_name"])) ? $configuration["db_name"] : null,
            'user' => (isset($configuration["db_username"])) ? $configuration["db_username"] : null,
            'password' => (isset($configuration["db_password"])) ? $configuration["db_password"] : null,
            'host' => (isset($configuration["host"])) ? $configuration["host"] : null,
            'driver' => 'pdo_mysql',
        );

    }

    private static function getDatabaseConfigurationFile(): string
    {

        $path_to_database_configuration_file = __DIR__ . '/../../../../database.json';

        if (!file_exists($path_to_database_configuration_file)) {
            $path_to_database_configuration_file = __DIR__ . '/../resources/database.json';
        }

        return $path_to_database_configuration_file;

    }

    public static function getTablePrefix(): string
    {
        if (empty(self::$PREFIX)) {
            throw new \UnexpectedValueException("database_table_prefix_is_invalid");
        }
        return self::$PREFIX;
    }

}