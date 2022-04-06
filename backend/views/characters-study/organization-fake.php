<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\CharactersStudy */

$this->title = 'Отчет по фейковым организациям';
$this->params['breadcrumbs'][] = ['label' => 'Characters Studies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<style>
    .tables{
        display:flex;
        flex-wrap: wrap;
        justify-content: space-around ;
    }
    th, td {
        border: 1px solid black!important;
        color: black;

    }
    th {
        background-color: #ede8b9;
        font-size: 15px;
    }
    thead, th {
        background-color: #ede8b9;
        font-size: 14px;
    }
</style>
<div class="characters-study-view">

    <h1 class="text-center mt-3 mb-3"><?= Html::encode($this->title) ?></h1>
    <p class="text-center text-danger"><small><b>Удаление организации приведет к полной очистки ее данных из всех таблиц</b></small></p>
    <table class="table_th0 table-hover table-responsive last" >
        <thead>
        <tr>
            <th class="text-center align-middle" rowspan="2" style="max-width: 50px">№</th>
            <th class="text-center align-middle" rowspan="2" style="max-width: 120px">Название/</th>
            <th class="text-center align-middle" rowspan="2" style="max-width: 20px">ID</th>
            <th class="text-center align-middle" rowspan="2" style="max-width: 200px">Регион</th>
            <th class="text-center align-middle" rowspan="2" style="max-width: 200px">М.район</th>
            <th class="text-center align-middle" rowspan="2" style="max-width: 200px">Тип</th>
            <th class="text-center align-middle" rowspan="2" style="max-width: 290px">Меню</th>
            <th class="text-center align-middle" rowspan="1" colspan="3" style="max-width: 290px">ШКОЛЫ</th>
            <th class="text-center align-middle" rowspan="1" colspan="3" style="max-width: 290px">Лагеря</th>
            <th class="text-center align-middle" rowspan="2" style="max-width: 290px">Дата регистрации</th>
            <th class="text-center align-middle" rowspan="2" style="max-width: 290px">Количество аккаунтов</th>
            <th class="text-center align-middle" rowspan="2" style="max-width: 290px">Удаление</th>
        </tr>
        <tr>
            <th class="text-center align-middle" rowspan="1" style="max-width: 290px">ХО</th>
            <th class="text-center align-middle" rowspan="1" style="max-width: 290px">Столовая</th>
            <th class="text-center align-middle" rowspan="1" style="max-width: 290px">Цеха</th>
            <th class="text-center align-middle" rowspan="2" style="max-width: 290px">Дети в отрядах</th>
            <th class="text-center align-middle" rowspan="2" style="max-width: 290px">Факт</th>
            <th class="text-center align-middle" rowspan="2" style="max-width: 290px">План</th>

        </tr>
        </thead>
        <tbody>
        <?$number = 0; foreach($organizations as $organization){$count = 0; ?>
            <?if(\common\models\Menus::find()->where(['organization_id' => $organization->id])->count() == 0){
                $count++;
                $result['menu'] = 0;
            }else{
                $result['menu'] = 1;
                $count=$count -3;
            }?>
        <?if($organization->type_org == 8){
                $count=$count +3;
        }?>
        <?if($organization->type_org == 1){?>
            <?if(\common\models\Kids::find()->where(['organization_id' => $organization->id])->count() == 0){
                $count++;
                $result['kids'] = 0;
            }else{
                $result['kids'] = 1;
            }?>

            <?if(\common\models\FactInfCamp::find()->where(['organization_id' => $organization->id])->count() == 0){
                $count++;
                $result['fact'] = 0;
            }else{
                $result['fact'] = 1;
            }?>
            <?if(\common\models\PlanInfCamp::find()->where(['organization_id' => $organization->id])->count() == 0){
                $count++;
                $result['plan'] = 0;
            }else{
                $result['plan'] = 1;
            }?>
        <?}?>
        <?if($organization->type_org == 3){?>
            <?if(\common\models\CharactersStudy::find()->where(['organization_id' => $organization->id])->count() == 0){
                $count++;
                $result['characters'] = 0;
            }else{
                $result['characters'] = 1;
            }?>
            <?if(\common\models\CharactersStolovaya::find()->where(['organization_id' => $organization->id])->count() == 0){
                $count++;
                $result['stolovaya'] = 0;
            }else{
                $result['stolovaya'] = 1;
            }?>
            <?if(\common\models\BasicInformationRazdelOrganization::find()->where(['organization_id' => $organization->id])->count() == 0){
                $count++;
                $result['razdel'] = 0;
            }else{
                $result['razdel'] = 1;
            }?>
        <?}?>



            <?if($count >= 3){$number++;?>
                <tr>
                    <td><b><?=$number?></b></td>
                    <td><?=$organization->title?></td>
                    <td class="text-center"><?=$organization->id?></td>
                    <td class="text-center"><?=\common\models\Region::find()->where(['id' => $organization->region_id])->one()->name;?></td>
                    <td class="text-center"><?=\common\models\Municipality::find()->where(['id' => $organization->municipality_id])->one()->name;?></td>
                    <td class="text-center"><?=\common\models\TypeOrganization::find()->where(['id' => $organization->type_org])->one()->name;?></td>
                    <td class="text-center <?= $result['menu'] == 0 ? 'bg-danger': 'bg-success';?>"><?=$result['menu']?></td>
                    <?if($organization->type_org ==3){?>
                        <td class="text-center <?= $result['characters'] == 0 ? 'bg-danger': 'bg-success';?>"><?=$result['characters']?></td>
                        <td class="text-center <?= $result['stolovaya'] == 0 ? 'bg-danger': 'bg-success';?>"><?=$result['stolovaya']?></td>
                        <td class="text-center <?= $result['razdel'] == 0 ? 'bg-danger': 'bg-success';?>"><?=$result['razdel']?></td>
                    <?}else{?>
                        <td class="text-center bg-secondary">-</td>
                        <td class="text-center bg-secondary">-</td>
                        <td class="text-center bg-secondary">-</td>
                    <?}?>
                    <?if($organization->type_org == 1){?>
                        <td class="text-center <?= $result['kids'] == 0 ? 'bg-danger': 'bg-success';?>"><?=$result['kids']?></td>
                        <td class="text-center <?= $result['fact'] == 0 ? 'bg-danger': 'bg-success';?>"><?=$result['fact']?></td>
                        <td class="text-center <?= $result['plan'] == 0 ? 'bg-danger': 'bg-success';?>"><?=$result['plan']?></td>
                    <?}else{?>
                        <td class="text-center bg-secondary">-</td>
                        <td class="text-center bg-secondary">-</td>
                        <td class="text-center bg-secondary">-</td>
                    <?}?>

                    <td class="text-center"><?=date('d.m.Y', strtotime($organization->created_at))?></td>
                    <td class="text-center"><?=\common\models\User::find()->where(['organization_id' => $organization->id])->count()?></td>
                    <td class="text-center"><?= Html::a('Удаление', ['organizations/delete', 'id' => $organization->id], [
                            'class' => 'btn btn-danger',
                        ]) ?></td>
                </tr>
            <?}?>
        <?}?>
        </tbody>
    </table>

    <p class="text-center mt-2" style="font-size: 20px;"><b>Процент фейковых организаций в программе: <?=round($number/\common\models\Organization::find()->count(),4)*100;?>%</b></p>

</div>
