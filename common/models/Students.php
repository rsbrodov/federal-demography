<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "students".
 *
 * @property int $id
 * @property int $students_class_id
 * @property int $organization_id
 * @property string $name
 * @property int $form_study 1-очная, 2-домашняя
 * @property int $dis_sahar 1-есть, 0 - нет
 * @property int $dis_ovz 1-есть, 0 - нет
 * @property int $dis_cialic 1-есть, 0 - нет
 * @property int $dis_fenilketon 1-есть, 0 - нет
 * @property int $dis_mukovis 1-есть, 0 - нет
 * @property int $al_moloko 1-есть, 0 - нет
 * @property int $al_yico 1-есть, 0 - нет
 * @property int $al_fish 1-есть, 0 - нет
 * @property int $al_chocolad 1-есть, 0 - нет
 * @property int $al_orehi 1-есть, 0 - нет
 * @property int $al_citrus 1-есть, 0 - нет
 * @property int $al_med 1-есть, 0 - нет
 * @property int $al_pshenica 1-есть, 0 - нет
 * @property int $otkaz_pitaniya 1-отказался, 0 - не отказался
 * @property int $prichina_otkaza 1-болезни, 2 - дом, 3-иные
 * @property string $created_at
 */
class Students extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'students';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['students_class_id', 'organization_id', 'name', 'form_study', 'dis_sahar', 'dis_ovz', 'dis_cialic', 'dis_fenilketon', 'dis_mukovis', 'al_moloko', 'al_yico', 'al_fish', 'al_chocolad', 'al_orehi', 'al_citrus', 'al_med', 'al_pshenica', 'al_inoe', 'otkaz_pitaniya', 'prichina_otkaza'], 'required'],
            [['students_class_id', 'organization_id', 'form_study', 'dis_sahar', 'dis_ovz', 'dis_cialic', 'dis_fenilketon', 'dis_mukovis', 'al_moloko', 'al_yico', 'al_fish', 'al_chocolad', 'al_orehi', 'al_citrus', 'al_med', 'al_pshenica', 'al_inoe', 'otkaz_pitaniya', 'prichina_otkaza'], 'integer'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'students_class_id' => 'Students Class ID',
            'organization_id' => 'Organization ID',
            'name' => 'Name',
            'form_study' => 'Form Study',
            'dis_sahar' => 'Dis Sahar',
            'dis_ovz' => 'Dis Ovz',
            'dis_cialic' => 'Dis Cialic',
            'dis_fenilketon' => 'Dis Fenilketon',
            'dis_mukovis' => 'Dis Mukovis',
            'al_moloko' => 'Al Moloko',
            'al_yico' => 'Al Yico',
            'al_fish' => 'Al Fish',
            'al_chocolad' => 'Al Chocolad',
            'al_orehi' => 'Al Orehi',
            'al_citrus' => 'Al Citrus',
            'al_med' => 'Al Med',
            'al_pshenica' => 'Al Pshenica',
            'al_inoe' => 'Al Inoe',
            'otkaz_pitaniya' => 'Otkaz Pitaniya',
            'prichina_otkaza' => 'Prichina Otkaza',
            'created_at' => 'Created At',
        ];
    }
}
