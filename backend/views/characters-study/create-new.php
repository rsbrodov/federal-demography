<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AnketParentControl */

$this->params['breadcrumbs'][] = ['label' => 'Anket Parent Controls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="anket-parent-control-create">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form2', [
            'model' => $model,
        ]) ?>

</div>
