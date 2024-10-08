<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241008125802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category CHANGE name nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7A19A357');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C87AE8BA9');
        $this->addSql('DROP INDEX IDX_9474526C7A19A357 ON comment');
        $this->addSql('DROP INDEX IDX_9474526C87AE8BA9 ON comment');
        $this->addSql('ALTER TABLE comment ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE parrent_comment_id parent_comment_id INT DEFAULT NULL, CHANGE contributor_id publisher_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CBF2AF943 FOREIGN KEY (parent_comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C40C86FCE FOREIGN KEY (publisher_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_9474526CBF2AF943 ON comment (parent_comment_id)');
        $this->addSql('CREATE INDEX IDX_9474526C40C86FCE ON comment (publisher_id)');
        $this->addSql('ALTER TABLE episode CHANGE duration duration INT NOT NULL, CHANGE release_date_at released_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE language CHANGE code code VARCHAR(3) NOT NULL, CHANGE name nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE media DROP media_type, CHANGE realease_date_at release_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE playlist CHANGE updated_at updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE playlist_media CHANGE added_at added_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE playlist_subscription DROP FOREIGN KEY FK_832940C61220EA6');
        $this->addSql('DROP INDEX IDX_832940C61220EA6 ON playlist_subscription');
        $this->addSql('ALTER TABLE playlist_subscription DROP creator_id, CHANGE subscribed_at subscribed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE season ADD number VARCHAR(255) NOT NULL, DROP season_number');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D37808B1AD');
        $this->addSql('DROP INDEX IDX_A3C664D37808B1AD ON subscription');
        $this->addSql('ALTER TABLE subscription DROP subscriber_id, CHANGE name name VARCHAR(255) NOT NULL, CHANGE duration_in_months duration INT NOT NULL');
        $this->addSql('ALTER TABLE subscription_history ADD start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP start_date_at, DROP end_date_at');
        $this->addSql('ALTER TABLE user ADD current_subscription_id INT DEFAULT NULL, CHANGE username username VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DDE45DDE FOREIGN KEY (current_subscription_id) REFERENCES subscription (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649DDE45DDE ON user (current_subscription_id)');
        $this->addSql('ALTER TABLE watch_history CHANGE last_watched_at last_watched_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category CHANGE nom name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE watch_history CHANGE last_watched_at last_watched_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CBF2AF943');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C40C86FCE');
        $this->addSql('DROP INDEX IDX_9474526CBF2AF943 ON comment');
        $this->addSql('DROP INDEX IDX_9474526C40C86FCE ON comment');
        $this->addSql('ALTER TABLE comment DROP created_at, CHANGE publisher_id contributor_id INT NOT NULL, CHANGE parent_comment_id parrent_comment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7A19A357 FOREIGN KEY (contributor_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C87AE8BA9 FOREIGN KEY (parrent_comment_id) REFERENCES comment (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9474526C7A19A357 ON comment (contributor_id)');
        $this->addSql('CREATE INDEX IDX_9474526C87AE8BA9 ON comment (parrent_comment_id)');
        $this->addSql('ALTER TABLE media ADD media_type VARCHAR(255) NOT NULL, CHANGE release_date realease_date_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE subscription_history ADD start_date_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD end_date_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP start_at, DROP end_at');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649DDE45DDE');
        $this->addSql('DROP INDEX IDX_8D93D649DDE45DDE ON `user`');
        $this->addSql('ALTER TABLE `user` DROP current_subscription_id, CHANGE username username VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE subscription ADD subscriber_id INT DEFAULT NULL, CHANGE name name VARCHAR(100) NOT NULL, CHANGE duration duration_in_months INT NOT NULL');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D37808B1AD FOREIGN KEY (subscriber_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_A3C664D37808B1AD ON subscription (subscriber_id)');
        $this->addSql('ALTER TABLE season ADD season_number INT NOT NULL, DROP number');
        $this->addSql('ALTER TABLE episode CHANGE duration duration TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\', CHANGE released_at release_date_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE playlist_subscription ADD creator_id INT NOT NULL, CHANGE subscribed_at subscribed_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE playlist_subscription ADD CONSTRAINT FK_832940C61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_832940C61220EA6 ON playlist_subscription (creator_id)');
        $this->addSql('ALTER TABLE playlist CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE playlist_media CHANGE added_at added_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE language CHANGE code code VARCHAR(255) NOT NULL, CHANGE nom name VARCHAR(255) NOT NULL');
    }
}
