<?php

namespace webspell_ng;

use \Doctrine\DBAL\Connection;
use \Doctrine\DBAL\DriverManager;
use \Dotenv\Dotenv;

class WebSpellDatabaseConnection {

    /** @var string $PREFIX */
    public static $PREFIX;

    public static function getDatabaseConnection(): Connection
    {
        return DriverManager::getConnection(
            WebSpellDatabaseConnection::readDatabaseConfiguration()
        );
    }

    /**
     * @return array{dbname: string, user: string, password: string, host: string, driver: string}
     */
    private static function readDatabaseConfiguration(): array
    {

        $dotenv = Dotenv::createImmutable(
            self::getDatabaseConfigurationFile()
        );
        $dotenv->load();

        $dotenv->required('DB_HOST')->notEmpty();
        $dotenv->required('DB_NAME')->notEmpty();
        $dotenv->required('DB_USER')->notEmpty();
        $dotenv->required('DB_PASS');
        $dotenv->required('DB_PREFIX')->notEmpty();

        if (!isset($_ENV["DB_USER"])) {
            throw new \InvalidArgumentException("cannot_read_database_user");
        }

        self::$PREFIX = $_ENV["DB_PREFIX"];

        if (!defined("PREFIX")) {
            define("PREFIX", self::$PREFIX);
        }

        return array(
            'dbname' => $_ENV["DB_NAME"],
            'user' => $_ENV["DB_USER"],
            'password' => $_ENV["DB_PASS"],
            'host' => $_ENV["DB_HOST"],
            'driver' => 'pdo_mysql'
        );

    }

    private static function getDatabaseConfigurationFile(): string
    {

        $path_to_database_configuration_file = __DIR__ . '/../../../../.env';

        if (!file_exists($path_to_database_configuration_file)) {
            $path_to_database_configuration_file = __DIR__ . '/../resources/.env';
        }

        return \dirname($path_to_database_configuration_file);

    }

    public static function getTablePrefix(): string
    {
        if (empty(self::$PREFIX)) {
            throw new \UnexpectedValueException("database_table_prefix_is_invalid");
        }
        return self::$PREFIX;
    }

}