<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20241004094150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create initial db schema';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, short_description LONGTEXT NOT NULL, long_description LONGTEXT NOT NULL, released_date VARCHAR(255) NOT NULL, released_at DATETIME NOT NULL, cover_image VARCHAR(255) NOT NULL, staff JSON NOT NULL, casting JSON NOT NULL, media_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, duration_in_month INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, account_status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE watch_history (id INT AUTO_INCREMENT NOT NULL, last_watched_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', number_of_views INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE watch_history');
    }
}
