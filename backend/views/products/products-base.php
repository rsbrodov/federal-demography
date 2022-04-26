<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;
use common\models\ProductsCategory;
use common\models\ProductsSubcategory;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'База данных продуктов';
$this->params['breadcrumbs'][] = $this->title;

$p_cat = array('' => 'Все ...');
$p_cat_bd = ArrayHelper::map(ProductsCategory::find()->orderBy(['name'=> SORT_ASC])->all(), 'id', 'name');
$p_cat = ArrayHelper::merge($p_cat,$p_cat_bd);


$sub_categories = array('' => 'Все ...');
$sub_categories_bd = ArrayHelper::map(ProductsSubcategory::find()->orderBy(['name'=> SORT_ASC])->all(), 'id', 'name');
$sub_categories = ArrayHelper::merge($sub_categories,$sub_categories_bd);


?>
<div class="products-index">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    <div class="mt-5">
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
            ['class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['class' => 'grid_table_th'],
            ],
            [
                'attribute' => 'name',
                'value' => 'name',
                'headerOptions' => ['class' => 'grid_table_th'],
                'contentOptions' => ['class' => ''],
            ],
            [
                'attribute' => 'products_category_id',
                'label' => 'Категория продукта',
                'value' => function ($model) {
                    return $model->category->name;
                },
                'filter' => $p_cat,
                'headerOptions' => ['class' => 'grid_table_th'],
                'contentOptions' => ['class' => ''],
            ],
            [
                'attribute' => 'products_subcategory_id',
                'label' => 'Подкатегория продукта',
                'value' => function ($model) {
                    return $model->subcategory->name;
                },
                'filter' => $sub_categories,
                'headerOptions' => ['class' => 'grid_table_th'],
                'contentOptions' => ['class' => ''],
            ],
            //'sort',
            [
                'attribute' => 'water',
                'value' => 'water',
                'headerOptions' => ['class' => 'grid_table_th text-nowrap'],
                'contentOptions' => ['class' => 'text-center align-middle'],
            ],
            [
                'attribute' => 'protein',
                'value' => 'protein',
                'headerOptions' => ['class' => 'grid_table_th text-nowrap'],
                'contentOptions' => ['class' => 'text-center align-middle'],
            ],
            [
                'attribute' => 'fat',
                'value' => 'fat',
                'headerOptions' => ['class' => 'grid_table_th text-nowrap'],
                'contentOptions' => ['class' => 'text-center align-middle'],
            ],
            [
                'attribute' => 'carbohydrates_total',
                'value' => 'carbohydrates_total',
                'headerOptions' => ['class' => 'grid_table_th text-nowrap'],
                'contentOptions' => ['class' => 'text-center align-middle'],
            ],
            [
                'attribute' => 'energy_kkal',
                'value' => 'energy_kkal',
                'headerOptions' => ['class' => 'grid_table_th text-nowrap'],
                'contentOptions' => ['class' => 'text-center align-middle'],
            ],
            //'energy_kkal',
            //'energy_kdj',
            //'created_at',

            [
                'header' => 'Просмотр продукта',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'contentOptions' => ['class' => 'action-column'],
                'buttons' => [

                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"> из БД нормативов</span>', $url, [
                            'title' => Yii::t('yii', 'Просмотр'),
                            'data-toggle'=>'tooltip',
                            'class'=>'btn btn-sm main-button-see text-nowrap'
                        ]);
                    },
                ],
            ]

        ],
    ]); ?>
    </div>


</div>
