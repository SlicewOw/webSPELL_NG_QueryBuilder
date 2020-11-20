<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Doctrine\DBAL\Connection;

use \webspell_ng\WebSpellDatabaseConnection;

final class WebSpellDatabaseConnectionTest extends TestCase
{

    public function testIfDefaultDatabaseIsConnectingToDevDatabase(): void
    {

        $database_connection = WebSpellDatabaseConnection::getDatabaseConnection();

        $this->assertInstanceOf(Connection::class, $database_connection);
        $this->assertTrue(!empty(WebSpellDatabaseConnection::getTablePrefix()), "Table prefix is set.");

        /**
         * Test of the lazy initialization
         */

        $database_connection = WebSpellDatabaseConnection::getDatabaseConnection();

        $this->assertInstanceOf(Connection::class, $database_connection);
        $this->assertTrue(!empty(WebSpellDatabaseConnection::getTablePrefix()), "Table prefix is still set.");

    }

}
