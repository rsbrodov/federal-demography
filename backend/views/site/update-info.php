<?php

/* @var $this yii\web\View */

use common\models\AnketParentControl;
use common\models\MenusSend;
use common\models\NutritionApplications;
use common\models\Organization;
use common\models\User;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;

$this->title = 'Информация по обновлениям в программе';

?>
<div class="site-index">
    <h1 class="text-center mb-5"><?= Html::encode($this->title) ?></h1>
    <div class="container">
        <?if(Yii::$app->user->can('admin') || Yii::$app->user->can('food_director') || Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr') || Yii::$app->user->can('minobr')){?>
        <p><b>1.Разработка меню для разных типов организаций(обновление от 04.02.2022)</b></p>
        <hr>
        <p>
            Теперь у операторов питания и органов управления образования появился функционал по разработке меню для разных типов организаций. В зависимости от типа организации подгружаются соответствующие ей нормативы.
            <br>Для этого в разделе <b>Организация питания(или контроль меню)->Настройка меню</b> при создании нового меню (или редактировании) нужно указать для какого типа организации предназначено меню.
        </p>
        <div class="img-blok">
            <img src="../images/up1.png" width="100%" height="auto" alt="">
        </div>
        <?}else{?>
            <p class="text-center"><b>Обновлений пока нет</b></p>
        <?}?>
    </div>



</div>

