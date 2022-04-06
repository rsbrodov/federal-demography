<?php

use common\models\SchoolBreak;
use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Button;
use common\models\Menus;
use common\models\Days;
use common\models\MenusDays;
use common\models\MenusDishes;
use common\models\MenusNutrition;
use common\models\Organization;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Результаты мониторинга организации';
$this->params['breadcrumbs'][] = $this->title;

$menu = new Menus();
$menus_dishes_model = new MenusDishes();



$normativ = [];
$normativ['vitamin_c'] = \common\models\NormativVitaminDay::find()->where(['name' => 'vitamin_c', 'age_info_id' => 6])->one()->value;
$normativ['vitamin_b1'] = \common\models\NormativVitaminDay::find()->where(['name' => 'vitamin_b1', 'age_info_id' => 6])->one()->value;
$normativ['vitamin_b2'] = \common\models\NormativVitaminDay::find()->where(['name' => 'vitamin_b2', 'age_info_id' => 6])->one()->value;
$normativ['vitamin_a'] = \common\models\NormativVitaminDay::find()->where(['name' => 'vitamin_a', 'age_info_id' => 6])->one()->value;
$normativ['ca'] = \common\models\NormativVitaminDay::find()->where(['name' => 'ca', 'age_info_id' => 6])->one()->value;
$normativ['p'] = \common\models\NormativVitaminDay::find()->where(['name' => 'p', 'age_info_id' => 6])->one()->value;
$normativ['mg'] = \common\models\NormativVitaminDay::find()->where(['name' => 'mg', 'age_info_id' => 6])->one()->value;
$normativ['fe'] = \common\models\NormativVitaminDay::find()->where(['name' => 'fe', 'age_info_id' => 6])->one()->value;
$normativ['i'] = \common\models\NormativVitaminDay::find()->where(['name' => 'i', 'age_info_id' => 6])->one()->value;
$normativ['se'] = \common\models\NormativVitaminDay::find()->where(['name' => 'se', 'age_info_id' => 6])->one()->value;

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
        thead, th {
            background-color: #ede8b9;
            font-size: 10px;
        }
    </style>

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>


    <div class="container-fluid">
                <table class="table table-bordered table-sm">
                    <thead>
                    <tr>
                        <td rowspan="3" style="min-width: 300px;">Наименование общеобразовательной организации</td>
                        <td rowspan="2" colspan="3">Количество школьников 1-4 кл. обучающихся очно</td>
                        <td rowspan="2" colspan="2">Продолжительность перемен для питания детей (в мин.)</td>
                        <td rowspan="2" colspan="6">Количество ШКОЛЬНИКОВ 1-4 кл. имеющих, заболевания требующие индивидуального подхода в организации питания</td>
                        <td rowspan="1" colspan="2">Количество</td>
                        <td rowspan="3">Количество оборудованных посадочных мест</td>
                        <td rowspan="3">Площадь на 1 посадочное место</td>
                        <td rowspan="3">Максимальное количество одномоментно питающихся детей</td>
                        <td rowspan="3">фактическая площадь на одно посадочное место</td>
                        <td rowspan="3">Имеется дефицит площади столовой</td>
                        <td rowspan="3">Количество функционирующих умывальников уст. перед входом в столовую</td>
                        <td rowspan="3">Количество детей на один умывальник (д.б. не менее 1 на 20 дет)</td>
                        <td rowspan="3">Имеется дефицит умывальников</td>

                        <td rowspan="2" colspan="8">Количество внесенных меню (ЗАВТРАКОВ)</td>



                        <td colspan="14">Средние показатели, характеризующие завтраки</td>
                        <td rowspan="2" colspan="6">количество дней в меню, предусматривающих выдачу в завтрак</td>
                        <td rowspan="3">Дефицит калорийности блюд(ЗАВТРАК)</td>
                        <td rowspan="2" colspan="8">Количество внесенных меню (ОБЕДОВ)</td>
                        <td colspan="14">ОБЕД</td>
                        <td rowspan="2" colspan="6">количество дней в меню, предусматривающих выдачу в Обед</td>
                        <td rowspan="3">Дефицит калорийности блюд(ОБЕД)</td>
                        <td colspan="38">количество школьников, получающих бесплатное питание и охват  в %</td>



                        <td class="text-center align-middle" colspan="7">ЗАВТРАКИ</td>
                        <td class="text-center align-middle" colspan="7">ОБЕДЫ</td>
                    </tr>
                    <tr>

                       <td colspan="2">детей официально отказавшихся от бесплатного питания, удельный вес их от всех школьников 1-4 классов</td>
                        <!--ЗАВТРАК-->
                        <td >суммарная масса блюд (г.)</td>
                        <td>калорийность (ккал)</td>
                        <td colspan="4">Содержание витаминов</td>
                        <td colspan="6">Содержание минеральных веществ</td>
                        <td colspan="2">Содержание в среднем за прием пищи</td>



                        <!--ОБЕД-->
                        <td >суммарная масса блюд (г.)</td>
                        <td >калорийность (ккал)</td>
                        <td colspan="4">Содержание витаминов</td>
                        <td colspan="6">Содержание минеральных веществ</td>
                        <td colspan="2">Содержание в среднем за прием пищи</td>


                        <!--            озватпит-->
                        <td colspan="2">школьников 1-4 кл. (всего)</td>
                        <td colspan="2">школьников 1-4 кл. получающих бесплатные горячие завтраки</td>
                        <td colspan="2">школьников 1-4 кл. получающих бесплатные обеды</td>
                        <td colspan="2">школьников 1-4 кл. получающих бесплатные завтраки и обеды</td>
                        <td colspan="5" class="text-center">СД</td>
                        <td colspan="5" class="text-center">целиакия</td>
                        <td colspan="5" class="text-center">муковисцедоз</td>
                        <td colspan="5" class="text-center">ФКУ</td>
                        <td colspan="5" class="text-center">ОВЗ</td>
                        <td colspan="5" class="text-center">ПА</td>






                        <td rowspan="2">Количество мероприятий внутреннего контроля</td>
                        <td colspan="3">Количество баллов</td>
                        <td colspan="3">Процент несъеденной пищи</td>

                        <td rowspan="2">Количество мероприятий внутреннего контроля</td>
                        <td colspan="3">Количество баллов</td>
                        <td colspan="3">Процент несъеденной пищи</td>
                    </tr>
                    <tr>
                        <td>всего</td>
                        <td>в I-ю смену</td>
                        <td>во II-ю</td>
                        <td>мин</td>
                        <td>макс</td>

                        <td>СД</td>
                        <td>Цел</td>
                        <td>Мук</td>
                        <td>ФКУ</td>
                        <td>овз</td>
                        <td>ПА</td>



                        <td>Всего</td>
                        <td>%</td>


                        <td>Всего</td>
                        <td>Осн</td>
                        <td>СД</td>
                        <td>Цел</td>
                        <td>Мук</td>
                        <td>ФКУ</td>
                        <td>овз</td>
                        <td>ПА</td>



                        <td>ср</td>

                        <td>ср</td>

                        <td>С(мг)</td>
                        <td>В1(мг)</td>
                        <td>В2(мг)</td>
                        <td>А (р.э.)</td>

                        <td>кальций (мг)</td>
                        <td>фосфор (мг)</td>
                        <td>магний (мг)</td>
                        <td>железо (мг)</td>
                        <td>йод (мг)</td>
                        <td>селен (мг)</td>

                        <td>соли (г)</td>
                        <td>сахара (г)</td>

                        <td>колбасных изделий</td>
                        <td>кондитерских изделий</td>
                        <td>фруктов</td>
                        <td>ягод</td>
                        <td>меда</td>
                        <td>овощей</td>

                        <td>Всего</td>
                        <td>Осн</td>
                        <td>СД</td>
                        <td>Цел</td>
                        <td>Мук</td>
                        <td>ФКУ</td>
                        <td>овз</td>
                        <td>ПА</td>



                        <!--ОБЕД-->
                        <td>ср</td>

                        <td>ср</td>

                        <td>С(мг)</td>
                        <td>В1(мг)</td>
                        <td>В2(мг)</td>
                        <td>А (р.э.)</td>

                        <td>кальций (мг)</td>
                        <td>фосфор (мг)</td>
                        <td>магний (мг)</td>
                        <td>железо (мг)</td>
                        <td>йод (мг)</td>
                        <td>селен (мг)</td>

                        <td>соли (г)</td>
                        <td>сахара (г)</td>

                        <td>колбасных изделий</td>
                        <td>кондитерских изделий</td>
                        <td>фруктов</td>
                        <td>ягод</td>
                        <td>меда</td>
                        <td>овощей</td>




                        <!--треб/пит-->
                        <td>всего</td>
                        <td>%</td>
                        <td>всего</td>
                        <td>%</td>
                        <td>всего</td>
                        <td>%</td>
                        <td>всего</td>
                        <td>%</td>

                        <td>кол-во шк</td>
                        <td>кол-во дет</td>
                        <td>кол-во шк внес меню</td>
                        <td>кол-во пит дет</td>
                        <td>кол-во не пит дет</td>

                        <td>кол-во шк</td>
                        <td>кол-во дет</td>
                        <td>кол-во шк внес меню</td>
                        <td>кол-во пит дет</td>
                        <td>кол-во не пит дет</td>


                        <td>кол-во шк</td>
                        <td>кол-во дет</td>
                        <td>кол-во шк внес меню</td>
                        <td>кол-во пит дет</td>
                        <td>кол-во не пит дет</td>

                        <td>кол-во шк</td>
                        <td>кол-во дет</td>
                        <td>кол-во шк внес меню</td>
                        <td>кол-во пит дет</td>
                        <td>кол-во не пит дет</td>

                        <td>кол-во шк</td>
                        <td>кол-во дет</td>
                        <td>кол-во шк внес меню</td>
                        <td>кол-во пит дет</td>
                        <td>кол-во не пит дет</td>

                        <td>кол-во шк</td>
                        <td>кол-во дет</td>
                        <td>кол-во шк внес меню</td>
                        <td>кол-во пит дет</td>
                        <td>кол-во не пит дет</td>


                        <td>мин</td>
                        <td>ср</td>
                        <td>макс</td>
                        <td>мин</td>
                        <td>ср</td>
                        <td>макс</td>

                        <td>мин</td>
                        <td>ср</td>
                        <td>макс</td>
                        <td>мин</td>
                        <td>ср</td>
                        <td>макс</td>

                    </tr>

                    </thead>
                    <tbody>
                    <? $itog_string = []; $count = 0; $sred = []; foreach ($organizations as $organization) { $count++;?>
                        <? $character_stolovaya = \common\models\CharactersStolovaya::find()->where(['organization_id' => $organization->id])->one();?>
                        <? $school_break_min = SchoolBreak::find()->where(['organization_id' => $organization->id])->min('duration');?>
                        <? $school_break_max = SchoolBreak::find()->where(['organization_id' => $organization->id])->max('duration');?>
                        <? $school_break_count = SchoolBreak::find()->where(['organization_id' => $organization->id])->count();if($school_break_count != 0){$school_break_sred = round(SchoolBreak::find()->where(['organization_id' => $organization->id])->sum('duration')/$school_break_count,1);}else{$school_break_sred = 0;}?>
                        <? $information_education = \common\models\InformationEducation::find()->where(['organization_id' => $organization->id])->one();?>
                        <? $cs_mas = []; $characters_studies = \common\models\CharactersStudy::find()->where(['organization_id' => $organization->id, 'class_number' => [1,2,3,4]])->all();?>
                        <? $characters_studies_all = \common\models\CharactersStudy::find()->where(['organization_id' => $organization->id])->all();
                        $number = [];
                        $vt_smena = $information_education->quantity14 - $information_education->quantity14_first;

                        foreach ($characters_studies_all as $characters_study_all)
                        {
                            $number[$characters_study_all->smena . "_" . $characters_study_all->number_peremena] = $number[$characters_study_all->smena . "_" . $characters_study_all->number_peremena] + $characters_study_all->count_ochno;
                        }
                        foreach ($characters_studies as $characters_study){
                            //$number[$characters_study->smena."_".$characters_study->number_peremena] = $number[$characters_study->smena."_".$characters_study->number_peremena] + $characters_study->count_ochno;

                            if($characters_study->count_ochno > 0){
                                $cs_mas['count_kid'] = $cs_mas['count_kid'] + $characters_study->count;
                            }

                            $cs_mas['count_kid_ochno'] = $cs_mas['count_kid_ochno'] + $characters_study->count_ochno;

                            $cs_mas['sahar'] = $cs_mas['sahar'] + $characters_study->sahar;
                            $cs_mas['ovz'] = $cs_mas['ovz'] + $characters_study->ovz;
                            $cs_mas['cialic'] = $cs_mas['cialic'] + $characters_study->cialic;
                            $cs_mas['allergy'] = $cs_mas['allergy'] + $characters_study->allergy;
                            $cs_mas['mukovis'] = $cs_mas['mukovis'] + $characters_study->mukovis;
                            $cs_mas['fenilketon'] = $cs_mas['fenilketon'] + $characters_study->fenilketon;

                            if($characters_study->types_pit == 1){
                                $cs_mas['zavtrak'] = $cs_mas['zavtrak'] + $characters_study->count_ochno;
                                //$cs_mas['ohvat_zavtrak'] = $cs_mas['ohvat_zavtrak'] + $characters_study->sahar + $characters_study->cialic + $characters_study->allergy + $characters_study->fenilketon + $characters_study->mukovis + $characters_study->ovz;
                                $cs_mas['otkaz_zavtrak'] = $cs_mas['otkaz_zavtrak'] + $characters_study->otkaz_sahar + $characters_study->otkaz_cialic + $characters_study->otkaz_allergy + $characters_study->otkaz_inoe;

                            }
                            if($characters_study->types_pit == 2){
                                $cs_mas['obed'] = $cs_mas['obed'] + $characters_study->count_ochno;
                                //$cs_mas['ohvat_obed'] = $cs_mas['ohvat_obed'] + $characters_study->sahar + $characters_study->cialic + $characters_study->allergy + $characters_study->fenilketon + $characters_study->mukovis + $characters_study->ovz;
                                $cs_mas['otkaz_obed'] = $cs_mas['otkaz_obed'] + $characters_study->otkaz_sahar + $characters_study->otkaz_cialic + $characters_study->otkaz_allergy + $characters_study->otkaz_inoe;

                            }
                            if($characters_study->types_pit == 3){
                                $cs_mas['zavtrak_obed'] = $cs_mas['zavtrak_obed'] + $characters_study->count_ochno;
                                //$cs_mas['ohvat_obed'] = $cs_mas['ohvat_obed'] + $characters_study->sahar + $characters_study->cialic + $characters_study->allergy + $characters_study->fenilketon + $characters_study->mukovis + $characters_study->ovz;
                                $cs_mas['otkaz_zavtrak_obed'] = $cs_mas['otkaz_zavtrak_obed'] + $characters_study->otkaz_sahar + $characters_study->otkaz_cialic + $characters_study->otkaz_allergy + $characters_study->otkaz_inoe;

                            }

                            $cs_mas['otkaz'] = $cs_mas['otkaz'] + $characters_study->otkaz_sahar + $characters_study->otkaz_cialic + $characters_study->otkaz_allergy + $characters_study->otkaz_inoe;
                            $cs_mas['otkaz_allergy'] = $cs_mas['otkaz_allergy'] + $characters_study->otkaz_allergy;
                            $cs_mas['otkaz_cialic'] = $cs_mas['otkaz_cialic'] + $characters_study->otkaz_cialic;
                            $cs_mas['otkaz_sahar'] = $cs_mas['otkaz_sahar'] + $characters_study->otkaz_sahar;

                            $cs_mas['otkaz_ovz'] = $cs_mas['otkaz_ovz'] + $characters_study->otkaz_ovz;
                            $cs_mas['otkaz_mukovis'] = $cs_mas['otkaz_mukovis'] + $characters_study->otkaz_mukovis;
                            $cs_mas['otkaz_fenilketon'] = $cs_mas['otkaz_fenilketon'] + $characters_study->otkaz_fenilketon;
                        }
                        if(empty($cs_mas['otkaz'])){
                            $cs_mas['otkaz'] = 0;
                        }
                        if(!empty($number)){
                            $pita = max($number);
                        }else{
                            $pita = 0;
                        }


                        $menu_mas = [];
                        $menus = \common\models\Menus::find()->where(['organization_id' => $organization->id, 'age_info_id' => [6, 9], 'status_archive' => 0])->all();
                        foreach($menus as $menu){
                            if(MenusDishes::find()->where(['menu_id' => $menu->id, 'nutrition_id' => 1])->count() > 0){
                                $menu_mas['zavtrak'][$menu->feeders_characters_id] = $menu_mas['zavtrak'][$menu->feeders_characters_id] + 1;
                                $menu_mas['zavtrak']['itog'] = $menu_mas['zavtrak']['itog'] + 1;
                            }
                            if(MenusDishes::find()->where(['menu_id' => $menu->id, 'nutrition_id' => 3])->count() > 0){
                                $menu_mas['obed'][$menu->feeders_characters_id] = $menu_mas['obded'][$menu->feeders_characters_id] + 1;
                                $menu_mas['obed']['itog'] = $menu_mas['obed']['itog'] + 1;
                            }
                        }
                        $menu = \common\models\Menus::find()->where(['organization_id' => $organization->id, 'feeders_characters_id' => 3, 'age_info_id' => [6, 9]])->count();
                        ?>

                        <tr>

                            <td class="align-middle"><?echo (empty($organization->short_title)) ? $organization->title : $organization->short_title;?></td>
                            <?if(empty($information_education->quantity14) || empty($information_education->quantity14_first)){?>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                            <?}else{?>
                                <td class="text-center align-middle"><?$itog_string['ochno_vsego'] = $itog_string['ochno_vsego'] + $information_education->quantity14; echo $information_education->quantity14;?></td>
                                <td class="text-center align-middle"><?$itog_string['ochno_pervaya'] = $itog_string['ochno_pervaya'] + $information_education->quantity14_first; echo $information_education->quantity14_first;?></td>
                                <td class="text-center align-middle"><?$itog_string['ochno_vtoraya'] = $itog_string['ochno_vtoraya'] + $vt_smena; echo $vt_smena;?></td>
                            <?}?>

                            <?if($school_break_count == 0){?>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                            <?}else{?>
                                <td class="text-center align-middle"><?= $school_break_min; ?></td>
                                <td class="text-center align-middle"><?= $school_break_max; ?></td>
                            <?}?>

                             <td class="text-center align-middle"><? if (empty($cs_mas['sahar'])){echo '-';}else{$itog_string['student_sahar'] = $itog_string['student_sahar'] + $cs_mas['sahar']; echo $cs_mas['sahar'];}?></td>
                            <td class="text-center align-middle"><? if (empty($cs_mas['cialic'])){echo '-';}else{$itog_string['student_cialic'] = $itog_string['student_cialic'] + $cs_mas['cialic']; echo $cs_mas['cialic'];}?></td>
                            <td class="text-center align-middle"><? if (empty($cs_mas['mukovis'])){echo '-';}else{$itog_string['student_mukovis'] = $itog_string['student_mukovis'] + $cs_mas['mukovis']; echo $cs_mas['mukovis'];}?></td>
                            <td class="text-center align-middle"><? if (empty($cs_mas['fenilketon'])){echo '-';}else{$itog_string['student_fenilketon'] = $itog_string['student_fenilketon'] + $cs_mas['fenilketon']; echo $cs_mas['fenilketon'];}?></td>
                            <td class="text-center align-middle"><? if (empty($cs_mas['ovz'])){echo '-';}else{$itog_string['student_ovz'] = $itog_string['student_ovz'] + $cs_mas['ovz']; echo $cs_mas['ovz'];}?></td>
                            <td class="text-center align-middle"><? if (empty($cs_mas['allergy'])){echo '-';}else{$itog_string['student_allergy'] = $itog_string['student_allergy'] + $cs_mas['allergy']; echo $cs_mas['allergy'];}?></td>
                            <?if(empty($characters_studies)){?>

                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                            <?}else{?>
                                <td class="text-center align-middle"><?= $cs_mas['otkaz'];$itog_string['student_otkaz'] = $itog_string['student_otkaz'] + $cs_mas['otkaz'];?></td>
                                <td class="text-center align-middle"><?if (empty($information_education->quantity14)){ echo 'нд';}else{ echo round($cs_mas['otkaz']/$information_education->quantity14*100, 1);$itog_string['student_otkaz_procent'] = $itog_string['student_otkaz_procent'] + round($cs_mas['otkaz']/$information_education->quantity14*100, 1);}?></td>
                            <?}?>



                            <?if(empty($character_stolovaya->spot)){?>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                            <?}else{?>
                                <td class="text-center align-middle"><?=$character_stolovaya->spot; $itog_string['kolichestvo_posad'] = $itog_string['kolichestvo_posad'] + $character_stolovaya->spot;?></td>
                                <td class="text-center align-middle"><?=round($character_stolovaya->square/$character_stolovaya->spot, 1);$itog_string['ploshad_posad'] = $itog_string['ploshad_posad'] + round($character_stolovaya->square/$character_stolovaya->spot, 1);$itog_string['ploshad_posad_count'] = $itog_string['ploshad_posad_count'] + 1;?></td>
                            <?}?>
                            <?if($pita == 0){?>
                                <td class="text-center align-middle bg-secondary">нд</td>
                            <?}else{?>
                                <td class="text-center align-middle"><?=$pita;?></td>
                            <?}?>
                            <?if($pita == 0 || empty($character_stolovaya->spot)){?>
                                <td class="text-center align-middle bg-secondary">нд</td>
                            <?}else{?>
                                <td class="text-center align-middle"><?$fact = round(round($character_stolovaya->square/$character_stolovaya->spot, 1) * $character_stolovaya->spot / $pita,2); echo $fact; $itog_string['fact_ploshad'] = $itog_string['fact_ploshad'] + $fact;$itog_string['fact_ploshad_count'] = $itog_string['fact_ploshad_count'] + 1;?></td>
                            <?}?>


                            <?if($pita == 0 || empty($character_stolovaya->spot)){?>
                                <td class="text-center align-middle bg-secondary">нд</td>
                            <?}elseif($pita > $character_stolovaya->spot){?>
                                <td class="text-center align-middle"><?=1; $itog_string['deficit_previsaet'] = $itog_string['deficit_previsaet'] + 1;?></td>
                            <?}else{?>
                                <td class="text-center align-middle">0</td>
                            <?}?>

                            <?if(empty($character_stolovaya->count_washing)){?>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>

                            <?}else{?>
                                <td class="text-center align-middle"><?=$character_stolovaya->count_washing;?></td>
                                <td class="text-center align-middle <?if(round($pita/$character_stolovaya->count_washing, 0)> 20){echo "bg-danger";}?>"><?=round($pita/$character_stolovaya->count_washing, 0);?></td>
                                <td class="text-center align-middle <?if(round($pita/$character_stolovaya->count_washing, 0)> 20){echo "bg-danger";}?>"><?if(round($pita/$character_stolovaya->count_washing, 0) > 20){echo 'Есть';$itog_string['deficit_umivalnik'] = $itog_string['deficit_umivalnik'] + 1;}else{ echo 'Нет';}?></td>
                                <?}?>


                            <td class="text-center align-middle"><?if (empty($menu_mas['zavtrak']['itog'])){ echo 0; }else{ echo $menu_mas['zavtrak']['itog'];$itog_string['vnes_zavtrak_itog_count'] = $itog_string['vnes_zavtrak_itog_count'] + $menu_mas['zavtrak']['itog'];}?></td>
                            <td class="text-center align-middle"><?if (empty($menu_mas['zavtrak'][3])){ echo 0; }else{ echo $menu_mas['zavtrak'][3];$itog_string['vnes_zavtrak_3_count'] = $itog_string['vnes_zavtrak_3_count'] + $menu_mas['zavtrak'][3];}?></td>
                            <td class="text-center align-middle <?if($cs_mas['sahar'] > 0 && empty($menu_mas['zavtrak'][5]) && empty($menu_mas['obed'][5])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][5])){ echo 0; }else{ echo $menu_mas['zavtrak'][5];$itog_string['vnes_zavtrak_5_count'] = $itog_string['vnes_zavtrak_5_count'] + $menu_mas['zavtrak'][5];}?></td>
                            <td class="text-center align-middle <?if($cs_mas['cialic'] > 0 && empty($menu_mas['zavtrak'][6]) && empty($menu_mas['obed'][6])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][6])){ echo 0; }else{ echo $menu_mas['zavtrak'][6];$itog_string['vnes_zavtrak_6_count'] = $itog_string['vnes_zavtrak_6_count'] + $menu_mas['zavtrak'][6];}?></td>
                            <td class="text-center align-middle <?if($cs_mas['mukovis'] > 0 && empty($menu_mas['zavtrak'][8]) && empty($menu_mas['obed'][8])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][8])){ echo 0; }else{ echo $menu_mas['zavtrak'][8];$itog_string['vnes_zavtrak_8_count'] = $itog_string['vnes_zavtrak_8_count'] + $menu_mas['zavtrak'][8];}?></td>
                            <td class="text-center align-middle <?if($cs_mas['fenilketon'] > 0 && empty($menu_mas['zavtrak'][7]) && empty($menu_mas['obed'][7])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][7])){ echo 0; }else{ echo $menu_mas['zavtrak'][7];$itog_string['vnes_zavtrak_7_count'] = $itog_string['vnes_zavtrak_7_count'] + $menu_mas['zavtrak'][7];}?></td>
                            <td class="text-center align-middle <?if($cs_mas['ovz'] > 0 && empty($menu_mas['zavtrak'][4]) && empty($menu_mas['obed'][4])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][4])){ echo 0; }else{ echo $menu_mas['zavtrak'][4];$itog_string['vnes_zavtrak_4_count'] = $itog_string['vnes_zavtrak_4_count'] + $menu_mas['zavtrak'][4];}?></td>
                            <td class="text-center align-middle <?if($cs_mas['allergy'] > 0 && empty($menu_mas['zavtrak'][10]) && empty($menu_mas['obed'][10])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][10])){ echo 0; }else{ echo $menu_mas['zavtrak'][10];$itog_string['vnes_zavtrak_10_count'] = $itog_string['vnes_zavtrak_10_count'] + $menu_mas['zavtrak'][10];}?></td>



                            <?if($menu > 0){
                                if(!empty($control_zavtrak)){
                                    unset($control_zavtrak);
                                }
                                if(!empty($control_obed)){
                                    unset($control_obed);
                                }
                                $menu_zavtrak = $menus_dishes_model->get_menu_information($organization->id, 1);
                                $menu_obed = $menus_dishes_model->get_menu_information($organization->id, 3);

                                //$control_zavtrak = $model->get_control_information($organization->id, 1);
                                //$control_obed = $model->get_control_information($organization->id, 3);
                                if($menu_zavtrak['yield'] == 0 || $menu_zavtrak== "null"){?>
                                    <td colspan="21" class="text-center align-middle text-danger">Меню не внесено</td>
                                <?}else{?>
                                    <!--                    <td class="text-center align-middle">--><?//= $menu_zavtrak['min_yield']?><!--</td>-->
                                    <td class="text-center align-middle"><?= round($menu_zavtrak['yield'],1); $itog_string['menu_zavtrak_yield'] = $itog_string['menu_zavtrak_yield'] + round($menu_zavtrak['yield'],1);$itog_string['menu_zavtrak_count'] = $itog_string['menu_zavtrak_count'] + 1;?></td>
                                    <!--                    <td class="text-center align-middle">--><?//= $menu_zavtrak['max_yield']?><!--</td>-->

                                    <!--                    <td class="text-center align-middle">--><?//= round($menu_zavtrak['min_kkal'],1)?><!--</td>-->
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['kkal'],1) < 400){echo "bg-danger";}?>"><?= round($menu_zavtrak['kkal'],1);$itog_string['menu_zavtrak_kkal'] = $itog_string['menu_zavtrak_kkal'] + round($menu_zavtrak['kkal'],1);?></td>
                                    <!--                    <td class="text-center align-middle">--><?//= round($menu_zavtrak['max_kkal'],1)?><!--</td>-->
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['vitamin_c'],1) < $normativ['vitamin_c'] * 0.2){echo "bg-danger";}?>"><?= round($menu_zavtrak['vitamin_c'],1);$itog_string['menu_zavtrak_vitamin_c'] = $itog_string['menu_zavtrak_vitamin_c'] + round($menu_zavtrak['vitamin_c'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['vitamin_b1'],1)< $normativ['vitamin_b1'] * 0.2){echo "bg-danger";}?>"><?= round($menu_zavtrak['vitamin_b1'],1);$itog_string['menu_zavtrak_vitamin_b1'] = $itog_string['menu_zavtrak_vitamin_b1'] + round($menu_zavtrak['vitamin_b1'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['vitamin_b2'],1) < $normativ['vitamin_b2'] * 0.2){echo "bg-danger";}?>"><?= round($menu_zavtrak['vitamin_b2'],1);$itog_string['menu_zavtrak_vitamin_b2'] = $itog_string['menu_zavtrak_vitamin_b2'] + round($menu_zavtrak['vitamin_b2'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['vitamin_a'],1) < $normativ['vitamin_a'] * 0.2){echo "bg-danger";}?>"><?= round($menu_zavtrak['vitamin_a'],1);$itog_string['menu_zavtrak_vitamin_a'] = $itog_string['menu_zavtrak_vitamin_a'] + round($menu_zavtrak['vitamin_a'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['ca'],1) < $normativ['ca'] * 0.2){echo "bg-danger";}?>"><?= round($menu_zavtrak['ca'],1);$itog_string['menu_zavtrak_ca'] = $itog_string['menu_zavtrak_ca'] + round($menu_zavtrak['ca'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['p'],1) < $normativ['p'] * 0.2){echo "bg-danger";}?>"><?= round($menu_zavtrak['p'],1);$itog_string['menu_zavtrak_p'] = $itog_string['menu_zavtrak_p'] + round($menu_zavtrak['p'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['mg'],1) < $normativ['mg'] * 0.2){echo "bg-danger";}?>"><?= round($menu_zavtrak['mg'],1);$itog_string['menu_zavtrak_mg'] = $itog_string['menu_zavtrak_mg'] + round($menu_zavtrak['mg'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['fe'],1) < $normativ['fe'] * 0.2){echo "bg-danger";}?>"><?= round($menu_zavtrak['fe'],1);$itog_string['menu_zavtrak_fe'] = $itog_string['menu_zavtrak_fe'] + round($menu_zavtrak['fe'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['i'],1) < $normativ['i'] * 0.2){echo "bg-danger";}?>"><?= round($menu_zavtrak['i'],1);$itog_string['menu_zavtrak_i'] = $itog_string['menu_zavtrak_i'] + round($menu_zavtrak['i'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['se'],1) < $normativ['se'] * 0.2){echo "bg-danger";}?>"><?= round($menu_zavtrak['se'],1);$itog_string['menu_zavtrak_se'] = $itog_string['menu_zavtrak_se'] + round($menu_zavtrak['se'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['salt'],1) >= 1.25){echo "bg-danger";}?>"><?= round($menu_zavtrak['salt'],1);$itog_string['menu_zavtrak_salt'] = $itog_string['menu_zavtrak_salt'] + round($menu_zavtrak['salt'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['sahar'],1) >= 10){echo "bg-danger";}?>"><?= round($menu_zavtrak['sahar'],1);$itog_string['menu_zavtrak_sahar'] = $itog_string['menu_zavtrak_sahar'] + round($menu_zavtrak['sahar'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['kolbasa'],0)>0){echo "bg-danger";}?>"><?= round($menu_zavtrak['kolbasa'],0);$itog_string['menu_zavtrak_kolbasa_count'] = $itog_string['menu_zavtrak_kolbasa_count']+round($menu_zavtrak['kolbasa'],0);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['konditer'],0)>0){echo "bg-danger";}?>"><?=round($menu_zavtrak['konditer'],0);$itog_string['menu_zavtrak_konditer_count'] = $itog_string['menu_zavtrak_konditer_count']+round($menu_zavtrak['konditer'],0);?></td>
                                    <td class="text-center align-middle"><?= round($menu_zavtrak['frukti'] ,0);$itog_string['menu_zavtrak_frukti_count'] = $itog_string['menu_zavtrak_frukti_count']+round($menu_zavtrak['frukti'] ,0);?></td>
                                    <td class="text-center align-middle"><?= round($menu_zavtrak['yagoda'] ,0);$itog_string['menu_zavtrak_yagoda_count'] = $itog_string['menu_zavtrak_yagoda_count']+round($menu_zavtrak['yagoda'] ,0);?></td>
                                    <td class="text-center align-middle"><?= round($menu_zavtrak['med'] ,0);$itog_string['menu_zavtrak_med_count'] = $itog_string['menu_zavtrak_med_count']+round($menu_zavtrak['med'] ,0);?></td>
                                    <td class="text-center align-middle"><?= round($menu_zavtrak['ovoshi'] ,0);$itog_string['menu_zavtrak_ovoshi_count'] = $itog_string['menu_zavtrak_ovoshi_count']+round($menu_zavtrak['ovoshi'] ,0); ?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['kkal'],1) < 400){echo "bg-danger";}?>"><?if(round($menu_zavtrak['kkal'],1) < 400){echo "Есть"; $itog_string['deficit_blud_zavtrak'] = $itog_string['deficit_blud_zavtrak']+1;}else{ echo "Нет";}?></td>

                                <?}?>
                            <?}else{?>
                                <td colspan="21" class="text-center align-middle text-danger">Меню не внесено</td>
                            <?}?>


                            <td class="text-center align-middle"><?if (empty($menu_mas['obed']['itog'])){ echo 0; }else{ echo $menu_mas['obed']['itog'];$itog_string['vnes_obed_itog_count'] = $itog_string['vnes_obed_itog_count'] + $menu_mas['obed']['itog'];}?></td>
                            <td class="text-center align-middle"><?if (empty($menu_mas['obed'][3])){ echo 0; }else{ echo $menu_mas['obed'][3];$itog_string['vnes_obed_3_count'] = $itog_string['vnes_obed_3_count'] + $menu_mas['obed'][3];}?></td>
                            <td class="text-center align-middle <?if($cs_mas['sahar'] > 0 && empty($menu_mas['zavtrak'][5]) && empty($menu_mas['obed'][5])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][5])){ echo 0; }else{ echo $menu_mas['obed'][5];$itog_string['vnes_obed_5_count'] = $itog_string['vnes_obed_5_count'] + $menu_mas['obed'][5];}?></td>
                            <td class="text-center align-middle <?if($cs_mas['cialic'] > 0 && empty($menu_mas['zavtrak'][6]) && empty($menu_mas['obed'][6])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][6])){ echo 0; }else{ echo $menu_mas['obed'][6];$itog_string['vnes_obed_6_count'] = $itog_string['vnes_obed_6_count'] + $menu_mas['obed'][6];}?></td>
                            <td class="text-center align-middle <?if($cs_mas['mukovis'] > 0 && empty($menu_mas['zavtrak'][8]) && empty($menu_mas['obed'][8])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][8])){ echo 0; }else{ echo $menu_mas['obed'][8];$itog_string['vnes_obed_8_count'] = $itog_string['vnes_obed_8_count'] + $menu_mas['obed'][8];}?></td>
                            <td class="text-center align-middle <?if($cs_mas['fenilketon'] > 0 && empty($menu_mas['zavtrak'][7]) && empty($menu_mas['obed'][7])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][7])){ echo 0; }else{ echo $menu_mas['obed'][7];$itog_string['vnes_obed_7_count'] = $itog_string['vnes_obed_7_count'] + $menu_mas['obed'][7];}?></td>
                            <td class="text-center align-middle <?if($cs_mas['ovz'] > 0 && empty($menu_mas['zavtrak'][4]) && empty($menu_mas['obed'][4])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][4])){ echo 0; }else{ echo $menu_mas['obed'][4];$itog_string['vnes_obed_4_count'] = $itog_string['vnes_obed_4_count'] + $menu_mas['obed'][4];}?></td>
                            <td class="text-center align-middle <?if($cs_mas['allergy'] > 0 && empty($menu_mas['zavtrak'][10]) && empty($menu_mas['obed'][10])){echo "bg-danger";}?> "><?if (empty($menu_mas['obed'][10])){ echo 0; }else{ echo $menu_mas['obed'][10];$itog_string['vnes_obed_10_count'] = $itog_string['vnes_obed_10_count'] + $menu_mas['obed'][10];}?></td>
                            <?if($menu > 0){
                                if($menu_obed['yield'] == 0 || $menu_obed== "null"){?>
                                    <td colspan="21" class="text-center align-middle text-danger">Меню не внесено</td>
                                <?}else{?>
                                    <!--                <td class="text-center align-middle">--><?//= $menu_obed['min_yield']?><!--</td>-->
                                    <td class="text-center align-middle"><?= round($menu_obed['yield'],1); $itog_string['menu_obed_yield'] = $itog_string['menu_obed_yield'] + round($menu_obed['yield'],1);$itog_string['menu_obed_count'] = $itog_string['menu_obed_count'] + 1;?></td>
                                    <!--                <td class="text-center align-middle">--><?//= $menu_obed['max_yield']?><!--</td>-->

                                    <!--                <td class="text-center align-middle">--><?//= round($menu_obed['min_kkal'],1)?><!--</td>-->
                                    <td class="text-center align-middle <?if(round($menu_obed['kkal'],1) < 550){echo "bg-danger";}?>"><?= round($menu_obed['kkal'],1);$itog_string['menu_obed_kkal'] = $itog_string['menu_obed_kkal'] + round($menu_obed['kkal'],1);?></td>
                                    <!--                <td class="text-center align-middle">--><?//= round($menu_obed['max_kkal'],1)?><!--</td>-->
                                    <td class="text-center align-middle <?if(round($menu_obed['vitamin_c'],1) < $normativ['vitamin_c'] * 0.3){echo "bg-danger";}?>"><?= round($menu_obed['vitamin_c'],1);$itog_string['menu_obed_vitamin_c'] = $itog_string['menu_obed_vitamin_c'] + round($menu_obed['vitamin_c'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['vitamin_b1'],1)< $normativ['vitamin_b1'] * 0.3){echo "bg-danger";}?>"><?= round($menu_obed['vitamin_b1'],1);$itog_string['menu_obed_vitamin_b1'] = $itog_string['menu_obed_vitamin_b1'] + round($menu_obed['vitamin_b1'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['vitamin_b2'],1) < $normativ['vitamin_b2'] * 0.3){echo "bg-danger";}?>"><?= round($menu_obed['vitamin_b2'],1);$itog_string['menu_obed_vitamin_b2'] = $itog_string['menu_obed_vitamin_b2'] + round($menu_obed['vitamin_b2'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['vitamin_a'],1) < $normativ['vitamin_a'] * 0.3){echo "bg-danger";}?>"><?= round($menu_obed['vitamin_a'],1);$itog_string['menu_obed_vitamin_a'] = $itog_string['menu_obed_vitamin_a'] + round($menu_obed['vitamin_a'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['ca'],1) < $normativ['ca'] * 0.3){echo "bg-danger";}?>"><?= round($menu_obed['ca'],1);$itog_string['menu_obed_ca'] = $itog_string['menu_obed_ca'] + round($menu_obed['ca'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['p'],1) < $normativ['p'] * 0.3){echo "bg-danger";}?>"><?= round($menu_obed['p'],1);$itog_string['menu_obed_p'] = $itog_string['menu_obed_p'] + round($menu_obed['p'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['mg'],1) < $normativ['mg'] * 0.3){echo "bg-danger";}?>"><?= round($menu_obed['mg'],1);$itog_string['menu_obed_mg'] = $itog_string['menu_obed_mg'] + round($menu_obed['mg'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['fe'],1) < $normativ['fe'] * 0.3){echo "bg-danger";}?>"><?= round($menu_obed['fe'],1);$itog_string['menu_obed_fe'] = $itog_string['menu_obed_fe'] + round($menu_obed['fe'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['i'],1) < $normativ['i'] * 0.3){echo "bg-danger";}?>"><?= round($menu_obed['i'],1);$itog_string['menu_obed_i'] = $itog_string['menu_obed_i'] + round($menu_obed['i'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['se'],1) < $normativ['se'] * 0.3){echo "bg-danger";}?>"><?= round($menu_obed['se'],1);$itog_string['menu_obed_se'] = $itog_string['menu_obed_se'] + round($menu_obed['se'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['salt'],1) >= 1.5){echo "bg-danger";}?>"><?= round($menu_obed['salt'],1);$itog_string['menu_obed_salt'] = $itog_string['menu_obed_salt'] + round($menu_obed['salt'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['sahar'],1) >= 10){echo "bg-danger";}?>"><?= round($menu_obed['sahar'],1);$itog_string['menu_obed_sahar'] = $itog_string['menu_obed_sahar'] + round($menu_obed['sahar'],1);?></td>




                                    <td class="text-center align-middle <?if(round($menu_obed['kolbasa'],0)>0){echo "bg-danger";}?>"><?= round($menu_obed['kolbasa'],0);$itog_string['menu_obed_kolbasa_count'] = $itog_string['menu_obed_kolbasa_count']+round($menu_obed['kolbasa'],0);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['konditer'],0)>0){echo "bg-danger";}?>"><?=round($menu_obed['konditer'],0);$itog_string['menu_obed_konditer_count'] = $itog_string['menu_obed_konditer_count']+round($menu_obed['konditer'],0);?></td>
                                    <td class="text-center align-middle"><?= round($menu_obed['frukti'] ,0);$itog_string['menu_obed_frukti_count'] = $itog_string['menu_obed_frukti_count']+round($menu_obed['frukti'] ,0);?></td>
                                    <td class="text-center align-middle"><?= round($menu_obed['yagoda'] ,0);$itog_string['menu_obed_yagoda_count'] = $itog_string['menu_obed_yagoda_count']+round($menu_obed['yagoda'] ,0);?></td>
                                    <td class="text-center align-middle"><?= round($menu_obed['med'] ,0);$itog_string['menu_obed_med_count'] = $itog_string['menu_obed_med_count']+round($menu_obed['med'] ,0);?></td>
                                    <td class="text-center align-middle"><?= round($menu_obed['ovoshi'] ,0);$itog_string['menu_obed_ovoshi_count'] = $itog_string['menu_obed_ovoshi_count']+round($menu_obed['ovoshi'] ,0); ?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['kkal'],1) < 550){echo "bg-danger";}?>"><?if(round($menu_obed['kkal'],1) < 550){echo "Есть"; $itog_string['deficit_blud_obed'] = $itog_string['deficit_blud_obed']+1;}else{ echo "Нет";}?></td>
                                <?}?>
                            <?}?>
                            <?if($menu == 0){?>
                                <td colspan="21" class="text-center align-middle text-danger">Меню не внесено</td>
                            <?}?>


                            <?if(empty($characters_studies) || empty($cs_mas['count_kid_ochno'])){?>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>

                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>

                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                                <td class="text-center align-middle bg-secondary">нд</td>
                            <?}else{?>
                                <td class="text-center align-middle"><?=$cs_mas['count_kid'] - $cs_mas['otkaz'];$itog_string['shcolnik_vsego'] = $itog_string['shcolnik_vsego']+($cs_mas['count_kid'] - $cs_mas['otkaz']);$itog_string['shcolnik_vsego_count'] = $itog_string['shcolnik_vsego_count']+1;?></td>
                                <td class="text-center align-middle"><?if(empty($cs_mas['count_kid'])){echo 0;}else{round(($cs_mas['count_kid'] - $cs_mas['otkaz'])/($cs_mas['count_kid'])*100,0);$itog_string['shcolnik_vsego_procent'] = $itog_string['shcolnik_vsego_procent']+round(($cs_mas['count_kid'] - $cs_mas['otkaz'])/($cs_mas['count_kid'])*100,0);}?></td>
                                <?if(!empty($cs_mas['zavtrak'])){?>
                                    <td class="text-center align-middle"><?=$cs_mas['zavtrak'] - $cs_mas['otkaz_zavtrak'];$itog_string['shcolnik_zavtrak'] = $itog_string['shcolnik_zavtrak']+($cs_mas['zavtrak'] - $cs_mas['otkaz_zavtrak']);?></td>
                                    <td class="text-center align-middle"><?=round(($cs_mas['zavtrak'] - $cs_mas['otkaz_zavtrak'])/($cs_mas['zavtrak'])*100,0)?></td>
                                <?}else{?>
                                    <td class="text-center align-middle">0</td>
                                    <td class="text-center align-middle">0</td>
                                <?}?>
                                <?if(!empty($cs_mas['obed'])){?>
                                    <td class="text-center align-middle"><?=$cs_mas['obed'] - $cs_mas['otkaz_obed'];$itog_string['shcolnik_obed'] = $itog_string['shcolnik_obed']+($cs_mas['obed'] - $cs_mas['otkaz_obed']);?></td>
                                    <td class="text-center align-middle"><?=round(($cs_mas['obed'] - $cs_mas['otkaz_obed'])/($cs_mas['obed'])*100,0)?></td>
                                <?}else{?>
                                    <td class="text-center align-middle">0</td>
                                    <td class="text-center align-middle">0</td>
                                <?}?>
                                <?if(!empty($cs_mas['zavtrak_obed'])){?>
                                    <td class="text-center align-middle"><?=$cs_mas['zavtrak_obed'] - $cs_mas['otkaz_zavtrak_obed'];$itog_string['shcolnik_zavtrak_obed'] = $itog_string['shcolnik_zavtrak_obed']+($cs_mas['zavtrak_obed'] - $cs_mas['otkaz_zavtrak_obed']);?></td>
                                    <td class="text-center align-middle"><?=round(($cs_mas['zavtrak_obed'] - $cs_mas['otkaz_zavtrak_obed'])/($cs_mas['zavtrak_obed'])*100,0)?></td>
                                <?}else{?>
                                    <td class="text-center align-middle">0</td>
                                    <td class="text-center align-middle">0</td>
                                <?}?>
                                <!--                    треб/пит-->
                                <td class="text-center align-middle"><?if($cs_mas['sahar'] > 0){echo 1;}else{echo 0;} ?></td>
                                <td class="text-center align-middle"><?=$cs_mas['sahar']; $itog_string['shcolnik_sahar'] = $itog_string['shcolnik_sahar']+$cs_mas['sahar'];?></td>
                                <td class="text-center align-middle"><?if (empty($menu_mas['zavtrak'][5]) && empty($menu_mas['obed'][5])){ echo 0; }else{ echo 1; $itog_string['shcolnik_sahar_menu'] = $itog_string['shcolnik_sahar_menu']+1;}?></td>
                                <td class="text-center align-middle"><?=$cs_mas['sahar'] - $cs_mas['otkaz_sahar'];$itog_string['shcolnik_sahar_minus'] = $itog_string['shcolnik_sahar_minus']+($cs_mas['sahar'] - $cs_mas['otkaz_sahar']);?></td>
                                <td class="text-center align-middle"><?=$cs_mas['otkaz_sahar'];?></td>


                                <td class="text-center align-middle"><?if($cs_mas['cialic'] > 0){echo 1;}else{echo 0;} ?></td>
                                <td class="text-center align-middle"><?=$cs_mas['cialic']; $itog_string['shcolnik_cialic'] = $itog_string['shcolnik_cialic']+$cs_mas['cialic'];?></td>
                                <td class="text-center align-middle"><?if (empty($menu_mas['zavtrak'][6]) && empty($menu_mas['obed'][6])){ echo 0; }else{ echo 1; $itog_string['shcolnik_cialic_menu'] = $itog_string['shcolnik_cialic_menu']+1;}?></td>
                                <td class="text-center align-middle"><?=$cs_mas['cialic'] - $cs_mas['otkaz_cialic'];$itog_string['shcolnik_cialic_minus'] = $itog_string['shcolnik_cialic_minus']+($cs_mas['cialic'] - $cs_mas['otkaz_cialic']);?></td>
                                <td class="text-center align-middle"><?=$cs_mas['otkaz_cialic'];?></td>


                                <td class="text-center align-middle"><?if($cs_mas['mukovis'] > 0){echo 1;}else{echo 0;} ?></td>
                                <td class="text-center align-middle"><?=$cs_mas['mukovis']; $itog_string['shcolnik_mukovis'] = $itog_string['shcolnik_mukovis']+$cs_mas['mukovis'];?></td>
                                <td class="text-center align-middle"><?if (empty($menu_mas['zavtrak'][8]) && empty($menu_mas['obed'][8])){ echo 0; }else{ echo 1; $itog_string['shcolnik_mukovis_menu'] = $itog_string['shcolnik_mukovis_menu']+1;}?></td>
                                <td class="text-center align-middle"><?=$cs_mas['mukovis'] - $cs_mas['otkaz_mukovis'];$itog_string['shcolnik_mukovis_minus'] = $itog_string['shcolnik_mukovis_minus']+($cs_mas['mukovis'] - $cs_mas['otkaz_mukovis']);?></td>
                                <td class="text-center align-middle"><?=$cs_mas['otkaz_mukovis'];?></td>

                                <td class="text-center align-middle"><?if($cs_mas['fenilketon'] > 0){echo 1;}else{echo 0;} ?></td>
                                <td class="text-center align-middle"><?=$cs_mas['fenilketon']; $itog_string['shcolnik_fenilketon'] = $itog_string['shcolnik_fenilketon']+$cs_mas['fenilketon'];?></td>
                                <td class="text-center align-middle"><?if (empty($menu_mas['zavtrak'][7]) && empty($menu_mas['obed'][7])){ echo 0; }else{ echo 1; $itog_string['shcolnik_fenilketon_menu'] = $itog_string['shcolnik_fenilketon_menu']+1;}?></td>
                                <td class="text-center align-middle"><?=$cs_mas['fenilketon'] - $cs_mas['otkaz_fenilketon'];$itog_string['shcolnik_fenilketon_minus'] = $itog_string['shcolnik_fenilketon_minus']+($cs_mas['fenilketon'] - $cs_mas['otkaz_fenilketon']);?></td>
                                <td class="text-center align-middle"><?=$cs_mas['otkaz_fenilketon'];?></td>

                                <td class="text-center align-middle"><?if($cs_mas['ovz'] > 0){echo 1;}else{echo 0;} ?></td>
                                <td class="text-center align-middle"><?=$cs_mas['ovz']; $itog_string['shcolnik_ovz'] = $itog_string['shcolnik_ovz']+$cs_mas['ovz'];?></td>
                                <td class="text-center align-middle"><?if (empty($menu_mas['zavtrak'][4]) && empty($menu_mas['obed'][4])){ echo 0; }else{ echo 1; $itog_string['shcolnik_ovz_menu'] = $itog_string['shcolnik_ovz_menu']+1;}?></td>
                                <td class="text-center align-middle"><?=$cs_mas['ovz'] - $cs_mas['otkaz_ovz'];$itog_string['shcolnik_ovz_minus'] = $itog_string['shcolnik_ovz_minus']+($cs_mas['ovz'] - $cs_mas['otkaz_ovz']);?></td></td>
                                <td class="text-center align-middle"><?=$cs_mas['otkaz_ovz'];?></td>

                                <td class="text-center align-middle"><?if($cs_mas['allergy'] > 0){echo 1;}else{echo 0;} ?></td>
                                <td class="text-center align-middle"><?=$cs_mas['allergy']; $itog_string['shcolnik_allergy'] = $itog_string['shcolnik_allergy']+$cs_mas['allergy'];?></td>
                                <td class="text-center align-middle"><?if (empty($menu_mas['zavtrak'][10])){ echo 0; }else{ echo 1; $itog_string['shcolnik_allergy_menu'] = $itog_string['shcolnik_allergy_menu']+1;}?></td>
                                <td class="text-center align-middle"><?=$cs_mas['allergy'] - $cs_mas['otkaz_allergy'];$itog_string['shcolnik_allergy_minus'] = $itog_string['shcolnik_allergy_minus']+($cs_mas['allergy'] - $cs_mas['otkaz_allergy']);?></td>
                                <td class="text-center align-middle"><?=$cs_mas['otkaz_allergy'];?></td>
                            <?}?>


                            <?
                            $control_zavtrak = $menus_dishes_model->get_control_information($organization->id, 1);
                            $control_obed = $menus_dishes_model->get_control_information($organization->id, 3);
                            ?>
                            <?if($control_zavtrak == 'null' || empty($control_zavtrak) || $control_zavtrak['vnutr'] == 0){?>
                                <td colspan="7" class="text-center align-middle text-danger">Контроль не проводился</td>
                            <?}else{
                                $itog_string['zavtrak_control_count'] ++;?>
                                <td class="text-center align-middle"><?=$control_zavtrak['vnutr']; $itog_string['zavtrak_vnutr'] = $itog_string['zavtrak_vnutr'] + $control_zavtrak['vnutr']; ?></td>
                                <td class="text-center align-middle"><?=round($control_zavtrak['min_ball'],1); if(empty($itog_string['zavtrak_min_ball'])){$itog_string['zavtrak_min_ball'] = round($control_zavtrak['min_ball'],1);}elseif ($itog_string['zavtrak_min_ball'] > round($control_zavtrak['min_ball'],1)){$itog_string['zavtrak_min_ball'] = round($control_zavtrak['min_ball'],1);}?></td>
                                <td class="text-center align-middle"><?=round($control_zavtrak['sred_ball'],1); $itog_string['zavtrak_sred_ball'] = $itog_string['zavtrak_sred_ball'] + round($control_zavtrak['sred_ball'],1);?></td>
                                <td class="text-center align-middle"><?=round($control_zavtrak['max_ball'],1); if(empty($itog_string['zavtrak_max_ball'])){$itog_string['zavtrak_max_ball'] = round($control_zavtrak['max_ball'],1);}elseif ($itog_string['zavtrak_max_ball'] < round($control_zavtrak['max_ball'],1)){$itog_string['zavtrak_max_ball'] = round($control_zavtrak['max_ball'],1);}?></td>
                                <td class="text-center align-middle"><?=round($control_zavtrak['min_procent'],1);if(empty($itog_string['zavtrak_min_procent'])){$itog_string['zavtrak_min_procent'] = round($control_zavtrak['min_procent'],1);}elseif ($itog_string['zavtrak_min_procent'] > round($control_zavtrak['min_procent'],1)){$itog_string['zavtrak_min_procent'] = round($control_zavtrak['min_procent'],1);}?></td>
                                <td class="text-center align-middle <?if(round($control_zavtrak['sred_procent'],1) >= 30){echo "bg-danger";}?>"><?=round($control_zavtrak['sred_procent'],1); $itog_string['zavtrak_sred_procent'] = $itog_string['zavtrak_sred_procent'] +round($control_zavtrak['sred_procent'],1);?></td>
                                <td class="text-center align-middle <?if(round($control_zavtrak['max_procent'],1) >= 30){echo "bg-danger";}?>"><?=round($control_zavtrak['max_procent'],1);if(empty($itog_string['zavtrak_max_procent'])){$itog_string['zavtrak_max_procent'] = round($control_zavtrak['max_procent'],1);}elseif ($itog_string['zavtrak_max_procent'] < round($control_zavtrak['max_procent'],1)){$itog_string['zavtrak_max_procent'] = round($control_zavtrak['max_procent'],1);}?></td>
                            <?}?>

                            <?if($control_obed == 'null' || empty($control_obed) || $control_obed['vnutr'] == 0){?>
                                <td colspan="7" class="text-center align-middle text-danger">Контроль не проводился</td>
                            <?}else{
                                $itog_string['obed_control_count'] ++;?>
                                <td class="text-center align-middle"><?=$control_obed['vnutr']; $itog_string['obed_vnutr'] = $itog_string['obed_vnutr'] + $control_obed['vnutr'];?></td>
                                <td class="text-center align-middle"><?=round($control_obed['min_ball'],1); if(empty($itog_string['obed_min_ball'])){$itog_string['obed_min_ball'] = round($control_obed['min_ball'],1);}elseif ($itog_string['obed_min_ball'] > round($control_obed['min_ball'],1)){$itog_string['obed_min_ball'] = round($control_obed['min_ball'],1);}?></td>
                                <td class="text-center align-middle"><?=round($control_obed['sred_ball'],1); $itog_string['obed_sred_ball'] = $itog_string['obed_sred_ball'] +round($control_obed['sred_ball'],1);?></td>
                                <td class="text-center align-middle"><?=round($control_obed['max_ball'],1); if(empty($itog_string['obed_max_ball'])){$itog_string['obed_max_ball'] = round($control_obed['max_ball'],1);}elseif ($itog_string['obed_max_ball'] < round($control_obed['min_ball'],1)){$itog_string['obed_max_ball'] = round($control_obed['max_ball'],1);}?></td>
                                <td class="text-center align-middle"><?=round($control_obed['min_procent'],1); if(empty($itog_string['obed_min_procent'])){$itog_string['obed_min_procent'] = round($control_obed['min_procent'],1);}elseif ($itog_string['obed_min_procent'] > round($control_obed['min_procent'],1)){$itog_string['obed_min_procent'] = round($control_obed['min_procent'],1);}?></td>
                                <td class="text-center align-middle <?if(round($control_obed['sred_procent'],1) >= 30){echo "bg-danger";}?>"><?=round($control_obed['sred_procent'],1); $itog_string['obed_sred_procent'] = $itog_string['obed_sred_procent'] +round($control_obed['sred_procent'],1);?></td>
                                <td class="text-center align-middle <?if(round($control_obed['max_procent'],1) >= 30){echo "bg-danger";}?>"><?=round($control_obed['max_procent'],1); if(empty($itog_string['obed_max_procent'])){$itog_string['obed_max_procent'] = round($control_obed['max_procent'],1);}elseif ($itog_string['obed_max_procent'] < round($control_obed['max_procent'],1)){$itog_string['obed_max_procent'] = round($control_obed['max_procent'],1);}?></td>
                            <?}?>



                        </tr>
                    <?}?>


                    <tr class="table-success">
                        <td class="" colspan="3">Нормативы:</td>
                        <td class="">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>

                        <td class="text-center align-middle"><20</td>
                        <td class="text-center align-middle">0</td>

                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>

                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">>400</td>
                        <td class="text-center align-middle">><?=$normativ['vitamin_c'] *0.2?></td>
                        <td class="text-center align-middle">><?=$normativ['vitamin_b1']*0.2?></td>
                        <td class="text-center align-middle">><?=$normativ['vitamin_b2']*0.2?></td>
                        <td class="text-center align-middle">><?=$normativ['vitamin_a'] *0.2?></td>
                        <td class="text-center align-middle">><?=$normativ['ca']*0.2?></td>
                        <td class="text-center align-middle">><?=$normativ['p'] *0.2?></td>
                        <td class="text-center align-middle">><?=$normativ['mg']*0.2?></td>
                        <td class="text-center align-middle">><?=$normativ['fe'] *0.2?></td>
                        <td class="text-center align-middle">><?=$normativ['i'] *0.2?></td>
                        <td class="text-center align-middle">><?=$normativ['se']*0.2?></td>
                        <td class="text-center align-middle"><1.25</td>
                        <td class="text-center align-middle"><10</td>

                        <td class="text-center align-middle">0</td>
                        <td class="text-center align-middle">0</td>
                        <td class="text-center align-middle">>0</td>
                        <td class="text-center align-middle">>0</td>
                        <td class="text-center align-middle">>0</td>
                        <td class="text-center align-middle">>0</td>
                        <td class="text-center align-middle">0</td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">>550</td>
                        <td class="text-center align-middle">><?=$normativ['vitamin_c'] *0.3?></td>
                        <td class="text-center align-middle">><?=$normativ['vitamin_b1']*0.3?></td>
                        <td class="text-center align-middle">><?=$normativ['vitamin_b2']*0.3?></td>
                        <td class="text-center align-middle">><?=$normativ['vitamin_a'] *0.3?></td>
                        <td class="text-center align-middle">><?=$normativ['ca']*0.3?></td>
                        <td class="text-center align-middle">><?=$normativ['p'] *0.3?></td>
                        <td class="text-center align-middle">><?=$normativ['mg']*0.3?></td>
                        <td class="text-center align-middle">><?=$normativ['fe'] *0.3?></td>
                        <td class="text-center align-middle">><?=$normativ['i'] *0.3?></td>
                        <td class="text-center align-middle">><?=$normativ['se']*0.3?></td>
                        <td class="text-center align-middle"><1.5</td>
                        <td class="text-center align-middle"><10</td>


                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>

                        <td class="text-center align-middle">0</td>
                        <td class="text-center align-middle">0</td>
                        <td class="text-center align-middle">>0</td>
                        <td class="text-center align-middle">>0</td>
                        <td class="text-center align-middle">>0</td>
                        <td class="text-center align-middle">>0</td>
                        <td class="text-center align-middle">0</td>
                        <td class="text-center align-middle"></td>

                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>

                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>

                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>

                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>

                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>

                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">40</td>
                        <td class="text-center align-middle"><30%</td>
                        <td class="text-center align-middle"><30%</td>
                        <td class="text-center align-middle"><30%</td>

                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">-</td>
                        <td class="text-center align-middle">40</td>
                        <td class="text-center align-middle"><30%</td>
                        <td class="text-center align-middle"><30%</td>
                        <td class="text-center align-middle"><30%</td>
                    </tr>


                    </tbody>
                </table><br><br><br>

    </div>

