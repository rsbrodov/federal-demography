<?php

use common\models\User;
use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Характеристика обучающихся';
$this->params['breadcrumbs'][] = $this->title;


$student_mas = [];
$field_mas = [];
$shapka_mas = [];

$field_mas['dis_sahar'] = 'СД';$field_mas['dis_ovz'] = 'ОВЗ';$field_mas['dis_cialic'] = 'Целиакия';$field_mas['dis_fenilketon'] = 'Фенилкетонурия';
$field_mas['dis_mukovis'] = 'Муковисцидоз';

$field_mas['al_moloko'] = 'Аллергия на молоко';$field_mas['al_yico'] = 'Аллергия на яйцо';$field_mas['al_fish'] = 'Аллергия на рыбу';
$field_mas['al_orehi'] = 'Аллергия на орехи';$field_mas['al_chocolad'] = 'Аллергия на шоколад';$field_mas['al_citrus'] = 'Аллергия на цитрус';
$field_mas['al_med'] = 'Аллергия на мед';$field_mas['al_pshenica'] = 'Аллергия на пшеницу';$field_mas['al_arahis'] = 'Аллергия на арахис';
$field_mas['al_inoe'] = 'Иная аллергия';

foreach ($models as $model){
    $students = \common\models\Students::find()->where(['students_class_id' => $model->id])->all();

    foreach ($students as $student){
        $student_mas[$model->id]['count'] = $student_mas[$model->id]['count'] + 1;
        $student_diseases = [];
        if ($student->form_study == 1){
            $student_mas[$model->id]['count_ochnoe'] = $student_mas[$model->id]['count_ochnoe'] + 1;
        }
        if ($student->form_study == 2){
            $student_mas[$model->id]['count_domashnee'] = $student_mas[$model->id]['count_domashnee'] + 1;
        }
        //Болезни и аллергии. Собираем в один массив чтобы знать сколько болезней у ребенка
        foreach ($field_mas as $key => $field){
            if ($student->$key == 1){
                $student_diseases[$key] = $field;
            }
        }
            /**/
        if (count($student_diseases) > 1){
            $kew_long = '';
            $name_long = '';
            $count_turn = 0;
            foreach ($student_diseases as $student_disease_key => $student_disease){
                $count_turn++;//количество оборотов
                if ($count_turn > 1){//если это второй оборот
                    $kew_long .= '+' . $student_disease_key;
                    //если встречаются 2 раза слова Аллергия - 1 из них нужно удалить
                    if (preg_match('/Аллергия/', $name_long)) {
                        $name_long .= ' и ' . mb_substr($student_disease, mb_strpos($student_disease, ' '));
                    }else{
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
                //print_r($name_long.'<br>');
            }
            $student_disease_itog_key = $kew_long;
            //print_r($kew_long.'-'.$name_long.'<br>');

        }

        if (count($student_diseases) == 1)
        {//тут понятно что будет один оборот но мне нужно получить ключ а я могу только из форича его взять
            foreach ($student_diseases as $student_disease_key => $student_disease){
                if (!array_key_exists($student_disease_key, $shapka_mas)){
                    $shapka_mas[$student_disease_key] = $student_disease;
                }
                $student_disease_itog_key = $student_disease_key;
            }
            //print_r($student_disease_key.'-'.$student_disease.'<br>');
        }


        //записываем в общий массив чтобы потом вывести
        if(!empty($student_diseases)){
            $student_mas[$model->id][$student_disease_itog_key] = $student_mas[$model->id][$student_disease_itog_key]+1;
        }
        //print_r($student_diseases);

        $students_nutrition = \common\models\StudentsNutrition::find()->where(['students_id' => $student->id])->all();
        foreach ($students_nutrition as $student_n){
            $student_mas[$model->id]['nutrition'][$student_n->nutrition_id] = $student_mas[$model->id]['nutrition'][$student_n->nutrition_id]+1;
            $student_mas[$model->id]['peremena'][$student_n->peremena] = $student_n->peremena;
        }

    }
}
//print_r($student_mas);
?>
<style>
    th, td {
        border: 1px solid black!important;
        color: black;

    }
    th {
        background-color: #ede8b9;
        font-size: 14px;
        font-weight: bold;
    }
    .form-group {
        margin-bottom: 0rem;
    }
    .block-items{
        display: flex;
        justify-content: space-around;
        align-items: center;
    }
</style>
<div class="characters-study-index">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <div class="block-items mt-5">
        <p><?= Html::a('Добавить новый класс', ['create-new'], ['class' => 'btn main-button-3 btn-lg']) ?></p>

        <?if(!empty(\common\models\StudentsClass::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->all())){?>
        <div class="col-8 mb-5" style="border: 1px solid #b7b6ba">
            <div class="row pb-2">
                <p class="text-center" style="margin: 0 auto; color:#78787a"><b>Инструкция по кнопкам</b></p>
            </div>
            <div class="row pt-2">
                <div class="col-3">
                    <?= Html::a('Список детей', ['characters-study/index-new'], [
                        'class'=>'btn btn-sm main-button-see'
                    ]). '<b> - Просмотр списка детей по классу<small>(Вы можете просмотреть списочный состав класса по детям с их характеристиками и питанием)</small> </b>';?>
                </div>

                <div class="col-3">
                    <?= Html::a('Добавить ученика в класс', ['characters-study/index-new'], [
                        'class'=>'btn btn-sm btn-success'
                    ]). '<b> - Добавить ученика в класс<small>(Необходимо внести в систему всех детей, которые есть в классе. Пустых классов быть не должно!)</small></b>
                            ';?>
                </div>

                <div class="col-3">
                    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['characters-study/index-new'], [
                        'class'=>'btn btn-sm main-button-edit'
                    ]). '<b> - Редактировать информацию о классе<small>(Можно сменить ответственного по классу, а также смену обучения)</small> </b>';?>
                </div>

                <div class="col-3">
                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['characters-study/index-new'], [
                        'class'=>'btn btn-sm main-button-delete'
                    ]). '<b> - Удалить класс навсегда</b>';?>
                </div>
            </div>
        </div>
        <?}?>
    </div>
    <p class="text-center text-danger" style="font-size: 20px;"><b>Необходимо внести учащихся с 1-4 класс.</b></p>





    <?if(!empty(\common\models\StudentsClass::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->all())){?>
    <table class="table-hover" style="max-width: 1500px">
        <thead>
        <tr>
            <th class="text-center align-middle" rowspan="3" style="width: 70px">№</th>
            <?if(!Yii::$app->user->can('teacher')){?>
                <th class="text-center align-middle" rowspan="3" colspan="4" style="width: 90px">Действия</th>
            <?}else{?>
                <th class="text-center align-middle" rowspan="3" colspan="3" style="width: 90px">Действия</th>
            <?}?>
            <th class="text-center align-middle" rowspan="3" style="max-width: 50px">Класс</th>
            <th class="text-center align-middle" rowspan="3" style="width: 50px">Буква/ цифра</th>
            <th class="text-center align-middle" rowspan="3" style="width: 50px">Смена обучения</th>
            <th class="text-center align-middle" rowspan="3" style="width: 70px">Количество детей</th>
            <th class="align-middle" colspan="<?=count($shapka_mas)+2 ;?>" style="width: 230px">Из них</th>

            <th class="text-center align-middle" rowspan="3" style="width: 190px">Перемены, на которых организ. питание</th>
            <th class="text-center align-middle" rowspan="3" style="min-width: 190px">Виды организованного питания</th>
            <th class="text-center align-middle" rowspan="3" style="width: 140px">Не питаются</th>

        </tr>
        <tr>
            <th class="text-center align-middle" rowspan="2">на очном обучении</th>
            <th class="text-center align-middle" rowspan="2">на домашнем обучении</th>
            <?if(!empty($shapka_mas)){?>
                <th class="text-center align-middle" colspan="<?=count($shapka_mas);?>">В том числе</th>
            <?}?>

        </tr>
        <tr>
            <?foreach($shapka_mas as $shapka){?>
                <th class="text-center align-middle" style="width: 70px"><?=$shapka?></th>
            <?}?>
        </tr>
        </thead>
        <tbody>
        <?$itog_mas = []; $count = 0; foreach ($models as $model){ $count++;?>
        <tr style="max-height: 30px;">
            <td class="align-middle text-center" style="width: 50px"><b><?= $count ?></b></td>
            <td class="text-center align-middle" style="max-width: 200px"><?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update-new?id='.$model->id], [
                    'title' => Yii::t('yii', 'Редактировать'),
                    'data-toggle'=>'tooltip',
                    'class'=>'btn btn-sm btn-primary'
                ]);?></td>
            <?if(!Yii::$app->user->can('teacher')){?>
                <td class="text-center align-middle" style="max-width: 200px"><?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete-new?id='.$model->id], [
                        'title' => Yii::t('yii', 'Удалить'),
                        'data-toggle' => 'tooltip',
                        'class' => 'btn btn-sm btn-danger',
                        'data' => ['confirm' => 'Вы уверены что хотите удалить?']
                 ]);?></td>
            <?}?>
            <td class="text-center align-middle" style="max-width: 200px"><?= Html::a('Добавить ученика', ['create-student?id='.$model->id], [
                    'title' => Yii::t('yii', 'Добавить ученика в класс'),
                    'class' => 'btn btn-sm btn-success',
                ]);?></td>

            <td class="text-center align-middle" style="max-width: 200px"><?= Html::a('Список детей', ['students-list?id='.$model->id], [
                    'title' => Yii::t('yii', 'Список детей'),
                    'class' => 'btn btn-sm btn-warning',
                ]);?></td>
            <?if($model->class_number>=21 && $model->class_number>=21){ $model->class_number = $model->class_number %10 .'(подготовительный)';}?>
            <td class="text-center align-middle" style="width: 100px"><? if($model->class_number == 13) echo 'Коррекц.'; else{ echo $model->class_number;}?></td>
            <td class="text-center align-middle" style="width: 100px"><?= $model->class_letter ?></td>
            <td class="text-center align-middle" style="width: 100px"><?= $model->smena ?></td>
            <td class="text-center align-middle" style="width: 100px; <?if(empty($student_mas[$model->id]['count'])){ echo 'color:red;';}?>"><b><?= empty($student_mas[$model->id]['count']) ? 0 : $student_mas[$model->id]['count']; $itog_mas['count'] = $itog_mas['count'] +$student_mas[$model->id]['count'];?></b></td>
            <td class="text-center align-middle" style="width: 100px"><?= empty($student_mas[$model->id]['count_ochnoe']) ? 0 : $student_mas[$model->id]['count_ochnoe']; $itog_mas['count_ochnoe'] = $itog_mas['count_ochnoe'] +$student_mas[$model->id]['count_ochnoe'];?></td>
            <td class="text-center align-middle" style="width: 100px"><?= empty($student_mas[$model->id]['count_domashnee']) ? 0 : $student_mas[$model->id]['count_domashnee']; $itog_mas['count_domashnee'] = $itog_mas['count_domashnee'] +$student_mas[$model->id]['count_domashnee'];?></td>
            <?foreach($shapka_mas as $key => $shapka){?>
                <td class="text-center align-middle <?if(empty($student_mas[$model->id][$key])) echo 'table-secondary';?>" style="width: 100px"><?= empty($student_mas[$model->id][$key]) ? '-' : $student_mas[$model->id][$key]; $itog_mas[$key] = $itog_mas[$key] +$student_mas[$model->id][$key];?></td>
            <?}?>

            <td class="" style="width: 100px"><? $count_peremena = 1;
                for($i=1;$i<17;$i++){
                    //контроль запятых находим и понимаем что запись последняя и запятую не ставим
                    if(!empty($student_mas[$model->id]['peremena'][$i]) && count($student_mas[$model->id]['peremena']) == $count_peremena){
                        echo \common\models\SmenaPeremena::findOne($student_mas[$model->id]['peremena'][$i])->name. ' ';
                        $count_peremena++;
                    }
                    elseif(!empty($student_mas[$model->id]['peremena'][$i]) && count($student_mas[$model->id]['peremena']) != $count_peremena){
                        echo \common\models\SmenaPeremena::findOne($student_mas[$model->id]['peremena'][$i])->name. ', ';
                        $count_peremena++;
                    }
                }?>
            </td>
            <td class="" style="width: 100px"><? $count_nutrition = 1;
                for($i=1;$i<7;$i++){

                    //контроль запятых находим и понимаем что запись последняя и запятую не ставим
                    if(!empty($student_mas[$model->id]['nutrition'][$i]) && count($student_mas[$model->id]['nutrition']) == $count_nutrition){
                        echo \common\models\NutritionInfo::findOne($i)->name . ' - ' . $student_mas[$model->id]['nutrition'][$i].'  чел. ';
                        $itog_mas['nutrition'][$i] = $itog_mas['nutrition'][$i]+$student_mas[$model->id]['nutrition'][$i];
                        $count_nutrition++;
                    }
                    elseif(!empty($student_mas[$model->id]['nutrition'][$i]) && count($student_mas[$model->id]['nutrition']) != $count_nutrition){
                        echo \common\models\NutritionInfo::findOne($i)->name . ' - ' . $student_mas[$model->id]['nutrition'][$i].' чел., <br>';
                        $itog_mas['nutrition'][$i] = $itog_mas['nutrition'][$i]+$student_mas[$model->id]['nutrition'][$i];
                        $count_nutrition++;
                    }
                }?>
            </td>
            <td class="text-center" style="width: 100px; <?if(\common\models\Students::find()->where(['students_class_id' => $model->id, 'otkaz_pitaniya' => 0])->count() > 0){ echo 'color:blue; font-weight:bold;';}?>"><?$otkaz = \common\models\Students::find()->where(['students_class_id' => $model->id, 'otkaz_pitaniya' => 0])->count(); echo $otkaz; $itog_mas['otkaz'] = $itog_mas['otkaz'] +$otkaz;?></td>
            <?if(empty(\common\models\Students::find()->where(['students_class_id' => $model->id])->count())){?>
                <td class="text-center text-danger" style="font-size: 13px; min-width: 200px; border-right:0!important;border-top:0!important;border-bottom:0!important;"><b><-Добавьте детей в класс</b></td>
            <?}?>
        </tr>

        <?}?>
        <?if(empty($models)){?>
        <tr>
            <td class="text-center text-danger" colspan="18">Нет данных</td>
        </tr>
        <?}?>
        <?if(!empty($models)){?>
            <tr class="table-primary font-weight-bold">
                <td class="text-center" colspan="8">Итоговая информация по классам</td>
                <td class="text-center" colspan="1"><?=$itog_mas['count']?></td>
                <td class="text-center" colspan="1"><?=$itog_mas['count_ochnoe']?></td>
                <td class="text-center" colspan="1"><?=$itog_mas['count_domashnee']?></td>
                <?foreach($shapka_mas as $key => $shapka){?>
                    <td class="text-center" colspan="1"><?=$itog_mas[$key]?></td>
                <?}?>
                <td class="text-center" colspan="1"></td>
                <td class="" style="width: 100px; font-size: 12px;"><? $count_nutrition = 1;
                    for($i=1;$i<7;$i++){
                        //контроль запятых находим и понимаем что запись последняя и запятую не ставим
                        if(!empty($itog_mas['nutrition'][$i]) && count($itog_mas['nutrition']) == $count_nutrition){
                            echo \common\models\NutritionInfo::findOne($i)->name . ' - ' . $itog_mas['nutrition'][$i].'  чел. ';
                            $count_nutrition++;
                        }
                        elseif(!empty($itog_mas['nutrition'][$i]) && count($itog_mas['nutrition']) != $count_nutrition){
                            echo \common\models\NutritionInfo::findOne($i)->name . ' - ' . $itog_mas['nutrition'][$i].' чел., <br>';
                            $count_nutrition++;
                        }
                    }?>
                </td>
                <td class="text-center" colspan="1"><?=$itog_mas['otkaz']?></td>
            </tr>

        <?}?>
        </tbody>
    </table>
    <?}?>
    <br>
<br>



</div>
