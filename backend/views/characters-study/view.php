<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\CharactersStudy */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Characters Studies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="characters-study-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            'organization_id',
            'class_number',
            'class_letter',
            'count',
            'count_home',
            'count_ochno',
            'sahar',
            'cialic',
            'allergy',
            'smena',
            'number_peremena',
            'types_pit',
            'otkaz_home',
            'otkaz_sahar',
            'otkaz_cialic',
            'otkaz_allergy',
            'otkaz_inoe',
            'created_at',
        ],
    ]) ?>

</div>