<?

$script = <<< JS

$( ".beforeload" ).click(function() {
  $(".beforeload").css('display','none');
  $(".load").css('display','block');
  
});


/*$( ".beforeload" ).click(function() {
  $('.beforeload').append('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
});*/






function FixTable(table) {
   var inst = this;
   this.table  = table;
 
   $('thead > tr > th',$(this.table)).each(function(index) {
      var div_fixed = $('<div/>').addClass('fixtable-fixed');
      var div_relat = $('<div/>').addClass('fixtable-relative');
      div_fixed.html($(this).html());
      div_relat.html($(this).html());
      $(this).html('').append(div_fixed).append(div_relat);
      $(div_fixed).hide();
   });
   
 
   this.StyleColumns();
   this.FixColumns();
 
   $(window).scroll(function(){
      inst.FixColumns()
   }).resize(function(){
      inst.StyleColumns()
   });
}
 
FixTable.prototype.StyleColumns = function() {
   var inst = this;
   $('tr > th', $(this.table)).each(function(){
      var div_relat = $('div.fixtable-relative', $(this));
      var th = $(div_relat).parent('th');
      $('div.fixtable-fixed', $(this)).css({
         'width': $(th).outerWidth(true) - parseInt($(th).css('border-left-width')) + 'px',
         'height': $(th).outerHeight(true) + 'px',
         'left': $(div_relat).offset().left - parseInt($(th).css('padding-left')) + 'px',
         'padding-top': $(div_relat).offset().top - $(inst.table).offset().top + 'px',
         'padding-left': $(th).css('padding-left'),
         'padding-right': $(th).css('padding-right')
      });
   });
}
 
FixTable.prototype.FixColumns = function() {
   var inst = this;
   var show = false;
   var s_top = $(window).scrollTop();
   var h_top = $(inst.table).offset().top;
 
   if (s_top < (h_top + $(inst.table).height() - $(inst.table).find('.fixtable-fixed').outerHeight()) && s_top > h_top) {
      show = true;
   }
 
   $('tr > th > div.fixtable-fixed', $(this.table)).each(function(){
      show ? $(this).show() : $(this).hide()
   });
}
 
$(document).ready(function(){
   $('.fixtable').each(function() {
      new FixTable(this);
   });
});
JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>