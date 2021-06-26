<?php

namespace webspell_ng\Handler;

use Dotenv\Dotenv;

use webspell_ng\Enums\DatabaseConfigurationEnums;


class DatabaseConfigurationHandler {

    private const PATH_TO_ENV_FILE =  __DIR__ . '/../../../../../.env';

    private const DEFAULT_PATH_TO_ENV_FILE =  __DIR__ . '/../../resources/.env';

    private const DATABASE_CONFIGURATIONS = array(
        DatabaseConfigurationEnums::DB_HOST,
        DatabaseConfigurationEnums::DB_PORT,
        DatabaseConfigurationEnums::DB_NAME,
        DatabaseConfigurationEnums::DB_USER,
        DatabaseConfigurationEnums::DB_PASS,
        DatabaseConfigurationEnums::DB_PREFIX
    );

    public static function loadEnvironmentVariables(): void
    {

        if (self::testIfAllRequiredEnvironmentVariablesArePresent()) {
            return;
        }

        self::clearDatabaseEnvironmentVariables();

        $dotenv = Dotenv::createImmutable(
            self::getDatabaseConfigurationFile()
        );
        $dotenv->load();

        $dotenv->required(DatabaseConfigurationEnums::DB_HOST)->notEmpty();
        $dotenv->required(DatabaseConfigurationEnums::DB_PORT)->notEmpty();
        $dotenv->required(DatabaseConfigurationEnums::DB_PORT)->isInteger();
        $dotenv->required(DatabaseConfigurationEnums::DB_NAME)->notEmpty();
        $dotenv->required(DatabaseConfigurationEnums::DB_USER)->notEmpty();
        $dotenv->required(DatabaseConfigurationEnums::DB_PASS);
        $dotenv->required(DatabaseConfigurationEnums::DB_PREFIX)->notEmpty();

    }

    public static function clearDatabaseEnvironmentVariables(): void
    {
        foreach (self::DATABASE_CONFIGURATIONS as $environment_variable) {
            if (isset($_ENV[$environment_variable])) {
                unset($_ENV[$environment_variable]);
            }
        }
    }

    private static function testIfAllRequiredEnvironmentVariablesArePresent(): bool
    {

        foreach (self::DATABASE_CONFIGURATIONS as $environment_variable) {
            if (!isset($_ENV[$environment_variable])) {
                return false;
            }
        }

        return true;

    }

    private static function getDatabaseConfigurationFile(): string
    {

        if (!file_exists(self::PATH_TO_ENV_FILE)) {
            return \dirname(self::DEFAULT_PATH_TO_ENV_FILE);
        } else {
            return \dirname(self::PATH_TO_ENV_FILE);
        }

    }

}
