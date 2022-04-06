<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CharactersStudyAllergy */

$this->title = 'Отчет по детям с патологиями, требующих индивидуального питания';
$this->params['breadcrumbs'][] = ['label' => 'Characters Study Allergies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$yes_no_items = [
    '0' => "Нет",
    '1' => "Есть",
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

<div class="text-center" style="margin-left: 25px;">
    <table class="table_th0 table-hover" style="width: 1200px!important;">
        <thead>
        <tr>
            <!--            <th class="text-center align-middle" rowspan="3" style="width: 170px">Кто вносил</th>-->
            <th class="text-center align-middle" style="width: 80px" rowspan="2">№</th>
            <th class="text-center align-middle" style="width: 80px" rowspan="2">Класс</th>
            <th class="text-center align-middle" style="width: 80px" rowspan="2">Буква/цифра</th>
            <th class="text-center align-middle" style="width: 80px" rowspan="2">Форма обучения</th>
            <th class="text-center align-middle" rowspan="3" style="width: 70px">Смена обучения (первая/ вторая)</th>
            <th class="text-center align-middle" rowspan="3" style="min-width: 100px">Перемена, на которой питается ребенок</th>
            <th class="text-center align-middle" style="min-width: 110px" rowspan="2">Фамилия(1я буква) имя ребенка</th>
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
        <?if(!empty($characters_study_mas)){?>
        <? $count = 0; foreach ($characters_study_mas as $character_m){ $count_cl = 0;?>
        <tr>
            <?$characters_study = \common\models\CharactersStudy::findOne($character_m)?>
            <?$characters_study_addalergy = \common\models\CharactersStudyAllergy::find()->where(['characters_study_id' => $character_m])->all()?>
            <?foreach ($characters_study_addalergy as $character){
                $count++; $count_cl++;?>
            <td class="text-center" style="width: 100px"><?=$count ?></td>
            <td class="text-center" style="width: 100px"><?=$characters_study->class_number; ?></td>
            <td class="text-center" style="width: 100px"><?=$characters_study->class_letter; ?></td>

            <?if($character->form_study == 1){?>
                 <td class="text-center" style="width: 100px">Очная</td>
            <? }elseif ($character->form_study == 2){?>
                <td class="text-center table-primary" style="width: 100px">Домашняя</td>
            <?} else{ ?><td class="text-center" style="width: 100px">Не указана</td>
            <?}?>
            <td class="text-center" style="width: 100px"><?= $characters_study->smena ?></td>
            <td class="text-center" style="width: 100px"><?= $characters_study->number_peremena ?></td>

            <td class="text-center" style="width: 250px"><?= $character->name; ?></td>
            <td class="text-center <?= ($character->moloko == 1) ? 'bg-success' : ''; ?>" style="width: 100px"><?= ($character->moloko == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center <?= ($character->yico == 1) ? 'bg-success' : ''; ?>" style="width: 100px"><?= ($character->yico == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center <?= ($character->pshenica == 1) ? 'bg-success' : ''; ?>" style="width: 100px"><?= ($character->pshenica == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center <?= ($character->fish == 1) ? 'bg-success' : ''; ?>" style="width: 100px"><?= ($character->fish == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center <?= ($character->shocolad == 1) ? 'bg-success' : ''; ?>" style="width: 100px"><?= ($character->shocolad == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center <?= ($character->orehi == 1) ? 'bg-success' : ''; ?>" style="width: 100px"><?= ($character->orehi == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center <?= ($character->citrus == 1) ? 'bg-success' : ''; ?>" style="width: 100px"><?= ($character->citrus == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center <?= ($character->med == 1) ? 'bg-success' : ''; ?>" style="width: 100px"><?= ($character->med == 1) ? 'Есть' : 'Нет'; ?></td>

            <td class="text-center <?= ($character->sahar == 1) ? 'bg-success' : ''; ?>" style="width: 100px"><?= ($character->sahar == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center <?= ($character->cialic == 1) ? 'bg-success' : ''; ?>" style="width: 100px"><?= ($character->cialic == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center <?= ($character->ovz == 1) ? 'bg-success' : ''; ?>" style="width: 100px"><?= ($character->ovz == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center <?= ($character->fenilketon == 1) ? 'bg-success' : ''; ?>" style="width: 100px"><?= ($character->fenilketon == 1) ? 'Есть' : 'Нет'; ?></td>
            <td class="text-center <?= ($character->mukovis == 1) ? 'bg-success' : ''; ?>" style="width: 100px"><?= ($character->mukovis == 1) ? 'Есть' : 'Нет'; ?></td>
        </tr>
        <?}?>
<!--        --><?//if($count_cl > 0){?>
<!--            <tr class="table-danger">-->
<!--                <td class="text-left" colspan="5">Итого</td>-->
<!--                <td></td>-->
<!--                <td></td>-->
<!--                <td></td>-->
<!--                <td></td>-->
<!--                <td></td>-->
<!--                <td></td>-->
<!--                <td></td>-->
<!--                <td></td>-->
<!--                <td></td>-->
<!--                <td></td>-->
<!--                <td></td>-->
<!--                <td></td>-->
<!--                <td></td>-->
<!--                <td></td>-->
<!--            </tr>-->
<!--        --><?//}?>

        <?}?>
        <?}else{?>
            <tr><td class="text-center" colspan="17" style="width: 100px">Нет данных</td></tr>
        <?}?>
        </tbody>
    </table>
</div>
</div>
