<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menus_send".
 *
 * @property int $id
 * @property int $sender_org_id
 * @property int $reciever_org_id
 * @property int $menu_id
 * @property string $created_at
 */
class MenusSend extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menus_send';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sender_org_id', 'reciever_org_id', 'sender_menu_id', 'reciever_menu_id', 'reciever_type_org'], 'required'],
            [['sender_org_id', 'reciever_org_id', 'sender_menu_id', 'reciever_menu_id', 'reciever_type_org'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender_org_id' => 'Sender Org ID',
            'reciever_org_id' => 'Reciever Org ID',
            'menu_id' => 'Menu ID',
            'created_at' => 'Created At',
        ];
    }
}
