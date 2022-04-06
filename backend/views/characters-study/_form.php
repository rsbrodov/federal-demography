<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CharactersStudy */
/* @var $form yii\widgets\ActiveForm */
$class_item = [
    1 => '1',
    2 => '2',
    3 => '3',
    4 => '4',
    5 => '5',
    6 => '6',
    7 => '7',
    8 => '8',
    9 => '9',
    10 => '10',
    11 => '11',
    12 => '12',
    13 => 'Коррекционный',

];

$smena_item = [
    1 => '1',
    2 => '2',
];

$peremena_item = [
    1 => '1',
    2 => '2',
    3 => '3',
    4 => '4',
    5 => '5',
    6 => '6',
    7 => '7',
    8 => '8',
    9 => '9',
    10 => '10',
    11 => '11',
];
$peremena_item2 = [
    '' => '',
    1 => '1',
    2 => '2',
    3 => '3',
    4 => '4',
    5 => '5',
    6 => '6',
    7 => '7',
    8 => '8',
    9 => '9',
    10 => '10',
];
$pit_item = [
    1 => 'Завтрак',
    2 => 'Обед',
    3 => 'Завтрак и обед',
    4 => 'Затрак, 2й завтрак, обед, полдник, ужин, 2й ужин',
];

$letter_item = [
    'А(1)' => 'А(1)',
    'Б(2)' => 'Б(2)',
    'В(3)' => 'В(3)',
    'Г(4)' => 'Г(4)',
    'Д(5)' => 'Д(5)',
    'Е(6)' => 'Е(6)',
    'Ж(7)' => 'Ж(7)',
    'З(8)' => 'З(8)',
    'И(9)' => 'И(9)',
    'К(10)' => 'К(10)',
    'Л(11)' => 'Л(11)',
    'М(12)' => 'М(12)',
    'Н(13)' => 'Н(13)',
    'О(14)' => 'О(14)',
    'П(15)' => 'П(15)',
    'Р(16)' => 'Р(16)',
    'С(17)' => 'С(17)',
    'Т(18)' => 'Т(18)',
    'У(19)' => 'У(19)',
    'Ф(20)' => 'Ф(20)',
    'Х(21)' => 'Х(21)',
    'Ц(22)' => 'Ц(22)',
    '(нет буквы)' => '(нет буквы)',
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




<div class="expenses-food-form mt-5" style="margin: 0 auto; font-size: 12px;">

    <?php $form = ActiveForm::begin(); ?>
    <table class="table_th0 table-hover" style="width: 2400px!important;">
        <thead>
        <tr>
            <th class="text-center align-middle" rowspan="3" style="width: 120px">Класс</th>
            <th class="text-center align-middle" rowspan="3" style="width: 120px">Буква/ цифра</th>
            <th class="text-center align-middle" rowspan="3" style="width: 170px">Количество детей (всего)</th>
            <th class="text-center align-middle" colspan="8" style="width: 230px">Из них</th>
            <th class="text-center align-middle" rowspan="3" style="width: 70px">Смена обучения (первая/ вторая)</th>
            <th class="text-center align-middle" rowspan="3" style="width: 70px">Номер перемены, на которой организовано питание</th>
            <th class="text-center align-middle" rowspan="3" style="width: 70px">Номер перемены(если питаются 2й раз)</th>
            <th class="text-center align-middle" rowspan="3" style="width: 70px">Вид организованного питания (завтрак/обед или завтрак +обед)</th>
            <th class="text-center align-middle" rowspan="2" colspan="8" style="width: 270px">Количество детей, отказавшихся от организованного питания по причинам</th>
        </tr>
        <tr>

            <th class="text-center align-middle" rowspan="2">на домашнем обучении</th>
            <th class="text-center align-middle" rowspan="2">на очном обучении</th>
            <th class="text-center align-middle" colspan="6">В том числе</th>

        </tr>
        <tr>
            <th class="text-center align-middle">сахарным диабетом</th>
            <th class="text-center align-middle">целиакией</th>
            <th class="text-center align-middle">ОВЗ</th>
            <th class="text-center align-middle">фенилкетонурией</th>
            <th class="text-center align-middle">муковисцидозом</th>
            <th class="text-center align-middle">пищевой аллергией</th>
            <!--OTKAZ-->
            <th class="text-center align-middle">домашнего обучения</th>
            <th class="text-center align-middle">сахарного диабета</th>
            <th class="text-center align-middle">целиакии</th>
            <th class="text-center align-middle">ОВЗ</th>
            <th class="text-center align-middle">Фенилкетонурии</th>
            <th class="text-center align-middle">Муковисцидоза</th>
            <th class="text-center align-middle">пищевой аллергии</th>
            <th class="text-center align-middle">иные причины</th>
        </tr>
        </thead>
        <tbody>
        <tr style="max-height: 30px;">
            <td class="text-center" style="width: 100px"><?= $form->field($model, 'class_number')->dropDownList($class_item)->label(false) ?></td>
            <td class="text-center" style="width: 100px"><?= $form->field($model, 'class_letter')->dropDownList($letter_item)->label(false) ?></td>
            <td class="text-center" style="width: 100px"><?= $form->field($model, 'count')->textInput()->label(false) ?></td>
            <td class="text-center" style="width: 100px"><?= $form->field($model, 'count_home')->textInput()->label(false) ?></td>
            <td class="text-center" style="width: 100px"><?= $form->field($model, 'count_ochno')->textInput()->label(false) ?></td>
            <td class="text-center" style="width: 100px"><?= $form->field($model, 'sahar')->textInput()->label(false) ?></td>
            <td class="text-center" style="width: 100px"><?= $form->field($model, 'cialic')->textInput()->label(false) ?></td>
            <td class="text-center" style="width: 70px"><?= $form->field($model, 'ovz')->textInput()->label(false) ?></td>
            <td class="text-center" style="width: 100px"><?= $form->field($model, 'fenilketon')->textInput()->label(false) ?></td>
            <td class="text-center" style="width: 100px"><?= $form->field($model, 'mukovis')->textInput()->label(false) ?></td>
            <td class="text-center" style="width: 100px"><?= $form->field($model, 'allergy')->textInput()->label(false) ?></td>
            <td class="text-center" style="width: 100px"><?= $form->field($model, 'smena')->dropDownList($smena_item)->label(false) ?></td>
            <td class="text-center" style="width: 100px"><?= $form->field($model, 'number_peremena')->dropDownList($peremena_item)->label(false) ?></td>
            <td class="text-center" style="width: 100px"><?= $form->field($model, 'number_peremena2')->dropDownList($peremena_item2)->label(false) ?></td>


            <td class="text-center" style="width: 100px"><?= $form->field($model, 'types_pit')->dropDownList($pit_item)->label(false) ?></td>
            <td class="text-center" style="width: 100px"><?= $form->field($model, 'otkaz_home')->textInput()->label(false) ?></td>
            <td class="text-center" style="width: 100px"><?= $form->field($model, 'otkaz_sahar')->textInput()->label(false) ?></td>
            <td class="text-center" style="width: 100px"><?= $form->field($model, 'otkaz_cialic')->textInput()->label(false) ?></td>
            <td class="text-center" style="width: 100px"><?= $form->field($model, 'otkaz_ovz')->textInput()->label(false) ?></td>
            <td class="text-center" style="width: 100px"><?= $form->field($model, 'otkaz_fenilketon')->textInput()->label(false) ?></td>
            <td class="text-center" style="width: 100px"><?= $form->field($model, 'otkaz_mukovis')->textInput()->label(false) ?></td>
            <td class="text-center" style="width: 100px"><?= $form->field($model, 'otkaz_allergy')->textInput()->label(false) ?></td>
            <td class="text-center" style="width: 100px"><?= $form->field($model, 'otkaz_inoe')->textInput()->label(false) ?></td>
        </tr>
        </tbody>
    </table>


    <div class="form-group text-center mt-3">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success main-button-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>








