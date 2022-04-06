<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CharactersStudy */

$this->title = 'Редактирование характеристики: ';
$this->params['breadcrumbs'][] = ['label' => 'Characters Studies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="characters-study-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
