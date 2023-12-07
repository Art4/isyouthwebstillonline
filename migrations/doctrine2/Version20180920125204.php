<?php

namespace Art4\IsYouthwebStillOnline\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180920125204 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $table = $schema->getTable('account_stats');
        $table->addOption('charset', 'utf8mb4');
        $table->addOption('collate', 'utf8mb4_unicode_ci');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $table = $schema->getTable('account_stats');
        $table->addOption('charset', 'utf8');
        $table->addOption('collate', 'utf8_unicode_ci');
    }
}
