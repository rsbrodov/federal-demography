<?php

use common\models\User;
use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$model = \common\models\StudentsClass::findOne($_GET['id']);
if($model->class_number>=21 && $model->class_number>=21){ $model->class_number = $model->class_number %10 .'(подготовительный)';}
$this->title = 'Список детей по '.$model->class_number.$model->class_letter.' классу';
$this->params['breadcrumbs'][] = $this->title;

$characters_study_model = new \common\models\CharactersStudy();
$student_mas = [];
$field_mas = [];
$shapka_mas = [];

$field_mas['dis_sahar'] = 'СД';$field_mas['dis_ovz'] = 'ОВЗ';$field_mas['dis_cialic'] = 'Целиакия';$field_mas['dis_fenilketon'] = 'Фенилкетонурия';
$field_mas['dis_mukovis'] = 'Муковисцидоз';

$field_mas['al_moloko'] = 'Аллергия на молоко';$field_mas['al_yico'] = 'Аллергия на яйцо';$field_mas['al_fish'] = 'Аллергия на рыбу';
$field_mas['al_orehi'] = 'Аллергия на орехи';$field_mas['al_chocolad'] = 'Аллергия на шоколад';$field_mas['al_citrus'] = 'Аллергия на цитрус';
$field_mas['al_med'] = 'Аллергия на мед';$field_mas['al_pshenica'] = 'Аллергия на пшеницу';$field_mas['al_arahis'] = 'Аллергия на арахис';
$field_mas['al_inoe'] = 'Иная аллергия';
if(!empty($students)){
foreach ($students as $student){
        $student_mas[$student->id]['f_name'] = $student->name;
        $student_mas[$student->id]['count'] = $student_mas[$student->id]['count'] + 1;
        $student_diseases = [];
        if ($student->form_study == 1){
            $student_mas[$student->id]['form_study'] = 'Очная';
        }
        if ($student->form_study == 2){
            $student_mas[$student->id]['form_study'] = 'Домашняя';
        }
        //Болезни и аллергии. Собираем в один массив чтобы знать сколько болезней у ребенка
        foreach ($field_mas as $key => $field){
            if ($student->$key == 1)
            {
                //строим шапку
                if (!array_key_exists($key, $shapka_mas)){
                    $shapka_mas[$key] = $field;
                }
                //массив болезней одного ученика
                $student_mas[$student->id][$key] = 'Есть';
            }

        }
        $students_nutrition = \common\models\StudentsNutrition::find()->where(['students_id' => $student->id])->all();
        foreach ($students_nutrition as $student_n){
            $student_mas[$student->id]['nutrition'][$student_n->nutrition_id] = $student_mas[$student->id]['nutrition'][$student_n->nutrition_id]+1;
            $student_mas[$student->id]['peremena'][$student_n->peremena] = $student_n->peremena;
        }
    }
?>
<?}?>
<style>
    th, td {
        border: 1px solid black!important;
        color: black;

    }
    th {
        background-color: #ede8b9;
        font-size: 15px;
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

    <h1 class="text-center mb-5"><?= Html::encode($this->title) ?></h1>
    <div class="block-items mt-5">
        <div class="div">
            <p><?= Html::a('<- Вернуться к списку классов', ['index-new'], ['class' => 'btn main-button-3']) ?></p>
            <p><?= Html::a('Добавить ученика в класс', ['create-student?id='.$_GET['id']], ['title' => Yii::t('yii', 'Добавить ученика в класс'), 'class' => 'btn btn-lg btn-warning',]);?></p>
        </div>


        <?if(!empty(\common\models\Students::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->all())){?>
            <div class="col-8 mb-5" style="border: 1px solid #b7b6ba">
                <div class="row pb-2">
                    <p class="text-center" style="margin: 0 auto; color:#78787a"><b>Инструкция по кнопкам</b></p>
                </div>
                <div class="row pt-2">

                    <div class="col-5">
                        <?= Html::a('Копировать ученика', ['characters-study/index-new'], [
                            'class'=>'btn btn-sm btn-secondary disabled'
                        ]). '<b> - Копировать ученика <small>(Пользуйтесь кнопкой копирования для ускорения работы. Можно создать карточку ребенка "Без особенностей", а после создать копии, изменив только имя ребенка)</small></b>
                            ';?>
                    </div>

                    <div class="col-4">
                        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['characters-study/index-new'], [
                            'class'=>'btn btn-sm main-button-edit disabled'
                        ]). '<b> - Редактировать информацию об ученике </b>';?>
                    </div>

                    <div class="col-3">
                        <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['characters-study/index-new'], [
                            'class'=>'btn btn-sm main-button-delete disabled'
                        ]). '<b> - Удалить ученика навсегда<small>(Если ребенок ушел из класса, необходимо удалить его данные из системы)</small></b>';?>
                    </div>
                </div>
            </div>
        <?}?>
    </div>

    <table class="table_th0 table-hover">
        <thead>
        <tr>
            <th class="text-center align-middle" rowspan="3" style="width: 70px">№</th>
                <th class="text-center align-middle" rowspan="3" colspan="3" style="max-width: 190px">Действия</th>
            <th class="text-center align-middle" rowspan="3" style="width: 70px">Класс</th>
            <th class="text-center align-middle" rowspan="3" style="width: 70px">Буква/ цифра</th>
            <th class="text-center align-middle" rowspan="3" style="width: 70px">Ф.Имя</th>
            <th class="text-center align-middle" rowspan="3" style="width: 70px">Форма обучения</th>
            <?if(!empty($shapka_mas)){?>
                <th class="align-middle" colspan="<?=count($shapka_mas);?>" style="width: 230px">Заболевания</th>
            <?}?>

            <th class="text-center align-middle" rowspan="3" style="width: 70px">Перемены, на которых орг-но питание</th>
            <th class="text-center align-middle" rowspan="3" style="width: 140px">Виды организованного питания</th>
            <th class="text-center align-middle" rowspan="3" style="width: 140px">Не питается</th>
        </tr>
        <tr>
            <?foreach($shapka_mas as $shapka){?>
                <th class="text-center align-middle"><?=$shapka?></th>
            <?}?>
        </tr>
        </thead>
        <tbody>
        <?$count = 0; foreach ($students as $student){ $count++;?>
            <tr>
                <td class="align-middle text-center" style="width: 50px"><b><?= $count ?></b></td>
                    <td class="text-center align-middle" style="max-width: 100px"><?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update-student?id='.$student->id], [
                            'title' => Yii::t('yii', 'Редактировать'),
                            'data-toggle'=>'tooltip',
                            'class'=>'btn btn-sm btn-primary'
                        ]);?></td>
                    <td class="text-center align-middle" style="max-width: 200px"><?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete-student?id='.$student->id], [
                            'title' => Yii::t('yii', 'Удалить'),
                            'data-toggle' => 'tooltip',
                            'class' => 'btn btn-sm btn-danger',
                            'data' => ['confirm' => 'Вы уверены что хотите удалить?']
                        ]);?></td>

                    <td class="text-center align-middle" style="max-width: 200px"><?= Html::a('Копировать ученика', ['copy-student?id='.$student->id], [
                            'title' => Yii::t('yii', 'Копировать ученика в класс'),
                            'class' => 'btn btn-sm btn-secondary',
                        ]);?></td>
                <?if($model->class_number>=21 && $model->class_number>=21){ $model->class_number = $model->class_number %10 .'(подготовительный)';}?>
                <td class="text-center align-middle" style="width: 100px"><? if($model->class_number == 13) echo 'Коррекц.'; else{ echo $model->class_number;}?></td>
                <td class="text-center align-middle" style="width: 100px"><?= $model->class_letter ?></td>
                <td class="align-middle" style="width: 100px"><?= $student->name ?></td>
                <td class="text-center align-middle <?if($student_mas[$student->id]['form_study'] == 'Домашняя') echo 'table-danger';?>" style="width: 100px"><?= $student_mas[$student->id]['form_study'];?></td>
                <?foreach($shapka_mas as $key => $shapka){?>
                    <td class="text-center align-middle <?if(empty($student_mas[$student->id][$key])) echo 'table-secondary';?> " style="width: 100px"><?=empty($student_mas[$student->id][$key]) ? '-' : $student_mas[$student->id][$key];?></td>
                <?}?>

                <td class="" style="width: 100px"><?
                    for($i=1;$i<17;$i++){$count_peremena = 1;
                        //контроль запятых находим и понимаем что запись последняя и запятую не ставим
                        if(!empty($student_mas[$student->id]['peremena'][$i]) && count($student_mas[$student->id]['peremena']) == $count_peremena){
                            echo \common\models\SmenaPeremena::findOne($student_mas[$student->id]['peremena'][$i])->name. ' ';
                            $count_peremena++;
                        }
                        elseif(!empty($student_mas[$student->id]['peremena'][$i]) && count($student_mas[$student->id]['peremena']) != $count_peremena){
                            echo \common\models\SmenaPeremena::findOne($student_mas[$student->id]['peremena'][$i])->name. ', ';
                            $count_peremena++;
                        }
                    }?>
                </td>
                <td class="" style="width: 100px"><?$count_nutrition = 1;
                    for($i=1;$i<7;$i++){
                        //контроль запятых находим и понимаем что запись последняя и запятую не ставим
                        if(!empty($student_mas[$student->id]['nutrition'][$i]) && count($student_mas[$student->id]['nutrition']) == $count_nutrition){
                            echo \common\models\NutritionInfo::findOne($i)->name.' ';
                            $count_nutrition++;
                        }
                        elseif(!empty($student_mas[$student->id]['nutrition'][$i]) && count($student_mas[$student->id]['nutrition']) != $count_nutrition){
                            echo \common\models\NutritionInfo::findOne($i)->name.', ';
                            $count_nutrition++;
                        }
                    }?>
                </td>
                <td class="text-center align-middle <? if(\common\models\Students::find()->where(['id' => $student->id, 'otkaz_pitaniya' => 0])->count() > 0) echo 'table-danger';?> " style="width: 100px; font-size: 13px;"><?=$characters_study_model->getOtkaz($student->id); ?></td>

            </tr>

        <?}?>
        <?if(empty($students)){?>
            <tr>
                <td class="text-center text-danger" colspan="18">Нет данных</td>
            </tr>
        <?}?>
        </tbody>
    </table>
    <br>
    <br>



</div>


