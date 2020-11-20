<?php

namespace webspell_ng;

use \Doctrine\DBAL\Connection;
use \Doctrine\DBAL\DriverManager;
use \Dotenv\Dotenv;

class WebSpellDatabaseConnection {

    /**
     * @var string $PREFIX
     */
    public static $PREFIX;

    /**
     * @var Connection $connection
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

        self::loadEnvironmentVariables();

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

    private static function loadEnvironmentVariables(): void
    {

        if (self::testIfAllRequiredEnvironmentVariablesArePresent()) {
            return;
        }

        $dotenv = Dotenv::createImmutable(
            self::getDatabaseConfigurationFile()
        );
        $dotenv->load();

        $dotenv->required('DB_HOST')->notEmpty();
        $dotenv->required('DB_NAME')->notEmpty();
        $dotenv->required('DB_USER')->notEmpty();
        $dotenv->required('DB_PASS');
        $dotenv->required('DB_PREFIX')->notEmpty();


    }

    private static function testIfAllRequiredEnvironmentVariablesArePresent(): bool
    {

        $environment_variables = array(
            "DB_HOST",
            "DB_NAME",
            "DB_USER",
            "DB_PASS",
            "DB_PREFIX"
        );

        foreach ($environment_variables as $environment_variable) {
            if (!isset($_ENV[$environment_variable])) {
                return false;
            }
        }

        return true;

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