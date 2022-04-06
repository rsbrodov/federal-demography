<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список образовательных организаций';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organization-index">

    <h1><?= Html::encode($this->title) ?></h1>

<!--    <p>-->
<!--        --><?//= Html::a('Create Organization', ['create'], ['class' => 'btn btn-success']) ?>
<!--    </p>-->


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => [
            'class' => 'menus-table table-responsive'],
        'tableOptions' => [
            'class' => 'table table-bordered table-responsive'
        ],
        'rowOptions' => ['class' => 'grid_table_tr'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'title',
                'value' => 'title',
                'headerOptions' => ['class' => 'grid_table_th'],
                'contentOptions' => ['class' => ''],
            ],
            [
                'attribute' => 'short_title',
                'value' => 'short_title',
                'headerOptions' => ['class' => 'grid_table_th'],
                'contentOptions' => ['class' => ''],
            ],
            [
                'attribute' => 'type_org',
                'value' => function($model){
                    return $model->get_type_org($model->type_org);
                },
                'filter' => $sub,
                'headerOptions' => ['class' => 'grid_table_th'],
                'contentOptions' => ['class' => ''],
            ],
            //'short_title',
            //'address',
            'phone',
            'email:email',
            [
                'attribute' => 'Федеральный округ/Регион',
                'value' => function($model){
                    return $model->get_district($model->federal_district_id).'/'.$model->get_region($model->region_id);
                },
            ],
            [
                'attribute' => 'municipality_id',
                'value' => function($model){
                    return $model->get_municipality($model->municipality_id);
                },
            ],

            //'municipality_id',
            //'inn',
            'created_at',

            //'organizator_food',
            //'medic_service_programm',

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => [],
                'template' => '{view} {update} {delete}</div>',
                'contentOptions' => ['class' => 'action-column', 'style' => 'width: 94px;'],
                'buttons' => [

                    'login' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-log-in"></span>', $url, [
                            'title' => 'login',
                            'data-toggle'=>'tooltip',
                            'class'=>'btn btn-sm btn-primary',
                        ]);
                    },

                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('yii', 'Просмотр'),
                            'data-toggle'=>'tooltip',
                            'class'=>'btn btn-sm btn-success'
                        ]);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('yii', 'Редактировать'),
                            'data-toggle'=>'tooltip',
                            'class'=>'btn btn-sm btn-primary'
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {

                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('yii', 'Удалить'),
                            'data-toggle' => 'tooltip',
                            'class' => 'btn btn-sm btn-danger',
                            'data' => ['confirm' => 'Вы уверены что хотите удалить пользователя?'],
                        ]);

                    },
                ],
            ]

        ],
    ]); ?>

</div>
