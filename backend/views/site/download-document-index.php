<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\bootstrap4\Html;

$this->title = 'Справочная информация';
?>
<div class="site-error">

    <h1 class="text-center">Справочная информация</h1>
<!--    <p class="text-center">Фрагмент проекта методических рекомендаций по организации и проведению производственного контроля за организацией питания детей, основанного на принципах HACCP</p>-->

    <div class="container">
        <table class="table table-bordered mt-5">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Название документа</th>
                <th scope="col">Скачать</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>1</td>
                <td>СанПиН 1.2.3685-21 "Гигиенические нормативы и требования к обеспечению безопасности и (или) безвредности для человека факторов среды обитания"</td>
                <td><?= Html::a('<span class="glyphicon glyphicon-download"></span> Скачать в Word', ['download-document?name=sp1.2.3685-21.rtf'],
                        [
                            'class'=>'btn btn-primary',
                            'style' =>['width'=>'200px'],
                            'title' => Yii::t('yii', 'Скачать отчет в формате word'),
                            'data-toggle'=>'tooltip',
                        ])
                ?></td>
            </tr>
            <tr>
                <td>2</td>
                <td>СанПиН 2.3/2.4.3590-20 "Санитарно-эпидемиологические требования к организации общественного питания населения"</td>
                <td><?= Html::a('<span class="glyphicon glyphicon-download"></span> Скачать в Word', ['download-document?name=sp2.3_2.4.3590-20.rtf'],
                        [
                            'class'=>'btn btn-primary',
                            'style' =>['width'=>'200px'],
                            'title' => Yii::t('yii', 'Скачать отчет в формате word'),
                            'data-toggle'=>'tooltip',
                        ])
                    ?></td>
            </tr>
            <tr>
                <td>3</td>
                <td>СП 2.4.3648-20 "Санитарно-эпидемиологические требования к организациям воспитания и обучения, отдыха и оздоровления детей и молодежи"</td>
                <td><?= Html::a('<span class="glyphicon glyphicon-download"></span> Скачать в Word', ['download-document?name=sp2.4.3648-20.rtf'],
                        [
                            'class'=>'btn btn-primary',
                            'style' =>['width'=>'200px'],
                            'title' => Yii::t('yii', 'Скачать отчет в формате word'),
                            'data-toggle'=>'tooltip',
                        ])
                    ?></td>
            </tr>
            <tr>
                <td>4</td>
                <td>Рекомендации по проведению оценки соответствия меню обязательным требованиям</td>
                <td><?= Html::a('<span class="glyphicon glyphicon-download"></span> Скачать в PDF', ['download-document?name=mr-2.4.0260_21-otsenka-menyu.pdf'],
                        [
                            'class'=>'btn btn-danger',
                            'style' =>['width'=>'200px'],
                            'title' => Yii::t('yii', 'Скачать отчет в формате word'),
                            'data-toggle'=>'tooltip',
                        ])
                    ?></td>
            </tr>
            <tr>
                <td>5</td>
                <td>Нормы физиологических потребностей в энергии и пищевых веществах для различных групп населения РФ</td>
                <td><?= Html::a('<span class="glyphicon glyphicon-download"></span> Скачать в PDF', ['download-document?name=1.-mr-2.3.1.0253_21-normy-pishchevykh-veshchestv.pdf'],
                        [
                            'class'=>'btn btn-danger',
                            'style' =>['width'=>'200px'],
                            'title' => Yii::t('yii', 'Скачать отчет в формате word'),
                            'data-toggle'=>'tooltip',
                        ])
                    ?></td>
            </tr>

            <tr>
                <td>6</td>
                <td>Фрагмент проекта методических рекомендаций по организации и проведению производственного контроля за организацией питания детей, основанного на принципах HACCP</td>
                <td><?= Html::a('<span class="glyphicon glyphicon-download"></span> Скачать в PDF', ['download-document?name=ФРАГМЕНТ_ПроектаМР_контроль за питанием_основанный на принципахНАССП.pdf'],
                        [
                            'class'=>'btn btn-danger',
                            'style' =>['width'=>'200px'],
                            'title' => Yii::t('yii', 'Скачать отчет в формате word'),
                            'data-toggle'=>'tooltip',
                        ])
                    ?></td>
            </tr>
            </tbody>
        </table>
    </div>

</div>
