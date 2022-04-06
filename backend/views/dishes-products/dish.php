<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Редактирование продуктов блюда';
$this->params['breadcrumbs'][] = $this->title;
$id = Yii::$app->request->get()['id'];
?>
<?= Html::a('Вернуться обратно к списку продуктов', ['dishes/addproduct?id='.$id], ['class' => 'profile-link']) ?>
<div class="dishes-products-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'dishes_id',
            [
                'attribute' => 'dishes_id',
                'value' => function($model){
                    return $model->get_dishes($model->dishes_id);
                },
            ],
            [
                'attribute' => 'products_id',
                'value' => function($model){
                    return $model->get_products($model->products_id)->name;
                },
            ],
           // 'products_id',
            'net_weight',
            'gross_weight',
            //'created_at',

            [
                'header' => 'Редактировать/Удалить',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'headerOptions' => ['class' => 'grid_table_th'],
                'contentOptions' => ['class' => 'action-column text-center'],
                'buttons' => [


                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('yii', 'Редактировать'),
                            'data-toggle'=>'tooltip',
                            'class'=>'btn btn-sm main-button-edit'
                        ]);
                    },

                    'delete' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('yii', 'Удалить'),
                            'data-toggle' => 'tooltip',
                            'class' => 'btn btn-sm main-button-delete',
                            'data' => ['confirm' => 'Вы уверены что хотите удалить меню?'],
                        ]);
                    },
                ],
            ]
        ],
    ]); ?>


</div>
