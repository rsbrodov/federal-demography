<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\widgets\MaskedInput;
use common\models\Organization;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Раздел заявок на регистрацию';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <br>
	<?php
	$sapi_type = php_sapi_name();
	if (substr($sapi_type, 0, 3) == 'cgi') {
		echo "Вы используете CGI PHP\n";
	} else {
		echo "Вы используете не CGI PHP\n";
	}		
	?>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			/*[
                'attribute' => 'FED',
                'value' => function($model){
                    return Organization::findOne($model->organization_id)->federal_district_id;
                },
            ],*/
            'name',
            [
                'attribute' => 'Роль в программе',
                'value' => function($model){
                    return $model->get_role($model->id);
                },
            ],
            'email:email',
            //'organization_id',

            [
                'attribute' => 'organization_id',
                'value' => function($model){
                    return $model->get_organization($model->organization_id);
                },
            ],

            /*[
                'attribute' => 'organization_id',
                'value' => function($model){
                    return $model->get_org_type($model->organization_id);
                },
            ],*/

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



            [
                'attribute' => 'Фед.округ/Регион',
                'value' => function($model){
                    $organization = Organization::findOne($model->organization_id);
                    return $organization->get_district($organization->federal_district_id).'/'.$organization->get_region($organization->region_id);
                },
            ],
            [
                'attribute' => 'Мун район',
                'value' => function($model){
                    $organization = Organization::findOne($model->organization_id);
                    return $organization->get_municipality($organization->municipality_id);
                },
            ],




            [
                'attribute' => 'application',
                'value' => function($model){
                    return $model->get_application($model->application);
                },
            ],
            //'status',
            [
                'attribute' => 'Дата заявки',
                'headerOptions' => ['class' => 'grid_table_th', 'style' => ['max-width' => '200px']],
                'contentOptions' => ['class' => ''],
                'value' => function($model){
                    return $model->get_date($model->created_at);
                },
            ],

            //'updated_at',
            //'verification_token',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update-request} {accept} {reject} {delete-request}',
                'contentOptions' => ['class' => 'action-column'],
                'headerOptions' => ['class' => 'grid_table_th', 'style' => ['max-width' => '100px']],
                'buttons' => [

                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('yii', 'Детально просмотреть заявку'),
                            'data-toggle'=>'tooltip',
                            'class'=>'btn btn-sm btn-success'
                        ]);
                    },
                    'update-request' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('yii', 'Редактировать'),
                            'data-toggle'=>'tooltip',
                            'class'=>'btn btn-sm btn-primary'
                        ]);
                    },
                    'accept' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, [
                            'title' => Yii::t('yii', 'Принять заявку и активировать пользователя'),
                            'data-toggle'=>'tooltip',
                            'class'=>'btn btn-sm btn-success'
                        ]);
                    },
                    'reject' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, [
                            'title' => Yii::t('yii', 'Отклонить заявку'),
                            'data-toggle'=>'tooltip',
                            'class'=>'btn btn-sm btn-danger'
                        ]);
                    },
                    'delete-request' => function ($url, $model, $key) {
                        if(Yii::$app->user->identity->id == $model->id) {
                            return null;
                        }
                        else{
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => Yii::t('yii', 'Удалить заявку навсегда'),
                                'data-toggle' => 'tooltip',
                                'class' => 'btn btn-sm btn-danger',
                                'data' => ['confirm' => 'Вы уверены что хотите удалить заявку?'],
                            ]);
                        }
                    },
                ],
            ]

        ],
    ]); ?>


</div>
