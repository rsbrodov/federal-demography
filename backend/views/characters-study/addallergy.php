<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CharactersStudyAllergy */

$this->title = 'Данные по заболеваниям детей';
$this->params['breadcrumbs'][] = ['label' => 'Characters Study Allergies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$yes_no_items = [
    '0' => "Нет",
    '1' => "Есть",
];

$form_study_items = [
    '1' => "Очная",
    '2' => "Домашняя",
];
?>

<style>
    th, td {
        border: 1px solid black!important;
        color: black;

    }
    th {
        background-color: #ede8b9;
        font-size: 15px;
    }
    .form-group {
        margin-bottom: 0rem;
    }
</style>


<div class="characters-study-allergy-create">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <?if(!Yii::$app->user->can('teacher')){?>
    <?php $form = ActiveForm::begin(); ?>



    <table class="table_th0 table-hover" style="width: 1500px!important;">
        <thead>
        <tr>
            <th class="text-center align-middle" style="width: 80px" rowspan="2">Класс</th>
            <th class="text-center align-middle" style="width: 80px" rowspan="2">Буква/ цифра</th>
            <th class="text-center align-middle" style="width: 80px" rowspan="2">Фамилия(1я буква) имя ребенка</th>
            <th class="text-center align-middle" style="width: 80px" rowspan="2">Форма обучения</th>
            <th class="text-center align-middle" colspan="8">Аллергия</th>
            <th class="text-center align-middle" colspan="5">Иные заболевания</th>
        </tr>
        <tr>
            <th class="text-center align-middle" style="width: 80px">Коровье молоко</th>
            <th class="text-center align-middle" style="width: 80px">Яйцо</th>
            <th class="text-center align-middle" style="width: 80px">Пшеница</th>
            <th class="text-center align-middle" style="width: 80px">Рыба и морепродукты</th>
            <th class="text-center align-middle" style="width: 80px">Шоколад</th>
            <th class="text-center align-middle" style="width: 80px">Орехи</th>
            <th class="text-center align-middle" style="width: 80px">Цитрусовые</th>
            <th class="text-center align-middle" style="width: 80px">Мед</th>

            <th class="text-center align-middle" style="width: 80px">Сахарный диабет</th>
            <th class="text-center align-middle" style="width: 80px">Целиакия</th>
            <th class="text-center align-middle" style="width: 80px">ОВЗ</th>
            <th class="text-center align-middle" style="width: 80px">Фенилкетонурия</th>
            <th class="text-center align-middle" style="width: 80px">Муковисцидоз</th>
        </tr>
        </thead>
        <tbody>
                <tr style="max-height: 30px;">
                    <!--            <td class="text-center" style="width: 100px">--><?//= User::findOne($model->user_id)->name ?><!--</td>-->
                    <?$characters_study = \common\models\CharactersStudy::findOne($_GET['id'])?>
                    <?= $form->field($model, 'characters_study_id')->hiddenInput(['value' => $_GET['id']])->label(false)?>
                    <td class="text-center" style="width: 100px"><?if($characters_study->class_number == 13) echo 'Коррекц.'; else{ echo $characters_study->class_number;} ?></td>
                    <td class="text-center" style="width: 100px"><?=$characters_study->class_letter; ?></td>
                    <td class="text-center" style="width: 250px"><?= $form->field($model, 'name')->textInput(['autocomplete' => 'off'])->label(false) ?></td>
                    <td class="text-center" style="width: 250px"><?= $form->field($model, 'form_study')->dropDownList($form_study_items)->label(false) ?></td>
                    <td class="text-center" style="width: 100px"><?= $form->field($model, 'moloko')->dropDownList($yes_no_items)->label(false) ?></td>
                    <td class="text-center" style="width: 100px"><?= $form->field($model, 'yico')->dropDownList($yes_no_items)->label(false) ?></td>
                    <td class="text-center" style="width: 100px"><?= $form->field($model, 'pshenica')->dropDownList($yes_no_items)->label(false) ?></td>
                    <td class="text-center" style="width: 100px"><?= $form->field($model, 'fish')->dropDownList($yes_no_items)->label(false) ?></td>
                    <td class="text-center" style="width: 100px"><?= $form->field($model, 'shocolad')->dropDownList($yes_no_items)->label(false) ?></td>
                    <td class="text-center" style="width: 100px"><?= $form->field($model, 'orehi')->dropDownList($yes_no_items)->label(false) ?></td>
                    <td class="text-center" style="width: 100px"><?= $form->field($model, 'citrus')->dropDownList($yes_no_items)->label(false) ?></td>
                    <td class="text-center" style="width: 100px"><?= $form->field($model, 'med')->dropDownList($yes_no_items)->label(false) ?></td>

                    <td class="text-center" style="width: 100px"><?= $form->field($model, 'sahar')->dropDownList($yes_no_items)->label(false) ?></td>
                    <td class="text-center" style="width: 100px"><?= $form->field($model, 'cialic')->dropDownList($yes_no_items)->label(false) ?></td>
                    <td class="text-center" style="width: 100px"><?= $form->field($model, 'ovz')->dropDownList($yes_no_items)->label(false) ?></td>
                    <td class="text-center" style="width: 100px"><?= $form->field($model, 'fenilketon')->dropDownList($yes_no_items)->label(false) ?></td>
                    <td class="text-center" style="width: 100px"><?= $form->field($model, 'mukovis')->dropDownList($yes_no_items)->label(false) ?></td>

                </tr>
        </tbody>
    </table>

    <div class="form-group">
        <?= Html::submitButton('добавить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?}?>










    <br><br><br><br><br>
    <h1>Сохраненная информация</h1>
    <table class="table_th0 table-hover" style="width: 1300px!important;">
        <thead>
        <tr>
            <!--            <th class="text-center align-middle" rowspan="3" style="width: 170px">Кто вносил</th>-->
            <th class="text-center align-middle" style="width: 80px" rowspan="2">Класс</th>
            <th class="text-center align-middle" style="width: 80px" rowspan="2">Буква/ цифра</th>
            <th class="text-center align-middle" style="width: 80px" rowspan="2">Фамилия(1я буква) имя ребенка</th>
            <th class="text-center align-middle" style="width: 80px" rowspan="2">Форма обучения</th>
            <th class="text-center align-middle" colspan="8">Аллергия</th>
            <th class="text-center align-middle" colspan="5">Иные заболевания</th>
            <th rowspan="2" class="text-center align-middle" colspan="2" style="width: 80px">Удаление/Редактирование</th>
        </tr>
        <tr>
            <th class="text-center align-middle" style="width: 80px">Коровье молоко</th>
            <th class="text-center align-middle" style="min-width: 100px">Яйцо</th>
            <th class="text-center align-middle" style="width: 80px">Пшеница</th>
            <th class="text-center align-middle" style="width: 80px">Рыба и морепродукты</th>
            <th class="text-center align-middle" style="width: 80px">Шоколад</th>
            <th class="text-center align-middle" style="width: 80px">Орехи</th>
            <th class="text-center align-middle" style="width: 80px">Цитрусовые</th>
            <th class="text-center align-middle" style="width: 80px">Мед</th>

            <th class="text-center align-middle" style="width: 80px">Сахарный диабет</th>
            <th class="text-center align-middle" style="width: 80px">Целиакия</th>
            <th class="text-center align-middle" style="width: 80px">ОВЗ</th>
            <th class="text-center align-middle" style="width: 80px">Фенилкетонурия</th>
            <th class="text-center align-middle" style="width: 80px">Муковисцидоз</th>

        </tr>
        </thead>
        <tbody>
        <?if(!empty($characters)){?>
        <?foreach ($characters as $character){?>
        <tr style="max-height: 30px;">

            <?$characters_study = \common\models\CharactersStudy::findOne($_GET['id'])?>
            <?//= $form->field($model, 'characters_study_id')->hiddenInput(['value' => $_GET['id']])->label(false)?>
            <td class="text-center" style="width: 100px"><?=$characters_study->class_number; ?></td>
            <td class="text-center" style="width: 100px"><?=$characters_study->class_letter; ?></td>
            <td class="text-center" style="width: 250px"><?= $character->name; ?></td>
            <td class="text-center" style="min-width: 150px"><?= $character->form_study($character->form_study); ?></td>
            <td class="text-center" style="width: 100px"><?= ($character->moloko == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center" style="min-width: 100px"><?= ($character->yico == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center" style="width: 100px"><?= ($character->pshenica == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center" style="width: 100px"><?= ($character->fish == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center" style="width: 100px"><?= ($character->shocolad == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center" style="width: 100px"><?= ($character->orehi == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center" style="width: 100px"><?= ($character->citrus == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center" style="width: 100px"><?= ($character->med == 1) ? 'Есть' : 'Нет'; ?></td>

            <td class="text-center" style="width: 100px"><?= ($character->sahar == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center" style="width: 100px"><?= ($character->cialic == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center" style="width: 100px"><?= ($character->ovz == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center" style="width: 100px"><?= ($character->fenilketon == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center" style="width: 100px"><?= ($character->mukovis == 1) ? 'Есть' : 'Нет'; ?></td>
            <?if(!Yii::$app->user->can('teacher')){?>
            <td class="text-center align-middle"><?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['characters-study-allergy/delete?id='.$character->id], [
                    'title' => Yii::t('yii', 'Удалить'),
                    'data-toggle' => 'tooltip',
                    'class' => 'btn btn-sm btn-danger',
                    'data' => ['confirm' => 'Вы уверены что хотите удалить?']
                ]);?></td>

                <td class="text-center align-middle"><?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['characters-study-allergy/update?id='.$character->id], [
                        'title' => Yii::t('yii', 'Редактировать'),
                        'class' => 'btn btn-sm btn-primary',

                    ]);?></td>
            <?}?>

        </tr>
        <?}?>
        <?}else{?>
            <tr><td class="text-center" colspan="17" style="width: 100px">Нет данных</td></tr>
        <?}?>
        </tbody>
    </table>

</div>
