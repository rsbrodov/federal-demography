<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DishesProducts */

$this->title = 'Редактирование брутто/нетто продукта из блюда: ' . $model->get_products($model->products_id)->name;;
$this->params['breadcrumbs'][] = ['label' => 'Dishes Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
$id = Yii::$app->request->get()['id'];
?>


<div class="dishes-products-update">

    <h1><?= Html::encode($this->title) ?></h1><br>
    <?= Html::a('Вернуться обратно к списку продуктов блюда', ['dishes/addproduct?id='.$model->dishes_id], ['class' => 'profile-link']) ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
