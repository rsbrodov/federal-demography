<?php

use common\models\User;
use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Отчет по посещаемости с 01.09.2021';
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1 class="text-center mb-5"><?= Html::encode($this->title) ?></h1>

    <p class="" style="font-size: 22px; font-style: italic">Посещения по типам организаций</p>
    <table class="table_th0 table-hover" style="width: 45%;">
        <thead>
        <tr>
            <th class="text-center align-middle" style="width: 20px">Тип организации</th>
            <th class="text-center align-middle" style="width: 40px">Зафиксировано входов в программу</th>
        </tr>
        </thead>
        <tbody>
        <?foreach($type_org_info as $info){?>
            <tr>
                <td class="align-middle"><?= $info['name']?></td>
                <td class="text-center align-middle"><?= $info['count_auth']?></td>
            </tr>
        <?}?>
        </tbody>
    </table>

    <p class="mt-5" style="font-size: 22px; font-style: italic">Посещения по регионам</p>
    <table class="table_th0 table-hover" style="width: 45%;">
        <thead>
        <tr>
            <th class="text-center align-middle" style="width: 20px">Регион</th>
            <th class="text-center align-middle" style="width: 40px">Зафиксировано входов в программу</th>
        </tr>
        </thead>
        <tbody>
        <?foreach($region_info as $info){?>
            <tr>
                <td class="align-middle"><?= $info['region_name']?></td>
                <td class="text-center align-middle"><?= $info['count_auth']?></td>
            </tr>
        <?}?>
        </tbody>
    </table>

    <p class="mt-5" style="font-size: 22px; font-style: italic">Посещения по регионам и типам организаций</p>
    <table class="table_th0 table-hover" style="width: 45%;">
        <thead>
        <tr>
            <th class="text-center align-middle" style="width: 20px">Регион</th>
            <th class="text-center align-middle" style="width: 20px">Тип организации</th>
            <th class="text-center align-middle" style="width: 40px">Зафиксировано входов в программу</th>
        </tr>
        </thead>
        <tbody>
        <?foreach($region_type_org_info as $info){?>
            <tr>
                <td class="align-middle"><?= $info['region_name']?></td>
                <td class="align-middle"><?= $info['name']?></td>
                <td class="text-center align-middle"><?= $info['count_auth']?></td>
            </tr>
        <?}?>
        </tbody>
    </table>


    <p class="mt-5" style="font-size: 22px; font-style: italic">ТОП 7 по каждому типу организаций</p>
    <?$type_org = \common\models\TypeOrganization::find()->all();
    foreach($type_org as $t_org){?>
        <p class=" mt-5"><b><?=$t_org->name?></b></p>
        <table class="table_th0 table-hover" style="width: 45%;">
            <thead>
            <tr>
                <th class="text-center align-middle" style="width: 20px">Регион</th>
                <th class="text-center align-middle" style="width: 20px">Тип организации</th>
                <th class="text-center align-middle" style="width: 20px">Название</th>
                <th class="text-center align-middle" style="width: 40px">Зафиксировано входов в программу</th>
            </tr>
            </thead>
            <tbody>

            <?
            $tops = User::find()->alias('u')->
            select(['r.name as region_name', 'tor.name', 'o.title', 'COUNT(*) as count_auth'])->
            innerJoin('user_autorization_statistic as ua', 'u.id = ua.user_id')->
            innerJoin('organization as o', 'u.organization_id = o.id')->
            innerJoin('type_organization  as tor', 'o.type_org = tor.id ')->
            innerJoin('region as r', 'o.region_id = r.id ')->
            where(['>=' ,'ua.created_at', '2021-09-01 00:00:00'])->
            andWhere(['tor.id' => $t_org->id])->
            groupBy(['o.id'])->
            //having(['>=' ,'count_auth', 9])->
            orderBy(['count_auth' => SORT_DESC])->
            limit(7)->
            asArray()->
            all();
            ?>
            <?foreach($tops as $top){?>
                <tr>
                    <td class="align-middle"><?= $top['region_name']?></td>
                    <td class="align-middle"><?= $top['name']?></td>
                    <td class="align-middle"><?= $top['title']?></td>
                    <td class="text-center align-middle"><?= $top['count_auth']?></td>
                </tr>
            <?}?>
            </tbody>
        </table>
    <?}?>


    <?/* print_r('<pre>');
    print_r($region_type_org_info);
    print_r('</pre>');*/
    ?>

    <??>

</div>
