<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dishes}}`.
 */
class m220905_041214_create_dishes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dishes}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'organization_id' => $this->integer()->notNull(),
            'dishes_category_id' => $this->integer()->notNull(),
            'recipes_collection_id' => $this->integer()->notNull(),
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
            'idx-dishes-organization_id',
            'dishes',
            'organization_id'
        );

        $this->addForeignKey(
            'dishes_organization_id',  // это "условное имя" ключа
            'dishes', // это название текущей таблицы
            'organization_id', // это имя поля в текущей таблице, которое будет ключом
            'organizations', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );

        /*category*/
        $this->createIndex(
            'idx-dishes-dishes_category_id',
            'dishes',
            'dishes_category_id'
        );

        $this->addForeignKey(
            'dishes_dishes_category_id',  // это "условное имя" ключа
            'dishes', // это название текущей таблицы
            'dishes_category_id', // это имя поля в текущей таблице, которое будет ключом
            'dishes_category', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );

        /*recipes*/
        $this->createIndex(
            'idx-dishes-recipes_collection_id',
            'dishes',
            'recipes_collection_id'
        );

        $this->addForeignKey(
            'dishes_recipes_collection_id',  // это "условное имя" ключа
            'dishes', // это название текущей таблицы
            'recipes_collection_id', // это имя поля в текущей таблице, которое будет ключом
            'recipes_collections', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );

        /*recipes*/
        $this->createIndex(
            'idx-dishes-culinary_processing_id',
            'dishes',
            'culinary_processing_id'
        );

        $this->addForeignKey(
            'dishes_culinary_processing_id',  // это "условное имя" ключа
            'dishes', // это название текущей таблицы
            'culinary_processing_id', // это имя поля в текущей таблице, которое будет ключом
            'culinary_processings', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%dishes}}');
    }
}
