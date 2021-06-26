<?php

namespace webspell_ng;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

use webspell_ng\Enums\DatabaseConfigurationEnums;
use webspell_ng\Handler\DatabaseConfigurationHandler;

class WebSpellDatabaseConnection {

    /**
     * @var string $PREFIX
     */
    public static $PREFIX;

    /**
     * @var ?Connection $connection
     */
    private static $connection = null;

    public static function getDatabaseConnection(): Connection
    {
        if (is_null(self::$connection)) {
            self::$connection = DriverManager::getConnection(
                WebSpellDatabaseConnection::readDatabaseConfiguration()
            );
        }
        return self::$connection;
    }

    /**
     * @return array{dbname: string, user: string, password: string, host: string, driver: string}
     */
    private static function readDatabaseConfiguration(): array
    {

        DatabaseConfigurationHandler::loadEnvironmentVariables();

        if (!isset($_ENV[DatabaseConfigurationEnums::DB_PREFIX])) {
            throw new \UnexpectedValueException("database_environment_is_unknown");
        }

        self::$PREFIX = $_ENV[DatabaseConfigurationEnums::DB_PREFIX];

        if (!defined("PREFIX")) {
            define("PREFIX", self::$PREFIX);
        }

        return array(
            'dbname' => $_ENV[DatabaseConfigurationEnums::DB_NAME],
            'user' => $_ENV[DatabaseConfigurationEnums::DB_USER],
            'password' => $_ENV[DatabaseConfigurationEnums::DB_PASS],
            'host' => $_ENV[DatabaseConfigurationEnums::DB_HOST] . ':' . $_ENV[DatabaseConfigurationEnums::DB_PORT],
            'driver' => 'pdo_mysql'
        );

    }

    public static function getTablePrefix(): string
    {
        if (empty(self::$PREFIX)) {
            throw new \UnexpectedValueException("database_table_prefix_is_invalid");
        }
        return self::$PREFIX;
    }

}