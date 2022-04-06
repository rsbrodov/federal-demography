<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\bootstrap4\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        На странице произошла ошибка.
    </p>
    <p>
        Отчет об ошибке был отправлен и в ближайшее время она будет устранена. Спасибо.
    </p>

</div>
