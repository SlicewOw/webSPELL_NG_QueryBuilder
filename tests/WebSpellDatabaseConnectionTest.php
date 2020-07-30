<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use \webspell_ng\WebSpellDatabaseConnection;

final class WebSpellDatabaseConnectionTest extends TestCase
{

    public function testIfUnexpectedValueExceptionIsThrownIfDatabaseConfigFileIsMissing(): void
    {

        $this->expectException(UnexpectedValueException::class);

        WebSpellDatabaseConnection::getDatabaseConnection();

    }

}
