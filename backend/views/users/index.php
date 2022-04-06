<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Раздел пользователей';
$this->params['breadcrumbs'][] = $this->title;

$roles = array('' => 'Все ...');
$roles_bd = ArrayHelper::map(\common\models\AuthItem::find()->orderBy(['description'=> SORT_ASC])->all(), 'name', 'description');
$roles = ArrayHelper::merge($roles,$roles_bd);
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать пользователя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => ['class' => 'grid_table_tr'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
            'name',
            [
                'attribute' => 'Роль в программе',
                'value' => function($model){
                    return $model->get_role($model->id);
                },
                'filter' => $roles,
            ],
            'email:email',
            //'organization_id',

            [
                'attribute' => 'organization_id',
                'value' => function($model){
                    return $model->get_organization($model->organization_id);
                },
            ],

            [
                'attribute' => 'phone',
                'value' => function($model){
                    if(!empty($model->phone)) {

                        $c1_3 = substr($model['phone'], 0, 3);
                        $c2_3 = substr($model['phone'], 3, 3);
                        $c3_2 = substr($model['phone'], 6, 2);
                        $c4_2 = substr($model['phone'], 8, 2);

                        return '+7(' . $c1_3 . ')-' . $c2_3 . '-' . $c3_2 . '-' . $c4_2;
                    }
                    else{
                        return '';
                    }
                },
            ],
            'post',
            //'status',
            [
                'attribute' => 'Последний вход',
                'header' => 'Последний вход',
                'value' => function($model){
                    $auth = \common\models\UserAutorizationStatistic::find()->where(['user_id' => $model->id])->orderBy(['id' => SORT_DESC])->one();
                    if(!empty($auth)){
                        return date('d.m.Y H:i', $auth->time_auth);
                    }else{
                        return 'Очень давно или никогда';
                    }
                },
            ],
            [
                'attribute' => 'created_at',
                'header' => 'Дата регистрации',
                'value' => function($model){
                    return date('d.m.Y H:i', $model->created_at);
                },
            ],
            //'updated_at',
            //'verification_token',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{login} {view} {update} {delete}',
                'contentOptions' => ['class' => 'action-column'],
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
                        if(Yii::$app->user->identity->id == $model->id) {
                            return null;
                        }
                        else{
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => Yii::t('yii', 'Удалить'),
                                'data-toggle' => 'tooltip',
                                'class' => 'btn btn-sm btn-danger',
                                'data' => ['confirm' => 'Вы уверены что хотите удалить пользователя?'],
                            ]);
                        }
                    },
                ],
            ]

        ],
    ]); ?>


</div>
