<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%menus}}`.
 */
class m220905_042545_create_menus_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%menus}}', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer()->defaultValue(0),
            'name' => $this->string()->notNull(),
            'food_director' => $this->integer()->defaultValue(0),
            'organization_id' => $this->integer()->notNull(),
            'type_org_id' => $this->integer()->notNull(),
            'feeders_characters_id' => $this->integer()->notNull(),
            'age_info_id' => $this->integer()->notNull(),
            'description' => $this->text()->notNull(),
            'culinary_processing_id' => $this->integer()->notNull(),
            'dishes_characters' => $this->text()->notNull(),
            'yield' => $this->integer()->notNull(),
            'techmup_number' => $this->string()->notNull(),
            'number_of_dish' => $this->string()->notNull(),
            'created_at'=>  $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        /*organization*/
        $this->createIndex(
            'idx-menus-organization_id',
            'menus',
            'organization_id'
        );

        $this->addForeignKey(
            'menus_organization_id',  // это "условное имя" ключа
            'menus', // это название текущей таблицы
            'organization_id', // это имя поля в текущей таблице, которое будет ключом
            'organizations', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );

        /*type_org*/
        $this->createIndex(
            'idx-menus-type_org_id',
            'menus',
            'type_org_id'
        );

        $this->addForeignKey(
            'menus_type_org_id',  // это "условное имя" ключа
            'menus', // это название текущей таблицы
            'type_org_id', // это имя поля в текущей таблице, которое будет ключом
            'type_organization', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );

        /*feeders_characters*/
        $this->createIndex(
            'idx-menus-feeders_characters_id',
            'menus',
            'feeders_characters_id'
        );

        $this->addForeignKey(
            'menus_feeders_characters_id',  // это "условное имя" ключа
            'menus', // это название текущей таблицы
            'feeders_characters_id', // это имя поля в текущей таблице, которое будет ключом
            'feeders_characters', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );

        /*age_info*/
        $this->createIndex(
            'idx-menus-age_info_id',
            'menus',
            'age_info_id'
        );

        $this->addForeignKey(
            'menus_age_info_id',  // это "условное имя" ключа
            'menus', // это название текущей таблицы
            'age_info_id', // это имя поля в текущей таблице, которое будет ключом
            'age_info', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );

        /*culinary_processing*/
        $this->createIndex(
            'idx-menus-culinary_processing_id',
            'menus',
            'culinary_processing_id'
        );

        $this->addForeignKey(
            'menus_culinary_processing_id',  // это "условное имя" ключа
            'menus', // это название текущей таблицы
            'culinary_processing_id', // это имя поля в текущей таблице, которое будет ключом
            'culinary_processing', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%menus}}');
    }
}
