<?php

use common\models\User;
use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Характеристика обучающихся';
$this->params['breadcrumbs'][] = $this->title;
?>
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
</style>
<div class="characters-study-index">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <?if(!Yii::$app->user->can('teacher')){?>
    <p>
        <?= Html::a('Добавить класс в характеристику обучающихся', ['create'], ['class' => 'btn main-button-3']) ?>
    </p>
    <?}?>



    <table class="table_th0 table-hover" style="width: 100%!important;">
        <thead>
        <tr>
            <?if(!Yii::$app->user->can('teacher')){?>
            <th class="text-center align-middle" rowspan="3" colspan="2" style="width: 90px">Редактировать/Удалить</th>
            <?}?>
            <th class="text-center align-middle" rowspan="3" style="width: 70px">Класс</th>
            <th class="text-center align-middle" rowspan="3" style="width: 70px">Буква/ цифра</th>
            <th class="text-center align-middle" rowspan="3" style="width: 230px">Количество детей (всего)</th>
            <th class="text-center align-middle" colspan="8" style="width: 230px">Из них</th>
            <th class="text-center align-middle" rowspan="3" style="width: 70px">Смена обучения (первая/ вторая)</th>
            <th class="text-center align-middle" rowspan="3" style="width: 70px">Номер перемены, на которой организовано питание</th>
            <th class="text-center align-middle" rowspan="3" style="width: 70px">Номер перемены(если питаются 2й раз)</th>
            <th class="text-center align-middle" rowspan="3" style="width: 70px">Вид организованного питания (завтрак/обед или завтрак +обед)</th>
            <th class="text-center align-middle" rowspan="2" colspan="8" style="width: 270px">Количество детей, отказавшихся от организованного питания по причинам</th>

        </tr>
        <tr>

            <th class="text-center align-middle" rowspan="2">на домашнем обучении</th>
            <th class="text-center align-middle" rowspan="2">на очном обучении</th>
            <th class="text-center align-middle" colspan="6">В том числе</th>

        </tr>
        <tr>
            <th class="text-center align-middle">сахарным диабетом</th>
            <th class="text-center align-middle">целиакией</th>
            <th class="text-center align-middle">ОВЗ</th>
            <th class="text-center align-middle">фенилкетонурией</th>
            <th class="text-center align-middle">муковисцидозом</th>
            <th class="text-center align-middle">пищевой аллергией</th>
            <!--OTKAZ-->
            <th class="text-center align-middle">домашнего обучения</th>
            <th class="text-center align-middle">сахарного диабета</th>
            <th class="text-center align-middle">целиакии</th>
            <th class="text-center align-middle">ОВЗ</th>
            <th class="text-center align-middle">Фенилкетонурии</th>
            <th class="text-center align-middle">Муковисцидоза</th>
            <th class="text-center align-middle">пищевой аллергии</th>
            <th class="text-center align-middle">иные причины</th>
        </tr>
        </thead>
        <tbody>
        <?foreach ($models as $model){?>
        <tr style="max-height: 30px;">
            <?if(!Yii::$app->user->can('teacher')){?>
            <td class="text-center align-middle"><?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update?id='.$model->id], [
                    'title' => Yii::t('yii', 'Редактировать'),
                    'data-toggle'=>'tooltip',
                    'class'=>'btn btn-sm btn-primary'
                ]);?></td>
            <td class="text-center align-middle"><?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete?id='.$model->id], [
                    'title' => Yii::t('yii', 'Удалить'),
                    'data-toggle' => 'tooltip',
                    'class' => 'btn btn-sm btn-danger',
                    'data' => ['confirm' => 'Вы уверены что хотите удалить?']
                ]);?></td>
            <?}?>
            <td class="text-center" style="width: 100px"><? if($model->class_number == 13) echo 'Коррекц.'; else{ echo $model->class_number;}?></td>
            <td class="text-center" style="width: 100px"><?= $model->class_letter ?></td>
            <td class="text-center" style="width: 100px"><?= $model->count?></td>
            <td class="text-center" style="width: 100px"><?= $model->count_home ?></td>
            <td class="text-center" style="width: 100px"><?= $model->count_ochno ?></td>
            <td class="text-center" style="width: 100px"><?= $model->sahar ?></td>
            <td class="text-center" style="width: 100px"><?= $model->cialic ?></td>
            <td class="text-center" style="width: 100px"><?= $model->ovz ?></td>
            <td class="text-center" style="width: 100px"><?= $model->fenilketon ?></td>
            <td class="text-center" style="width: 100px"><?= $model->mukovis ?></td>
            <td class="text-center" style="width: 100px"><?= $model->allergy ?></td>
            <td class="text-center" style="width: 100px"><?= $model->smena ?></td>
            <td class="text-center" style="width: 100px"><?= $model->number_peremena ?></td>
            <td class="text-center" style="width: 100px"><?= $model->number_peremena2 ?></td>


            <td class="text-center" style="width: 100px"><?= $model->types_pit($model->types_pit) ?></td>
            <td class="text-center" style="width: 100px"><?= $model->otkaz_home ?></td>
            <td class="text-center" style="width: 100px"><?= $model->otkaz_sahar ?></td>
            <td class="text-center" style="width: 100px"><?= $model->otkaz_cialic ?></td>
            <td class="text-center" style="width: 100px"><?= $model->otkaz_ovz ?></td>
            <td class="text-center" style="width: 100px"><?= $model->otkaz_fenilketon ?></td>
            <td class="text-center" style="width: 100px"><?= $model->otkaz_mukovis ?></td>
            <td class="text-center" style="width: 100px"><?= $model->otkaz_allergy ?></td>
            <td class="text-center" style="width: 100px"><?= $model->otkaz_inoe ?></td>

        </tr>

        <?}?>
        <?if(empty($models)){?>
        <tr>
            <td class="text-center text-danger" colspan="18">Нет данных</td>
        </tr>
        <?}?>
        </tbody>
    </table>
    <br>
