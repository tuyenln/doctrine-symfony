<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220102040817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $usersTable = $schema->createTable('user');
        $usersTable->addColumn('id', 'integer', ['autoincrement' => true, 'notnull' => true]);
        $usersTable->addColumn('username', 'string', ['length' => 255]);
        $usersTable->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('user');
    }
}
