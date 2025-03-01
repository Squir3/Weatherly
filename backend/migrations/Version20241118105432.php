<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241118105432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Tworzy tabelę weather zgodną z encją WeatherQuery';
    }

    public function up(Schema $schema): void
    {
         $this->addSql('
            CREATE TABLE weather (
                id INT AUTO_INCREMENT NOT NULL,
                query_date DATETIME NOT NULL,
                location VARCHAR(255) NOT NULL,
                latitude DOUBLE NOT NULL,
                longitude DOUBLE NOT NULL,
                temperature DOUBLE NOT NULL,
                humidity DOUBLE NOT NULL,
                weather_description VARCHAR(255) NOT NULL,
                PRIMARY KEY(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE weather');
    }
}