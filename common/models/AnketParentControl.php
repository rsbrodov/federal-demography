<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "anket_parent_control".
 *
 * @property int $id
 * @property int $organization_id
 * @property int $date
 * @property string $name
 * @property int $menu_id
 * @property int $question1 Соответствует ли фактическое меню, объемы порций двухнедельному меню, утвержденному руководителем образовательной организации
 * @property int $question2 Организовано ли питание детей, требующих индивидуального подхода в организации питания с учетом имеющихся нарушений здоровья
 * @property int $question3 Все ли дети с сахарным диабетом и пищевой аллергией питаются вместе с другими детьми
 * @property int $question4 Все ли дети моют руки перед едой
 * @property int $question5 Созданы ли условия для мытья и дезинфекции рук
 * @property int $question6 Все ли дети едят сидя
 * @property int $question7 Все ли дети успевают поесть за перемену (хватает ли времени для приема пищи
 * @property int $question8 Есть ли замечания по чистоте посуды
 * @property int $question9 Есть ли замечания по чистоте столов
 * @property int $question10 Есть ли замечания к сервировке столов
 * @property int $question11 Теплые ли блюда выдаются детям
 * @property int $question12 Участвуют ли дети в накрывании на столы
 * @property int $question13 Лица, накрывающие на столы, работают в специальной одежде (халат, головной убор
 * @property int $question14 Организовано ли наряду с основным питанием дополнительное питание
 * @property int $count
 * @property int $masa_porcii
 * @property int $masa_othodov
 * @property string $created_at
 */
class AnketParentControl extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'anket_parent_control';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['organization_id', 'date', 'name', 'smena', 'peremena', 'question1', 'question2', 'question3', 'question4', 'question5', 'question6', 'question7', 'question8', 'question9', 'question10', 'question11', /*'question12',*/ 'question13', 'question14', 'count', 'masa_porcii', 'masa_othodov'], 'required'],
            [['organization_id', 'smena', 'question1', 'question2', 'question3', 'question4', 'question5', 'question6', 'question7', 'question8', 'question9', 'question10', 'question11', /*'question12',*/ 'question13', 'question14', 'count', 'masa_porcii', 'upolnomoch_org_id', 'indicator_parent'], 'integer'],
            [['created_at', 'question2', 'question3', 'peremena'], 'safe'],
            [['masa_othodov', 'procent', 'test_food', 'test', 'itog_ball'], 'double'],
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
            'organization_id' => 'Organization ID',
            'date' => 'Дата проведения мероприятия родительского контроля ',
            'name' => 'Ответственные лица (ФИО)',
            'smena' => 'Смена',
            'peremena' => 'Перемена',
            'question1' => '1.Соответствует ли фактическое меню, объемы порций двухнедельному меню, утвержденному руководителем образовательной организации?',
            'question2' => '2.Организовано ли питание детей, требующих индивидуального подхода в организации питания с учетом имеющихся нарушений здоровья (сахарный диабет, целиакия, пищевая аллергия)',
            'question3' => '3.Все ли дети с сахарным диабетом, пищевой аллергией, ОВЗ, фенилкетонурией, целиакией, муковисцидозом питаются в столовой? ',
            'question4' => '5.Все ли дети моют руки перед едой?',
            'question5' => '4.Созданы ли условия для мытья и дезинфекции рук? ',
            'question6' => '6.Все ли дети едят сидя? ',
            'question7' => '7.Все ли дети успевают поесть за перемену (хватает ли времени для приема пищи)?',
            'question8' => '8.Есть ли замечания по чистоте посуды?',
            'question9' => '9.Есть ли замечания по чистоте столов?',
            'question10' => '10.Есть ли замечания к сервировке столов?',
            'question11' => '11.Теплые ли блюда выдаются детям?',
//            'question12' => '12.Участвуют ли дети в накрывании на столы?',
            'question13' => '12.Лица, накрывающие на столы, работают в специальной одежде (халат, головной убор)?',
            'question14' => '13.Организовано ли наряду с основным питанием дополнительное питание (возможность самостоятельного приобретения блюд через линию раздачи или буфет)? ',
            'count' => '14.Число детей, питающихся на данной перемене',
            'masa_porcii' => '15.Масса всех блюд на одного ребенка по меню(в граммах)',
            'masa_othodov' => '16.Общая масса несъеденной пищи (взвешивается несъеденная пища в КГ).',
            'created_at' => 'Дата сохранения',
        ];
    }



    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {   //Если данные вносит родительский комитет
            if(Yii::$app->request->pathInfo == 'anket-parent-control/parent-outside-link')
            {
                $explode_mas = explode('-', $_GET['id']);
                $organization = Organization::find()->where(['id' => $explode_mas[1]])->one();
                $this->organization_id = $organization->id;
                $this->status = 1;
                $this->indicator_parent = 1;
                return true;
            }

            //Если родительский контроль вносит директор
            if(Yii::$app->request->pathInfo == 'anket-parent-control/create')
            {
                $this->organization_id = Yii::$app->user->identity->organization_id;
                $this->status = 1;
                return true;
            }

            //Если внутренний контроль вносит директор
            if(Yii::$app->request->pathInfo == 'anket-parent-control/inside')
            {
                $this->organization_id = Yii::$app->user->identity->organization_id;
                $this->status = 2;
                return true;
            }
            //Если это общественный контроль
            if(Yii::$app->request->pathInfo == 'anket-parent-control/social')
            {
                $this->upolnomoch_org_id = Yii::$app->user->identity->organization_id;
                $this->status = 3;
                return true;
            }
            if(Yii::$app->request->pathInfo == 'anket-parent-control/script')
            {

                return true;
            }

        }
        return false;
    }
    public function get_result($id)
    {
        $model = AnketParentControl::findOne($id);

        $items = [1,2,3,4,5,6,7,8,9,10,11,13,14];
        //for($i=1;$i<=14;$i++){
        foreach($items as $i){
            $question = 'question'.$i;
            if($i==8 || $i == 9 || $i == 10)
            {
                if ($model->$question == 1)
                {
                    $count = $count + 0;
                }
                if ($model->$question == 0)
                {
                    $count = $count + 2;
                }
            }else{
                if ($model->$question == 1)
                {
                    $count = $count + 2;
                }
                if ($model->$question == 2)
                {
                    $count = $count + 1;
                }
                if ($model->$question == 0)
                {
                    $count = $count + 0;
                }
            }
        }


        $np = ($model->masa_othodov/$model->masa_porcii) * 100;
        if($np <= 20 ){
            $np_itog = 12;
        }
        elseif($np > 20 && $np <= 30){
            $np_itog = 8;
        }
        elseif($np > 40 && $np <= 50){
            $np_itog = 3;
        }
        elseif($np > 50 && $np <= 60){
            $np_itog = 1;
        }
        else{
            $np_itog = 0;
        }
        $itog = $count + $np_itog;
        return $itog;
    }

    public function get_result_test($id)
    {
        $model = AnketParentControl::findOne($id);
        $items = [1,2,3,4,5,6,7,8,9,10,11,13,14];
        foreach($items as $i){
            $question = 'question'.$i;
            if($i == 8 || $i == 9 || $i == 10)
            {
                if ($model->$question == 1)
                {
                    $count = $count + 0;
                }
                if ($model->$question == 0)
                {
                    $count = $count + 2;
                }
            }elseif($i == 2){
                if($model->$question == 4){
                    $count = $count +  2;
                }
                if($model->$question == 1){
                    $count = $count +  2;
                }
                if($model->$question == 3){
                    $count = $count +  1;
                }
                if($model->$question == 0){
                    $count = $count +  0;
                }
            }else{
                if($model->$question == 1){
                    $count = $count + 2;
                }
                if($model->$question == 2){
                    $count = $count + 1;
                }
                if($model->$question == 0){
                    $count = $count + 0;
                }
            }
        }

        return $count;
    }

    public function get_result_food($id, $field)
    {
        $model = AnketParentControl::findOne($id);
        if($field == 'procent'){
            if($model->masa_porcii == 0 || $model->count == 0){
                $np = 0;
            }
            else{
                $np = (($model->masa_othodov * 1000)/($model->masa_porcii * $model->count)) * 100;
            }
            return round($np, 1);
        }
        else{
            if($model->masa_porcii == 0 || $model->count == 0){
                $np = 0;
            }else{
                $np = (($model->masa_othodov * 1000)/($model->masa_porcii * $model->count)) * 100;
            }
            if($np <= 20 ){
                $np_itog = 12;
            }
            elseif($np > 20 && $np <= 30){
                $np_itog = 8;
            }
            elseif($np > 30 && $np <= 50){
                $np_itog = 3;
            }
            elseif($np > 50 && $np <= 60){
                $np_itog = 1;
            }
            else{
                $np_itog = 0;
            }
            return $np_itog;

        }
    }

    public function yes_no($quest, $answer, $field)
    {
        if($field == 'answer'){
            if($answer == 1){
                return 'Да';
            }
            if($answer == 0){
                return 'Нет';
            }
            if($answer == 3){
                return 'Частично';
            }
            if($answer == 4){
                return 'Детей с заболеваниями нет';
            }
        }
        if($field == 'ball'){
            if($quest == 8 || $quest == 9 || $quest == 10){
                if($answer == 1){
                    return 0;
                }
                if($answer == 0){
                    return 2;
                }
            }else{
                if($answer == 4){
                    return 2;
                }
                if($answer == 1){
                    return 2;
                }
                if($answer == 3){
                    return 1;
                }
                if($answer == 0){
                    return 0;
                }
            }
        }
    }


    public function get_ball($id)
    {
        $model = AnketParentControl::findOne($id);

            if($model->masa_porcii == 0 || $model->count == 0){
                $np = 0;
            }else{
                $np = (($model->masa_othodov * 1000)/($model->masa_porcii * $model->count)) * 100;
            }
            if($np <= 20 ){
                $np_itog = 12;
            }
            elseif($np > 20 && $np <= 30){
                $np_itog = 8;
            }
            elseif($np > 40 && $np <= 50){
                $np_itog = 3;
            }
            elseif($np > 50 && $np <= 60){
                $np_itog = 1;
            }
            else{
                $np_itog = 0;
            }




        $items = [1,2,3,4,5,6,7,8,9,10,11,13,14];
        foreach($items as $i){
            $question = 'question'.$i;
            if($i == 8 || $i == 9 || $i == 10){
                if($model->$question == 1){
                    $count = $count + 0;
                }
                if($model->$question == 0){
                    $count = $count + 2;
                }
            }else{
                if($model->$question == 1){
                    $count = $count + 2;
                }
                if($model->$question == 2){
                    $count = $count + 1;
                }
                if($model->$question == 0){
                    $count = $count + 0;
                }
            }

        }

        return $count + $np_itog;

        }



    public function get_info_municipality($municipality_id, $type_control,$date_start,$date_end, $indicator)
    {
        $mun = [];$dates = [];

        if($type_control == 3){
            $field[] = 1;
            $field[] = 2;
        }else{
            $field[] = $type_control;
        }

        if($indicator == 'region'){
            $report_municipalities = \common\models\Municipality::find()->where(['region_id' => Organization::findOne(Yii::$app->user->identity->organization_id)->region_id])->all();
        }
        if($indicator == 'municipality'){
            $report_municipalities = \common\models\Municipality::find()->where(['id' => $municipality_id])->one();
            $organizations = Organization::find()->where(['type_org' => 3, 'municipality_id' => $report_municipalities->id])->andWhere(['!=', 'id', 7])->all();
        }
        if($indicator == 'organization'){
            $organizations = Organization::find()->where(['id' => $municipality_id])->andWhere(['!=', 'id', 7])->all();
        }

         foreach($organizations as $organization){$org = [];
            if ($date_start == 0 && $date_end != 0){
                $models = AnketParentControl::find()->where(['organization_id' => $organization->id, 'status' => $field])->andWhere(['<=', 'date', strtotime($date_end)])->orderBy(['date' => SORT_ASC])->all();
            }
            elseif ($date_start != 0 && $date_end == 0){
                $models = AnketParentControl::find()->where(['organization_id' => $organization->id, 'status' => $field])->andWhere(['>=', 'date', strtotime($date_start)])->orderBy(['date' => SORT_ASC])->all();
            }
            elseif ($date_start == 0 && $date_end == 0){
                $models = AnketParentControl::find()->where(['organization_id' => $organization->id, 'status' => $field])->orderBy(['date' => SORT_ASC])->all();
            }
            else{
                $models = AnketParentControl::find()->where(['organization_id' => $organization->id, 'status' => $field])->andWhere(['>=', 'date', strtotime($date_start)])->andWhere(['<=', 'date', strtotime($date_end)])->orderBy(['date' => SORT_ASC])->all();
            }
            //сумма всех анкет по организации
            if (!empty($models))
            {
                foreach ($models as $model)
                {
                    $dates[date("m", $model->date)] = date("m", $model->date);
                    $org[date("m", $model->date)]['procent'] = $org[date("m", $model->date)]['procent'] + $model->get_result_food($model->id, 'procent');
                    $org[date("m", $model->date)]['count'] = $org[date("m", $model->date)]['count'] + 1;
                    $org[date("m", $model->date)]['ball'] = $org[date("m", $model->date)]['ball'] + ($model->get_result_test($model->id) + $model->get_result_food($model->id, 'ball'));
                    $mun[date("m", $model->date)]['count'] = $mun[date("m", $model->date)]['count'] + 1;
                }


                //среднее всех анкет по организации

                foreach ($dates as $date)
                {
                    if(!empty($org[$date]['count'])){
                        $mun[$date]['count_org'] = $mun[$date]['count_org'] + 1;
                        $org[$date]['procent'] = round($org[$date]['procent'] / $org[$date]['count'], 1);
                        $org[$date]['ball'] = round($org[$date]['ball'] / $org[$date]['count'], 1);
                    }

                }
                //подготовка cумма по муниципальному
                foreach ($dates as $date)
                {
                    $mun[$date]['procent'] = $mun[$date]['procent'] + $org[$date]['procent'];
                    $mun[$date]['ball'] = $mun[$date]['ball'] + $org[$date]['ball'];
                }
            }

        }
        //среднее по муниципальному
        foreach ($dates as $date)
        {
            //arraykeyexistdata
            $mun[$date]['procent'] = round($mun[$date]['procent']/$mun[$date]['count_org'], 1);
            $mun[$date]['count'] = round($mun[$date]['count']/$mun[$date]['count_org']);
            $mun[$date]['ball'] = round($mun[$date]['ball']/$mun[$date]['count_org'], 1);
        }
        return $mun;



    }


    public function get_info_municipality2($municipality_id, $type_control,$date_start,$date_end, $indicator)
    {
        $dates = [];
        if($type_control == 3){
            $field[] = 1;
            $field[] = 2;
        }else{
            $field[] = $type_control;
        }

        if($indicator == 'region'){
            $report_municipalities = \common\models\Municipality::find()->where(['region_id' => $municipality_id])->all();
        }
        if($indicator == 'municipality'){
            $report_municipalities = \common\models\Municipality::find()->where(['id' => $municipality_id])->all();
        }
        if($indicator == 'organization'){
            $report_municipalities = \common\models\Municipality::find()->where(['id' =>Organization::find()->where(['id' => $municipality_id])->andWhere(['!=', 'id', 7])->one()->municipality_id])->all();
        }
        $reg = [];
        foreach($report_municipalities as $municipality){$mun=[];
            if($indicator == 'organization'){
                $organizations = Organization::find()->where(['id' => $municipality_id])->andWhere(['!=', 'id', 7])->all();
            }else{
                $organizations = Organization::find()->where(['type_org' => 3, 'municipality_id' => $municipality->id])->andWhere(['!=', 'id', 7])->all();
            }
            foreach ($organizations as $organization){$org = [];
                if ($date_start == 0 && $date_end != 0)
                {
                    $models = AnketParentControl::find()->where(['organization_id' => $organization->id, 'status' => $field])->andWhere(['<=', 'date', strtotime($date_end)])->orderBy(['date' => SORT_ASC])->all();
                }
                elseif ($date_start != 0 && $date_end == 0)
                {
                    $models = AnketParentControl::find()->where(['organization_id' => $organization->id, 'status' => $field])->andWhere(['>=', 'date', strtotime($date_start)])->orderBy(['date' => SORT_ASC])->all();
                }
                elseif ($date_start == 0 && $date_end == 0)
                {
                    $models = AnketParentControl::find()->where(['organization_id' => $organization->id, 'status' => $field])->orderBy(['date' => SORT_ASC])->all();
                }
                else
                {
                    $models = AnketParentControl::find()->where(['organization_id' => $organization->id, 'status' => $field])->andWhere(['>=', 'date', strtotime($date_start)])->andWhere(['<=', 'date', strtotime($date_end)])->orderBy(['date' => SORT_ASC])->all();
                }
                //сумма всех анкет по организации
                if (!empty($models))
                {
                    foreach ($models as $model)
                    {
                        $dates[date("m", $model->date)] = date("m", $model->date);
                        $org[date("m", $model->date)]['procent'] = $org[date("m", $model->date)]['procent'] + $model->get_result_food($model->id, 'procent');
                        $org[date("m", $model->date)]['count'] = $org[date("m", $model->date)]['count'] + 1;
                        $org[date("m", $model->date)]['ball'] = $org[date("m", $model->date)]['ball'] + ($model->get_result_test($model->id) + $model->get_result_food($model->id, 'ball'));
                        $mun[date("m", $model->date)]['count'] = $mun[date("m", $model->date)]['count'] + 1;
                    }


                    //среднее всех анкет по организации

                    foreach ($dates as $date)
                    {
                        if(!empty($org[$date]['count']))
                        {
                            $mun[$date]['count_org'] = $mun[$date]['count_org'] + 1;
                            $org[$date]['procent'] = round($org[$date]['procent'] / $org[$date]['count'], 1);
                            $org[$date]['ball'] = round($org[$date]['ball'] / $org[$date]['count'], 1);
                        }

                    }
                    //подготовка cумма по муниципальному
                    foreach ($dates as $date)
                    {
                        $mun[$date]['procent'] = $mun[$date]['procent'] + $org[$date]['procent'];
                        $mun[$date]['ball'] = $mun[$date]['ball'] + $org[$date]['ball'];
                    }
                }

            }
            //среднее по муниципальному
            foreach ($dates as $date)
            {
                //arraykeyexistdata
                if(!empty($mun[$date]['count_org'])){
                    $mun[$date]['procent'] = round($mun[$date]['procent'] / $mun[$date]['count_org'], 1);
                    $mun[$date]['count'] = round($mun[$date]['count'] / $mun[$date]['count_org']);
                    $mun[$date]['ball'] = round($mun[$date]['ball'] / $mun[$date]['count_org'], 1);
                }
            }
            foreach ($dates as $date)
            {
                if (!empty($mun[$date]['count_org'])){
                    $reg[$date]['count_mun']++;
                }
                //сумма по региону
                $reg[$date]['procent'] = $reg[$date]['procent']+$mun[$date]['procent'];
                $reg[$date]['ball'] = $reg[$date]['ball']+$mun[$date]['ball'];
                $reg[$date]['count'] = $reg[$date]['count']+$mun[$date]['count'];
            }
        }
        //среднее по региону
        foreach ($dates as $date){
            $itog[$date]['procent'] = round($reg[$date]['procent'] / $reg[$date]['count_mun'], 1);
            $itog[$date]['count'] = round($reg[$date]['count']/$reg[$date]['count_mun']);
            $itog[$date]['ball'] = round($reg[$date]['ball'] / $reg[$date]['count_mun'], 1);
        }
        return $itog;
    }
}
