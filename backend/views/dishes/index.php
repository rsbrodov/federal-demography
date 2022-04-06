<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;
use common\models\RecipesCollection;
use common\models\DishesCategory;

use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Редактирование(Создание) блюд';
$this->params['breadcrumbs'][] = $this->title;

/*if(Yii::$app->user->can('admin'))
{
    $recipes = array('' => 'Все ...');
    $recipes_bd = ArrayHelper::map(RecipesCollection::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');
    $recipes = ArrayHelper::merge($recipes, $recipes_bd);
}
else{*/
    $recipes = array('' => 'Все ...');
    $recipes_bd = ArrayHelper::map(RecipesCollection::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->orderBy(['name' => SORT_ASC])->all(), 'id', 'name');
    $recipes = ArrayHelper::merge($recipes, $recipes_bd);
//}


$categories = array('' => 'Все ...');
$categories_bd = ArrayHelper::map(DishesCategory::find()->orderBy(['name'=> SORT_ASC])->all(), 'id', 'name');
$categories = ArrayHelper::merge($categories,$categories_bd);
?>
<div class="dishes-index">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    <?if(RecipesCollection::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->orderBy(['name' => SORT_ASC])->count() == 0 && (!(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition')))
        ){?>
    <p class="text-center" style="color: red"><b>У Вас не созданы сборник(и) и не добавлены в него блюда</b></p>
    <?}?>
    <?if(!(Yii::$app->user->can('rospotrebnadzor_camp'))){?>


        <div class="col-8 mb-5 mt-5" style="border: 1px solid #b7b6ba">
            <div class="row pb-2">
                <p class="text-center" style="margin: 0 auto; color:#78787a"><b>Инструкция по кнопкам</b></p>
            </div>
            <div class="row pt-2">

                <div class="col-3">
                    <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['#'], [
                        'class'=>'btn btn-sm btn-success'
                    ]). '<b> - Детальный просмотр </b>';?>
                </div>
                <div class="col-4">
                    <?= Html::a('<span style="color: white" class="glyphicon glyphicon-plus"></span>', ['#'], [
                        'class'=>'btn btn-sm btn-secondary btn-warning'
                    ]). '<b> - Добавить продукты в блюдо </b>';?>
                </div>


                <div class="col-5">
                    <?= Html::a('<span class="glyphicon glyphicon-duplicate"></span>', ['#'], [
                        'class'=>'btn btn-sm btn-secondary'
                    ]). '<b> - Сделать копию меню со всеми настройками(копия блюда появится в конце списка) </b>';?>
                </div>
            </div>
            <div class="row pt-2 pb-2">
            <div class="col-4">
                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['#'], [
                    'class'=>'btn btn-sm main-button-edit'
                ]). '<b> - Редактировать информацию о блюде </b>';?>
            </div>

                <div class="col-5">
                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['#'], [
                        'class'=>'btn btn-sm main-button-delete'
                    ]). '<b> - Удалить блюдо навсегда</b>';?>
                </div>
            </div>
        </div>

        <p class="text-center"><?= Html::a('Создать блюдо', ['create'], ['class' => 'btn btn-lg main-button-3']) ?></p>
    <?}?>
    <?= GridView::widget([
        'options' => [
            'class' => 'menus-table table-responsive'],
        'tableOptions' => [
            'class' => 'table table-bordered table-responsive'
        ],
        'dataProvider' => $dataProvider,
        'rowOptions' => ['class' => 'grid_table_tr'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['class' => 'grid_table_th'],
            ],
            //'id',
            [
                'attribute' => 'name',
                'value' => 'name',
                'headerOptions' => ['class' => 'grid_table_th'],
                'contentOptions' => ['class' => ''],
            ],
            [
                'attribute' => 'dishes_category_id',
                'value' => function($model){
                    return $model->get_category_dish($model->dishes_category_id);
                },
                'filter' => $categories,
                'headerOptions' => ['class' => 'grid_table_th text-nowrap'],
                'contentOptions' => ['class' => ''],
                //'visible' => Yii::$app->user->can('admin'),
            ],
            [
                'attribute' => 'recipes_collection_id',
                'value' => function($model){
                    return $model->get_recipes($model->recipes_collection_id)->name;
                },
                'filter' => $recipes,
                'headerOptions' => ['class' => 'grid_table_th text-nowrap'],
                'contentOptions' => ['class' => ''],
                //'visible' => Yii::$app->user->can('admin'),
            ],
            [
                'attribute' => 'Количество продуктов в блюде',
                'value' => function($model){
                    return $model->get_count_products($model->id);
                },
                'filter' => $recipes,
                'headerOptions' => ['class' => 'grid_table_th'],
                'contentOptions' => ['class' => 'text-center'],
                //'visible' => Yii::$app->user->can('admin'),
            ],
            [
                'attribute' => 'Сколько раз использовано в меню',
                'value' => function($model){
                    return $model->get_count_menus($model->id);
                },
                'filter' => $recipes,
                'headerOptions' => ['class' => 'grid_table_th'],
                'contentOptions' => ['class' => 'text-center'],
                //'visible' => Yii::$app->user->can('admin'),
            ],
            'description:ntext',
            //'culinary_processing_id',
            [
                'attribute' => 'yield',
                'value' => 'yield',
                'headerOptions' => ['class' => 'grid_table_th'],
                'contentOptions' => ['class' => 'text-center'],
            ],
			
			[
                'attribute' => 'number_of_dish',
                'value' => 'number_of_dish',
                'headerOptions' => ['class' => 'grid_table_th'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            //'appearance',
            //'consistency',
            //'color',
            //'taste',
            //'smell',
            //'techmup_number',
            [
                'attribute' => 'techmup_number',
                'value' => 'techmup_number',
                'headerOptions' => ['class' => 'grid_table_th'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            //'number_of_dish',
            //'created_at',
            [
                'header' => 'Настройки сборника',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {addproduct} {copy-dish} {copy-user-dish} {refresh-dish} {update} {delete} {techmap}',
                'contentOptions' => ['class' => 'action-column', 'style'=>['width' =>'150px']],
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        /*if(!(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition')))
                        {*/
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                'title' => Yii::t('yii', 'Просмотр'),
                                'data-toggle' => 'tooltip',
                                'class' => 'btn btn-sm btn-success mb-3'
                            ]);
                        //}
                    },
                    'update' => function ($url, $model, $key) {
                        /*if(!(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition')))
                        {*/
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                'title' => Yii::t('yii', 'Редактировать'),
                                'data-toggle' => 'tooltip',
                                'class' => 'btn btn-sm btn-primary'
                            ]);
                       // }
                    },
                    'addproduct' => function ($url, $model, $key) {
                        /*if(!(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition')))
                        {*/
                            return Html::a('<span style="color: white" class="glyphicon glyphicon-plus"></span>', $url, [
                                'title' => Yii::t('yii', 'Добавление продуктов в блюдо'),
                                'data-toggle' => 'tooltip',
                                'class' => 'btn btn-sm btn-warning mb-3'
                            ]);
                        //}
                    },
                    'refresh-dish' => function ($url, $model, $key) {
                        if(Yii::$app->user->can('admin'))
                        {
                        return Html::a('<span style="color: white" class="glyphicon glyphicon-refresh"></span>', $url, [
                            'title' => Yii::t('yii', 'Изменить значение выхода блюда пропорционально'),
                            'data-toggle'=>'tooltip',
                            'class'=>'btn btn-sm btn-secondary'
                        ]);
                        }
                    },
                    'copy-dish' => function ($url, $model, $key) {
                        if(Yii::$app->user->can('admin'))
                        {
                            return Html::a('<span style="color: white" class="glyphicon glyphicon-duplicate"></span>', $url, [
                                'title' => Yii::t('yii', 'Дублировать это блюдо в новый сборник'),
                                'data-toggle'=>'tooltip',
                                'class'=>'btn btn-sm btn-secondary mb-3'
                            ]);
                        }
                    },

                    'copy-user-dish' => function ($url, $model, $key) {
                        if(!Yii::$app->user->can('admin'))
                        {
                            return Html::a('<span style="color: white" class="glyphicon glyphicon-duplicate"></span>', $url, [
                                'title' => Yii::t('yii', 'Создать копию блюда'),
                                'data-toggle'=>'tooltip',
                                'class'=>'btn btn-sm btn-secondary mb-3'
                            ]);
                        }
                    },
                    'delete' => function ($url, $model, $key) {
                        //if(!(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition')))
                        //{
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => Yii::t('yii', 'Удалить'),
                                'data-toggle' => 'tooltip',
                                'class' => 'btn btn-sm btn-danger',
                                'data' => ['confirm' => 'Вы уверены что хотите удалить это блюдо?'],
                            ]);
                        //}

                    },



                    'techmap' => function ($url, $model, $key) {
                        return Html::button('<span class="glyphicon glyphicon-list-alt text-nowrap"> Техкарта</span>', [
                            'title' => Yii::t('yii', 'Просмотр'),
                            'data-toggle'=>'tooltip',
                            //'class'=>'btn btn-sm btn-success',
                            'data-dishes_id' => $model->id,
                            'class'=>'btn btn-sm main-button-see',
                            'onclick' => '
                          $.get("../menus-dishes/showtechmupadd?id=" + $(this).attr("data-dishes_id"), function(data){
                          $("#showTechmup .modal-body").empty();
                            $("#showTechmup .modal-body").append(data);
                            //console.log(data);
                            $("#showTechmup").modal("show");
                          });'
                        ]);
                    },
                ],
            ]

        ],
    ]); ?>


</div>

<!--МОДАЛЬНОЕ ОКНО ДЛЯ ТЕХКАРТ-->
<div id="showTechmup" class="modal fade">
    <div class="modal-dialog modal-lg" style="">
        <div class="modal-content">
            <div class="modal-header-p3">
                <h4 class="modal-title">Технологическая карта
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="row">

                </div>
            </div>
        </div>
    </div>
</div>
