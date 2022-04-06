<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CharactersStudy */

$this->title = 'Ввод характеристики обучающихся 2020-2021';
$this->params['breadcrumbs'][] = ['label' => 'Characters Studies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="characters-study-create">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
