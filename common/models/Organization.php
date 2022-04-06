<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "organization".
 *
 *
 * @property int $id
 * @property string $title
 * @property string $short_title
 * @property string $address
 * @property int $federal_district_id
 * @property string $type_org
 * @property int $region_id
 * @property string $municipality_id
 * @property string $phone
 * @property string $email
 * @property string $inn
 * @property int $organizator_food
 * @property int $medic_service_programm
 * @property int $status
 * @property string $created_at
 */
class Organization extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organization';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['title', 'federal_district_id', 'region_id', 'municipality', 'type_org'], 'required'],
            [['federal_district_id', 'region_id', 'municipality_id', 'type_org', 'organizator_food', 'medic_service_programm', 'status', 'type_lager_id', 'forma_sobstvennosti_id', 'regim_id', 'moshnost_lager_leto', 'moshnost_lager_inoe', 'city_id'], 'integer'],
            [['created_at', 'date_sez_build', 'date_sez_med', 'anket_parent_control_link'], 'safe'],
            [['title', 'short_title', 'address', 'phone', 'email', 'inn', 'org_balansodergatel', 'sez_build', 'sez_med', 'name_dir', 'naseleni_punkt'], 'string', 'max' => 255],
            ['email', 'email'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Наименование организации',
            'type_org' => 'Тип организации',
            'short_title' => 'Короткое название организации',
            'address' => 'Юридический адрес',
            'federal_district_id' => 'Федеральный округ',
            'region_id' => 'Субъект федерации',
            'municipality_id' => 'Муниципальное образование',
            'phone' => 'Телефон организации',
            'email' => 'Электронная почта организации',
            'inn' => 'ИНН',
            'organizator_food' => 'Организатор питания',
            'medic_service_programm' => 'Настройка программы \'Мед. обслуживание\'',
            'status' => 'Статус',
            'type_lager_id' => 'Тип лагеря',
            'org_balansodergatel' => 'Наименование организации балансодержателя (полностью)',
            'forma_sobstvennosti_id' => 'Форма собственности',
            'sez_build' => 'Наличие положительного санитарно-эпидемиологического заключения на здания, строения и сооружения',
            'date_sez_build' => 'Дата выдачи СЭЗ',
            'sez_med' => 'Наличие положительного санитарно-эпидемиологического заключения на медицинскую деятельность',
            'date_sez_med' => 'Дата выдачи СЭЗ на мед. деят',
            'regim_id' => 'Режим работы',
            'moshnost_lager_leto' => 'Мощность лагеря в смену (лето)',
            'moshnost_lager_inoe' => 'Мощность лагеря в смену (иные смены)',
            'created_at' => 'Дата создания',
            'name_dir'  => 'ФИО руководителя',
            'naseleni_punkt' => 'Населенный пункт',
        ];
    }

    public function beforeSave($insert){
        if (parent::beforeSave($insert)) {
            if(Yii::$app->user->can('camp_director'))
            {
                $this->date_sez_med = strtotime($this->date_sez_med);
                $this->date_sez_build = strtotime($this->date_sez_build);
                return true;
            }
            return true;
        }
        return false;
    }

    public function get_district($id)
    {
        $district = FederalDistrict::findOne($id);
        $district = $district->name;
        return $district;
    }

    public function get_region($id)
    {
        $region = Region::find()->where(['id' => $id])->one();
        $region = $region->name;
        return $region;
    }
    public function get_municipality($id)
    {
        $municipality = Municipality::find()->where(['id' => $id])->one();
        $municipality = $municipality->name;
        return $municipality;
    }
    public function get_type_org($id)
    {
        $type_org = TypeOrganization::find()->where(['id' => $id])->one();
        $type_org = $type_org->name;
        return $type_org;
    }
    public function get_type_lager($id)
    {
        $type_lager = TypeLager::find()->where(['id' => $id])->one();
        $type_lager = $type_lager->name;
        return $type_lager;
    }
}
