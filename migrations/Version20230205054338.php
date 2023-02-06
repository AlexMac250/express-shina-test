<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230205054338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE cart_items_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE carts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE manufacturers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_model_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_types_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE products_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE cart_items (id INT NOT NULL, cart_id INT DEFAULT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BEF484451AD5CDBF ON cart_items (cart_id)');
        $this->addSql('CREATE INDEX IDX_BEF484454584665A ON cart_items (product_id)');
        $this->addSql('CREATE TABLE carts (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE manufacturers (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_94565B125E237E06 ON manufacturers (name)');
        $this->addSql('CREATE TABLE product_model (id INT NOT NULL, product_type_id INT DEFAULT NULL, manufacturer_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_76C9098514959723 ON product_model (product_type_id)');
        $this->addSql('CREATE INDEX IDX_76C90985A23B42D ON product_model (manufacturer_id)');
        $this->addSql('CREATE UNIQUE INDEX product_model_unique_idx ON product_model (name, manufacturer_id, product_type_id)');
        $this->addSql('CREATE TABLE product_types (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F86CF26C5E237E06 ON product_types (name)');
        $this->addSql('CREATE TABLE products (id INT NOT NULL, product_model_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B3BA5A5AB2C5DD70 ON products (product_model_id)');
        $this->addSql('CREATE UNIQUE INDEX product_name_product_model_unique_idx ON products (name, product_model_id)');
        $this->addSql('ALTER TABLE cart_items ADD CONSTRAINT FK_BEF484451AD5CDBF FOREIGN KEY (cart_id) REFERENCES carts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cart_items ADD CONSTRAINT FK_BEF484454584665A FOREIGN KEY (product_id) REFERENCES products (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_model ADD CONSTRAINT FK_76C9098514959723 FOREIGN KEY (product_type_id) REFERENCES product_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_model ADD CONSTRAINT FK_76C90985A23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AB2C5DD70 FOREIGN KEY (product_model_id) REFERENCES product_model (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE cart_items_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE carts_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE manufacturers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_model_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_types_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE products_id_seq CASCADE');
        $this->addSql('ALTER TABLE cart_items DROP CONSTRAINT FK_BEF484451AD5CDBF');
        $this->addSql('ALTER TABLE cart_items DROP CONSTRAINT FK_BEF484454584665A');
        $this->addSql('ALTER TABLE product_model DROP CONSTRAINT FK_76C9098514959723');
        $this->addSql('ALTER TABLE product_model DROP CONSTRAINT FK_76C90985A23B42D');
        $this->addSql('ALTER TABLE products DROP CONSTRAINT FK_B3BA5A5AB2C5DD70');
        $this->addSql('DROP TABLE cart_items');
        $this->addSql('DROP TABLE carts');
        $this->addSql('DROP TABLE manufacturers');
        $this->addSql('DROP TABLE product_model');
        $this->addSql('DROP TABLE product_types');
        $this->addSql('DROP TABLE products');
    }
}
