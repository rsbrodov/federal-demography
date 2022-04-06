<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Настройки меню';
$this->params['breadcrumbs'][] = $this->title;
//print_r(Yii::$app->request->pathInfo);
?>
<div class="menus-index">
    <div class="row justify-content-center">
        <div class="col-auto">
            <h1 class="text-center mb-5"><?= Html::encode($this->title) ?></h1>
            <div class="row">
                <div class="col-3 mt-5">
                    <p>
                        <?= Html::a('Добавить новое меню', ['create'], ['class' => 'btn main-button-3 col-md-10']) ?>
                    </p>
<!--                    <p class="text-danger"><b>Создание меню не доступно до 11:00(мск) 03.03.22</b></p>-->
                </div>
                <? if(\common\models\Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->count() > 0){?>
                <div class="col-8 mb-5" style="border: 1px solid #b7b6ba">
                    <div class="row pb-2">
                        <p class="text-center" style="margin: 0 auto; color:#78787a"><b>Инструкция по кнопкам</b></p>
                    </div>
                    <div class="row pt-2">
                        <div class="col-3">
                            <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['menus/index'], [
                            'class'=>'btn btn-sm main-button-see'
                            ]). '<b> - Детальный просмотр </b>';?>
                        </div>
                        <div class="col-5">
                            <?= Html::a('<span class="glyphicon glyphicon-duplicate"></span>', ['menus/index'], [
                                'class'=>'btn btn-sm btn-secondary'
                            ]). '<b> - Сделать копию меню со всеми настройками </b>';?>
                        </div>
                        <div class="col-4">
                            <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['menus/index'], [
                                'class'=>'btn btn-sm main-button-edit'
                            ]). '<b> - Редактировать информацию о меню </b>';?>
                        </div>
                    </div>

                    <div class="row pt-2 pb-2">
                        <?if(!(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition'))){?>
                        <div class="col-3">
                            <?= Html::a('<span class="glyphicon glyphicon-upload"></span>', ['menus/index'], [
                                'class'=>'btn btn-sm btn-success'
                            ]). '<b> - Загрузить меню в архив</b>
                            ';?>
                        </div>
                        <?}?>
                        <div class="col-5">
                            <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['menus/index'], [
                                'class'=>'btn btn-sm main-button-delete'
                            ]). '<b> - Удалить меню навсегда</b>';?>
                        </div>
                    </div>
                </div>
                <?}?>
            </div>











            <?if(!(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition'))){?>
			<div class="row">
                <div class="col-4 mt-5">

                </div>
               <div class="col-4 mb-5" style="border: 1px solid #b7b6ba">
                        <div class="row pb-2">
                            <p class="text-center" style="margin: 0 auto; color:#78787a"><b>Справка меню</b></p>
                        </div>
                    <?$standart = \common\models\Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'feeders_characters_id' => 3])->count();
                    $sahar = \common\models\Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'feeders_characters_id' => 5])->count();
                    $ovz = \common\models\Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'feeders_characters_id' => 4])->count();
                    $cialic = \common\models\Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'feeders_characters_id' => 6])->count();
                    $allergy = \common\models\Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'feeders_characters_id' => 10])->count();

                    $characters_study = \common\models\Students::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->all();
                    $character_sahar = 0; $character_cialic = 0; $character_ovz = 0; $character_allergy = 0;
                    foreach ($characters_study as $c_study){
                        if($c_study->dis_sahar > 0){
                            $character_sahar++;
                        }
                        if($c_study->dis_cialic > 0){
                            $character_cialic++;
                        }
                        if($c_study->dis_ovz > 0){
                            $character_ovz++;
                        }
                        if($c_study->al_moloko > 0 || $c_study->al_yico > 0 || $c_study->al_fish > 0 || $c_study->al_chocolad > 0 || $c_study->al_orehi > 0 || $c_study->al_citrus > 0 || $c_study->al_med > 0 || $c_study->al_pshenica > 0 || $c_study->al_arahis > 0 || $c_study->al_inoe > 0){
                            $character_allergy++;
                        }
                    }
                    ?>
                    <?if($standart > 0){?>
                       <div class="row pt-2">
                           <div class="col-7"><b>  Меню(без особенностей) </b>
                           </div>
                           <div class="col-5 text-success"><b> - Создано </b>
                           </div>
                       </div>
                   <?}?>

                   <?if($standart == 0){?>
                       <div class="row pt-2">
                           <div class="col-7"><b> Меню(без особенностей) </b>
                           </div>
                           <div class="col-5 text-danger"><b> - Не создано </b>
                           </div>
                       </div>
                   <?}?>



                   <?if($sahar > 0 && $character_sahar > 0){?>
                       <div class="row pt-2">
                           <div class="col-7"><b>  Меню(Дети с сахарным диабетом) </b>
                           </div>
                           <div class="col-5 text-success"><b> - Создано </b>
                           </div>
                       </div>
                   <?}?>

                   <?if($sahar == 0 && $character_sahar > 0){?>
                       <div class="row pt-2">
                           <div class="col-7"><b> Меню(Дети с сахарным диабетом) </b>
                           </div>
                           <div class="col-5 text-danger"><b> - Не создано </b>
                           </div>
                       </div>
                   <?}?>




                   <?if($ovz > 0 && $character_ovz > 0){?>
                       <div class="row pt-2">
                           <div class="col-7"><b>  Меню(Дети с ОВЗ) </b>
                           </div>
                           <div class="col-5 text-success"><b> - Создано </b>
                           </div>
                       </div>
                   <?}?>

                   <?if($ovz == 0 && $character_ovz > 0){?>
                       <div class="row pt-2">
                           <div class="col-7"><b> Меню(Дети с ОВЗ) </b>
                           </div>
                           <div class="col-5 text-danger"><b> - Не создано </b>
                           </div>
                       </div>
                   <?}?>






                   <?if($cialic > 0 && $character_cialic > 0){?>
                       <div class="row pt-2">
                           <div class="col-7"><b>  Меню(Дети с целиакией) </b>
                           </div>
                           <div class="col-5 text-success"><b> - Создано </b>
                           </div>
                       </div>
                   <?}?>

                   <?if($cialic == 0 && $character_cialic > 0){?>
                       <div class="row pt-2">
                           <div class="col-7"><b> Меню(Дети с целиакией) </b>
                           </div>
                           <div class="col-5 text-danger"><b> - Не создано </b>
                           </div>
                       </div>
                   <?}?>





                   <?if($allergy > 0 && $character_allergy > 0){?>
                       <div class="row pt-2">
                           <div class="col-7"><b>  Меню(Дети с пищевой аллергией) </b>
                           </div>
                           <div class="col-5 text-success"><b> - Создано </b>
                           </div>
                       </div>
                   <?}?>

                   <?if($allergy == 0 && $character_allergy > 0){?>
                       <div class="row pt-2">
                           <div class="col-7"><b> Меню(Дети с пищевой аллергией) </b>
                           </div>
                           <div class="col-5 text-danger"><b> - Не создано </b>
                           </div>
                       </div>
                   <?}?>


                    </div>

                <div class="col-4">
                </div>
            </div>
            <?}?>



            <div class="">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
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
                        'id',
                        [
                            'attribute' => 'name',
                            'value' => 'name',
                            'headerOptions' => ['class' => 'grid_table_th', 'style' => ['width' => '500px']],
                            'contentOptions' => ['class' => ''],
                        ],
                        [
                            'attribute' => 'feeders_characters_id',
                            'value' => function ($model) {
                                return $model->get_characters($model->feeders_characters_id);
                            },
                            'headerOptions' => ['class' => 'grid_table_th'],
                            'contentOptions' => ['class' => ''],
                        ],
                        [
                            'attribute' => 'age_info_id',
                            'value' => function ($model) {
                                return $model->get_age($model->age_info_id);
                            },
                            'headerOptions' => ['class' => 'grid_table_th'],
                            'contentOptions' => ['class' => ''],
                        ],
                        /*[
                            'attribute' => 'Дни меню',
                            'value' => function ($model) {
                                return $model->get_days($model->id, 'name');
                            },
                            'headerOptions' => ['class' => 'grid_table_th', 'style' => ['width' => '300px']],
                            'contentOptions' => ['class' => ''],
                        ],*/
                        [
                            'attribute' => 'cycle',
                            'value' => 'cycle',
                            'headerOptions' => ['class' => 'grid_table_th', 'style' => ['width' => '150px']],
                            'contentOptions' => ['class' => ''],
                        ],
                        [
                            'attribute' => 'Количество дней',
                            'value' => function ($model) {
                                $m_days = \common\models\MenusDays::find()->where(['menu_id' => $model->id])->count();
                                $count = $m_days*$model->cycle;
                                return $count;
                            },
                            'label' => 'Количество дней',
                            'headerOptions' => ['class' => 'grid_table_th', 'style' => ['width' => '100px']],
                            'contentOptions' => ['class' => ''],
                        ],
                        [
                            'attribute' => 'Продолжительность',
                            'value' => function ($model) {
                                $now_date = date('d.m.Y');
                                $date_end = date('d.m.Y', $model->date_end);
                                if(strtotime($now_date) > strtotime($date_end)){
                                    return date('d.m.Y', $model->date_start).'-'.date('d.m.Y', $model->date_end).'<br><small class="text-center text-danger"><b>Истек срок действия меню!</b></small>';
                                }else{
                                    return date('d.m.Y', $model->date_start).'-'.date('d.m.Y', $model->date_end);
                                }

                            },
                            'label' => 'Продолжительность',
                            'format' => 'html',
                            'headerOptions' => ['class' => 'grid_table_th', 'style' => ['width' => '240px']],
                            'contentOptions' => ['class' => ''],
                        ],
                        [
                            'header' => 'Настройки меню',
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {copy} {update} {push-archive} {delete}',
                            'headerOptions' => ['class' => 'grid_table_th'],
                            'contentOptions' => ['class' => 'action-column text-center'],
                            'buttons' => [

                                'view' => function ($url, $model, $key) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                        'title' => Yii::t('yii', 'Просмотр'),
                                        'data-toggle' => 'tooltip',
                                        'class' => 'btn btn-sm main-button-see'
                                    ]);
                                },
                                'copy' => function ($url, $model, $key) {
                                    return Html::a('<span class="glyphicon glyphicon-duplicate"></span>', $url, [
                                        'title' => Yii::t('yii', 'Сделать копию меню'),
                                        'data-toggle' => 'tooltip',
                                        'class' => 'btn btn-sm btn-secondary'
                                    ]);
                                },
                                'update' => function ($url, $model, $key) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                        'title' => Yii::t('yii', 'Редактировать'),
                                        'data-toggle' => 'tooltip',
                                        'class' => 'btn btn-sm main-button-edit'
                                    ]);
                                },
                                'push-archive' => function ($url, $model, $key) {
                                if(!(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition')))
                                {
                                    return Html::a('<span class="glyphicon glyphicon-upload"></span>', $url, [
                                        'title' => Yii::t('yii', 'Загрузить меню в архив'),
                                        'data-toggle' => 'tooltip',
                                        'class' => 'btn btn-sm btn-success'
                                    ]);
                                }
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

        </div>
    </div>
</div>
