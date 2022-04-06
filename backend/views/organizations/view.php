<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Organization */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Organizations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$organizations_id = $_GET['id'];
$users = \common\models\User::find()->where(['organization_id' => $organizations_id])->all();
 ?>
<div class="organization-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <table class="table_th0 table-hover" style="width: 1100px!important;">
        <thead>
        <tr>
            <!--            <th class="text-center align-middle" rowspan="3" style="width: 170px">Кто вносил</th>-->
            <th class="text-center align-middle" style="width: 80px" >ФИО</th>
            <th class="text-center align-middle" style="width: 80px" >Роль в ПО</th>
            <th class="text-center align-middle" style="width: 80px">Вход</th>
        </tr>
        <tbody>
    <?foreach($users as $user){?>
        <tr>
            <td><?=$user->name?></td>
            <td><?=\common\models\AuthItem::find()->where(['name' => \common\models\AuthAssignment::find()->where(['user_id' => $user->id])->one()->item_name])->one()->description?></td>

            <td class="text-center align-middle"><?= Html::a('<span class="glyphicon glyphicon-log-in"></span>', ['users/login?id='.$user->id], [
                    'title' => Yii::t('yii', 'Вход'),
                    'class' => 'btn btn-sm btn-primary',
                ]);?></td>
        </tr>
    <?}?>
        </tbody>
    </table>


</div>
