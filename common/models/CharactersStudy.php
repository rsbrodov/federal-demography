<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "characters_study".
 *
 * @property int $id
 * @property int $user_id
 * @property int $organization_id
 * @property int $class_number
 * @property string $class_letter
 * @property int $count
 * @property int $count_home
 * @property int $count_ochno
 * @property int $sahar
 * @property int $cialic
 * @property int $allergy
 * @property int $smena
 * @property int $number_peremena
 * @property int $types_pit
 * @property int $otkaz_home
 * @property int $otkaz_sahar
 * @property int $otkaz_cialic
 * @property int $otkaz_allergy
 * @property int $otkaz_inoe
 * @property string $created_at
 */
class CharactersStudy extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'characters_study';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'organization_id', 'class_number', 'class_letter', 'count', 'count_home', 'count_ochno', 'sahar', 'cialic', 'allergy', 'smena', 'number_peremena', 'types_pit', 'otkaz_home', 'otkaz_sahar', 'otkaz_cialic', 'otkaz_ovz', 'otkaz_fenilketon', 'otkaz_mukovis', 'otkaz_allergy', 'otkaz_inoe', 'ovz', 'fenilketon', 'mukovis'], 'required'],
            [['user_id', 'organization_id', 'class_number', 'count', 'count_home', 'count_ochno', 'sahar', 'cialic', 'allergy', 'smena', 'number_peremena', 'types_pit', 'otkaz_home', 'otkaz_sahar', 'otkaz_cialic', 'otkaz_ovz', 'otkaz_fenilketon', 'otkaz_mukovis', 'otkaz_allergy', 'otkaz_inoe', 'ovz', 'fenilketon', 'mukovis', 'number_peremena2'], 'integer'],
            [['created_at'], 'safe'],
            [['class_letter'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Кто вносил данные',
            'organization_id' => 'Организация',
            'class_number' => 'Класс',
            'class_letter' => 'Буква',
            'count' => 'Количество детей (всего)',
            'count_home' => 'на домашнем обучении',
            'count_ochno' => 'на очном обучении',
            'sahar' => 'сахарным диабетом',
            'cialic' => 'целиакией',
            'ovz' => 'ОВЗ',
            'fenilketon' => 'Фенилкетонурией',
            'mukovis' => 'Муковисцидозом',
            'allergy' => 'пищевой аллергией',
            'smena' => 'Смена',
            'number_peremena' => 'Номер перемены',
            'number_peremena2' => 'Номер перемены',
            'types_pit' => 'Вид организованного питания',
            'otkaz_home' => 'домашнего обучения',
            'otkaz_sahar' => 'сахарного диабета',
            'otkaz_cialic' => 'целиакии',
            'otkaz_allergy' => 'пищевой аллергии',
            'otkaz_inoe' => 'иные причины',
            'created_at' => 'Дата внесения данных',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            $me = User::findOne(Yii::$app->user->id);
            if (!empty($me))
            {
                $this->organization_id = $me->organization_id;
                $this->user_id = $me->id;
                return true;
            }
        }
        return false;
    }
    public function types_pit($data)
    {
        if ($data == 1)
        {
            return 'Завтрак';
        }
        elseif ($data ==2)
        {
            return 'Обед';
        }
        elseif ($data ==4)
        {
            return 'Затрак, 2й завтрак, обед, полдник, ужин, 2й ужин';
        }
        else{
            return 'Завтрак и обед';
        }
    }


    public function getOtkaz($student_id)
    {
        $student = Students::findOne($student_id);
        //0-отказался/1-согл

        $otkaz_pitanie = $student->otkaz_pitaniya;
        if($otkaz_pitanie == 1){
            return '-';
        }
        else
        {
            if ($student->prichina_otkaza == 1)
            {
                return 'По причине болезни';
            }
            elseif ($student->prichina_otkaza == 2)
            {
                return 'По причине домашнего обучения';
            }
            elseif ($student->prichina_otkaza == 3)
            {
                return 'По иным причинам';
            }
            else
            {
                return '-';
            }
        }
    }

    public function diseasesClass($class_id)
    {
        $student_mas = [];
        $shapka_mas = [];
        $diseases_new = DiseasesNew:: find()->where(['!=', 'alias', 'bez_osoben'])->all();
        $field_mas = ArrayHelper::map($diseases_new, 'alias', 'name');

        $students = \common\models\Students::find()->where(['students_class_id' => $class_id])->all();

        foreach ($students as $student)
        {
            $student_diseases = [];

            //Болезни и аллергии. Собираем в один массив чтобы знать сколько болезней у ребенка
            foreach ($field_mas as $key => $field)
            {
                if ($student->$key == 1)
                {
                    //разблочить если нужен алиас вместо id и наоборот
                    //$student_diseases[$key] = $field;
                    $student_diseases[DiseasesNew:: find()->where(['alias' => $key])->one()->id] = $field;
                }
            }
            /**/
            if (count($student_diseases) > 1)
            {
                $kew_long = '';
                $name_long = '';
                $count_turn = 0;
                foreach ($student_diseases as $student_disease_key => $student_disease)
                {
                    $count_turn++;//количество оборотов
                    if ($count_turn > 1)
                    {//если это второй оборот
                        $kew_long .= '+' . $student_disease_key;
                        //если встречаются 2 раза слова Аллергия - 1 из них нужно удалить
                        if (preg_match('/Аллергия/', $name_long))
                        {
                            $name_long .= ' и ' . mb_substr($student_disease, mb_strpos($student_disease, ' '));
                        }
                        else
                        {
                            $name_long .= ' и ' . $student_disease;
                        }

                    }
                    elseif ($count_turn == 1 || $count_turn == count($student_diseases))
                    {
                        $kew_long .= $student_disease_key;
                        $name_long .= $student_disease;
                    }
                }
                //добавляем множественное название в бд
                if (!array_key_exists($kew_long, $shapka_mas))
                {
                    $shapka_mas[$kew_long] = $name_long;
                }
                $student_disease_itog_key = $kew_long;

            }

            if (count($student_diseases) == 1)
            {//тут понятно что будет один оборот но мне нужно получить ключ а я могу только из форича его взять
                foreach ($student_diseases as $student_disease_key => $student_disease)
                {
                    if (!array_key_exists($student_disease_key, $shapka_mas))
                    {
                        $shapka_mas[$student_disease_key] = $student_disease;
                    }
                    $student_disease_itog_key = $student_disease_key;
                }
                //print_r($student_disease_key.'-'.$student_disease.'<br>');
            }

            //записываем в общий массив чтобы потом вывести
            if (!empty($student_diseases)){
                $student_mas[$student_disease_itog_key] = $student_mas[$student_disease_itog_key] + 1;
            }else{
                //разблочить если нужен алиас вместо id и наоборот заблочить более нижнюю
                //$student_mas['bez_osoben'] = $student_mas['bez_osoben'] + 1;
                $student_mas[15] = $student_mas[15] + 1;
            }
        }

        $itog['aliases'] = $student_mas;
        $itog['shapka'] = $shapka_mas;
        if(!empty($student_mas[15])){
            $itog['shapka'][15] = 'Без особенностей';
        }
        return $itog;
    }



    public function nutritionClass($class_id)
    {
        $student_mas = [];
        $shapka_mas = [];
        $students = \common\models\Students::find()->where(['students_class_id' => $class_id])->all();

        foreach ($students as $student)
        {
            $student_nutritions_mas = [];
            $student_nutritions = StudentsNutrition::find()->where(['students_id' => $student->id])->all();
            //Находим все приемы пищи у ребенка и формируем массив
            foreach ($student_nutritions as $student_nutrition)
            {
                $student_nutritions_mas[$student_nutrition->nutrition_id] = NutritionInfo:: find()->where(['id' => $student_nutrition->nutrition_id])->one()->name;
            }
            //print_r($student_nutritions_mas);
            //print_r('<br>');
            /**/
            //Если более 1 приема пищи, формируем ключ и общее название
            if (count($student_nutritions_mas) > 1)
            {
                $kew_long = '';
                $name_long = '';
                $count_turn = 0;
                foreach ($student_nutritions_mas as $student_nutritions_key => $student_nutritions_m)
                {
                    $count_turn++;//количество оборотов
                    if ($count_turn > 1)
                    {//если это второй оборот
                        $kew_long .= '+' . $student_nutritions_key;
                        //если встречаются 2 раза слова Аллергия - 1 из них нужно удалить
                        if (preg_match('/Аллергия/', $name_long))
                        {
                            $name_long .= ' и ' . mb_substr($student_nutritions_m, mb_strpos($student_nutritions_m, ' '));
                        }
                        else
                        {
                            $name_long .= ' и ' . $student_nutritions_m;
                        }

                    }
                    elseif ($count_turn == 1 || $count_turn == count($student_nutritions_m))
                    {
                        $kew_long .= $student_nutritions_key;
                        $name_long .= $student_nutritions_m;
                    }
                }
                //добавляем множественное название в бд
                if (!array_key_exists($kew_long, $shapka_mas))
                {
                    $shapka_mas[$kew_long] = $name_long;
                }
                $student_disease_itog_key = $kew_long;

            }

            if (count($student_nutritions_mas) == 1)
            {//тут понятно что будет один оборот но мне нужно получить ключ а я могу только из форича его взять
                foreach ($student_nutritions_mas as $student_nutritions_key => $student_nutritions_m)
                {
                    if (!array_key_exists($student_nutritions_key, $shapka_mas))
                    {
                        $shapka_mas[$student_nutritions_key] = $student_nutritions_m;
                    }
                    $student_disease_itog_key = $student_nutritions_key;
                }
                //print_r($student_disease_key.'-'.$student_disease.'<br>');
            }

            //записываем в общий массив чтобы потом вывести
            if (!empty($student_nutritions_mas)){
                $student_mas[$student_disease_itog_key] = $student_mas[$student_disease_itog_key] + 1;
            }
        }

        $itog['aliases'] = $student_mas;
        $itog['shapka'] = $shapka_mas;
        return $itog;
    }


    public function diseasesOrganization($organization_id)
    {
        $student_mas = [];
        $shapka_mas = [];
        $diseases_new = DiseasesNew:: find()->where(['!=', 'alias', 'bez_osoben'])->all();
        $field_mas = ArrayHelper::map($diseases_new, 'alias', 'name');

        $students = \common\models\Students::find()->where(['organization_id' => $organization_id])->all();

        foreach ($students as $student)
        {
            $student_diseases = [];

            //Болезни и аллергии. Собираем в один массив чтобы знать сколько болезней у ребенка
            foreach ($field_mas as $key => $field)
            {
                if ($student->$key == 1)
                {
                    //разблочить если нужен алиас вместо id и наоборот
                    //$student_diseases[$key] = $field;
                    $student_diseases[DiseasesNew:: find()->where(['alias' => $key])->one()->id] = $field;
                }
            }
            /**/
            if (count($student_diseases) > 1)
            {
                $kew_long = '';
                $name_long = '';
                $count_turn = 0;
                foreach ($student_diseases as $student_disease_key => $student_disease)
                {
                    $count_turn++;//количество оборотов
                    if ($count_turn > 1)
                    {//если это второй оборот
                        $kew_long .= '+' . $student_disease_key;
                        //если встречаются 2 раза слова Аллергия - 1 из них нужно удалить
                        if (preg_match('/Аллергия/', $name_long))
                        {
                            $name_long .= ' и ' . mb_substr($student_disease, mb_strpos($student_disease, ' '));
                        }
                        else
                        {
                            $name_long .= ' и ' . $student_disease;
                        }

                    }
                    elseif ($count_turn == 1 || $count_turn == count($student_diseases))
                    {
                        $kew_long .= $student_disease_key;
                        $name_long .= $student_disease;
                    }
                }
                //добавляем множественное название в бд
                if (!array_key_exists($kew_long, $shapka_mas))
                {
                    $shapka_mas[$kew_long] = $name_long;
                }
                $student_disease_itog_key = $kew_long;

            }

            if (count($student_diseases) == 1)
            {//тут понятно что будет один оборот но мне нужно получить ключ а я могу только из форича его взять
                foreach ($student_diseases as $student_disease_key => $student_disease)
                {
                    if (!array_key_exists($student_disease_key, $shapka_mas))
                    {
                        $shapka_mas[$student_disease_key] = $student_disease;
                    }
                    $student_disease_itog_key = $student_disease_key;
                }
                //print_r($student_disease_key.'-'.$student_disease.'<br>');
            }

            //записываем в общий массив чтобы потом вывести
            if (!empty($student_diseases)){
                $student_mas[$student_disease_itog_key] = $student_mas[$student_disease_itog_key] + 1;
            }else{
                //разблочить если нужен алиас вместо id и наоборот заблочить более нижнюю
                //$student_mas['bez_osoben'] = $student_mas['bez_osoben'] + 1;
                $student_mas[15] = $student_mas[15] + 1;
            }
        }

        $itog['aliases'] = $student_mas;
        $itog['shapka'] = $shapka_mas;
        if(!empty($student_mas[15])){
            $itog['shapka'][15] = 'Без особенностей';
        }
        return $itog;
    }

    
}
