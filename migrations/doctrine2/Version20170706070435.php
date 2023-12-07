<?php

namespace Art4\IsYouthwebStillOnline\DoctrineMigrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170706070435 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('account_stats');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('user_total', 'integer');
        $table->addColumn('user_online', 'integer');
        $table->addColumn('created_at', 'integer');
        $table->setPrimaryKey(['id']);
        $table->addOption('charset', 'utf8');
        $table->addOption('collate', 'utf8_unicode_ci');
        $table->addOption('engine', 'InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $schema->dropTable('account_stats');
    }
}
