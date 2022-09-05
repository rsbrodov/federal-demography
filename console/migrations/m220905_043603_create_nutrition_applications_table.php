<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%nutrition_applications}}`.
 */
class m220905_043603_create_nutrition_applications_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%nutrition_applications}}', [
            'id' => $this->primaryKey(),
            'type_org_id' => $this->integer()->defaultValue(0),
            'sender_org_id' => $this->string()->notNull(),
            'reciever_org_id' => $this->integer()->defaultValue(0),
            'status' => $this->integer()->notNull(),
            'created_at'=>  $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        /*sender*/
        $this->createIndex(
            'idx-nutrition_applications-sender_org_id',
            'nutrition_applications',
            'sender_org_id'
        );

        $this->addForeignKey(
            'nutrition_applications_sender_org_id',  // это "условное имя" ключа
            'nutrition_applications', // это название текущей таблицы
            'sender_org_id', // это имя поля в текущей таблице, которое будет ключом
            'organizations', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );

        /*sender*/
        $this->createIndex(
            'idx-nutrition_applications-reciever_org_id',
            'nutrition_applications',
            'reciever_org_id'
        );

        $this->addForeignKey(
            'nutrition_applications_reciever_org_id',  // это "условное имя" ключа
            'nutrition_applications', // это название текущей таблицы
            'reciever_org_id', // это имя поля в текущей таблице, которое будет ключом
            'organizations', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );

        /*type_org*/
        $this->createIndex(
            'idx-nutrition_applications-type_org_id',
            'nutrition_applications',
            'type_org_id'
        );

        $this->addForeignKey(
            'nutrition_applications_type_org_id',  // это "условное имя" ключа
            'nutrition_applications', // это название текущей таблицы
            'type_org_id', // это имя поля в текущей таблице, которое будет ключом
            'type_organization', // это имя таблицы, с которой хотим связаться
            'id', // это поле таблицы, с которым хотим связаться
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%nutrition_applications}}');
    }
}