<br>
    <h1 class="text-center">Подробная информация по детям, которые имееют заболевания или находятся на домашнем обучении</h1>
    <div class="row">
    <div class="col-3">
    <table class="table_th0 table-hover" style="width: 350px!important;">
        <thead>
        <tr>
            <th class="text-center align-middle" style="width: 80px">Класс</th>
            <th class="text-center align-middle" style="width: 80px">Буква/ цифра</th>
            <th class="text-center align-middle" colspan="1" style="width: 90px">Внести данные</th>
        </tr>
    </thead>
    <tbody>
        <?foreach ($models as $model){?>
            <?//if($model->sahar > 0 || $model->cialic > 0 || $model->ovz > 0 || $model->fenilketon > 0 || $model->mukovis > 0|| $model->allergy > 0){ ?>
            <tr style="max-height: 30px;">
                <!--            <td class="text-center" style="width: 100px">--><?//= User::findOne($model->user_id)->name ?><!--</td>-->
                <td class="text-center" style="width: 100px"><? if($model->class_number == 13) echo 'Коррекц.'; else{ echo $model->class_number;}?></td>
                <td class="text-center" style="width: 100px"><?= $model->class_letter ?></td>
                <?if(!Yii::$app->user->can('teacher')){?>
                <td class="text-center align-middle" style="width: 230px"><?= Html::a('Внести информацию', ['addallergy?id='.$model->id], [
                        'title' => Yii::t('yii', 'Внести информацию'),
                        'data-toggle'=>'tooltip',
                        'class'=>'btn btn-sm btn-warning'
                    ]);?></td>
                <?}else{?>
                    <td class="text-center align-middle" style="width: 230px"><?= Html::a('Посмотреть информацию', ['addallergy?id='.$model->id], [
                            'title' => Yii::t('yii', 'Посмотреть информацию'),
                            'data-toggle'=>'tooltip',
                            'class'=>'btn btn-sm btn-warning'
                        ]);?></td>
                <?}?>
            </tr>
            <?//}?>
        <?}?>
    </tbody>
    </table>
    </div>


</div>
