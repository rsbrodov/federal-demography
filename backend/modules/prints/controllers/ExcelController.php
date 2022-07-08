<?php

namespace backend\modules\prints\controllers;

use common\models\AgeInfo;
use common\models\Days;
use common\models\DishesProducts;
use common\models\FeedersCharacters;
use common\models\Menus;
use common\models\MenusDays;
use common\models\MenusDishes;
use common\models\MenusNutrition;
use common\models\NutritionInfo;
use common\models\Organization;
use common\models\Products;
use common\models\ProductsCategory;
use common\models\ProductsChangeOrganization;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Default controller for the `print` module
 */
class ExcelController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionMenusDaysExportExcel($menu_id, $cycle, $days_id)
    {
        require_once Yii::$app->basePath . '/Excel/PHPExcel.php';
        require_once Yii::$app->basePath . '/Excel/PHPExcel/IOFactory.php';

        $document = new \PHPExcel();
        $model = new MenusDishes();

        ob_start();
        $my_menus = Menus::findOne($menu_id);
        $menus_nutrition_id = MenusNutrition::find()->where(['menu_id' => $menu_id])->all();//ВСЕ ПРИЕМЫ ПИЩИ КОНКРЕТНОГО МЕНЮ
        $ids = [];

        foreach ($menus_nutrition_id as $m_id)
        {
            $ids[] = $m_id->nutrition_id;//МАССИВ ID ПРИЕМОВ ПИЩИ КЛНКРЕЬНОГО МЕНЮ
        }
        $nutritions = NutritionInfo::find()->where(['id' => $ids])->all();//ОБЪЕКТ С НАБОРОМ ПРИЕМОВ ПИЩИ ИЗ БАЗЫ С УЧЕТОМ НАШИХ АЙДИ
        $menus_dishes = MenusDishes::find()->
        select(['menus_dishes.id as id', 'menus_dishes.date_fact_menu as date_fact_menu', 'menus_dishes.menu_id as menu_id', 'menus_dishes.cycle as cycle', 'menus_dishes.days_id as days_id', 'menus_dishes.nutrition_id as nutrition_id', 'menus_dishes.dishes_id as dishes_id', 'menus_dishes.yield as yield', 'dishes_category.sort as sort'])->
        leftJoin('dishes', 'menus_dishes.dishes_id = dishes.id')->
        leftJoin('dishes_category', 'dishes.dishes_category_id = dishes_category.id')->
        where(['date_fact_menu' => 0, 'menu_id' => $menu_id, 'cycle' => $cycle, 'days_id' => $days_id])->
        orderby(['cycle' => SORT_ASC, 'days_id' => SORT_ASC, 'nutrition_id' => SORT_ASC, 'sort' => SORT_ASC])->
        all();

        $num = 9;
        $sheet = $document->getActiveSheet();
        $num_st = 7;
        $this->layout = false;

        $sheet->getColumnDimension('A')->setWidth("30");
        $sheet->getColumnDimension('B')->setWidth("50");
        $sheet->getColumnDimension('G')->setWidth("30");

        $sheet->getStyle("B1")->getFont()->setBold(true);
        $sheet->getStyle("B2")->getFont()->setBold(true);
        $sheet->getStyle("B3")->getFont()->setBold(true);
        $sheet->getStyle("B4")->getFont()->setBold(true);
        $sheet->getStyle("B5")->getFont()->setBold(true);
        $sheet->getStyle("A7:V7")->getFont()->setBold(true);
        $sheet->getStyle("A8:V8")->getFont()->setBold(true);

        $sheet->setCellValue('B' . 1, 'Организация: '.Organization::findOne($my_menus->organization_id)->title);
        $sheet->setCellValue('B' . 2, 'Название меню: '.$my_menus->name);
        $sheet->setCellValue('B' . 3, 'Возрастная категория: '.AgeInfo::findOne($my_menus->age_info_id)->name);
        $sheet->setCellValue('B' . 4, 'Характеристика питающихся: '.FeedersCharacters::findOne($my_menus->age_info_id)->name);
        $sheet->setCellValue('B' . 5, 'Срок действия меню: '.date('d.m.Y', $my_menus->date_start).' - '.date('d.m.Y', $my_menus->date_end));

        $sheet->setCellValue('A' . $num_st, "№ рецептуры");
        $sheet->setCellValue('B' . $num_st, "Название блюда");
        $sheet->setCellValue('C' . $num_st, "Масса");
        $sheet->setCellValue('D' . $num_st, "Белки");
        $sheet->setCellValue('E' . $num_st, "Жиры");
        $sheet->setCellValue('F' . $num_st, "Углеводы");
        $sheet->setCellValue('G' . $num_st, "Энергетическая ценность");

        $sheet->setCellValue('H' . $num_st, "B1");
        $sheet->setCellValue('I' . $num_st, "B2");
        $sheet->setCellValue('J' . $num_st, "A");
        $sheet->setCellValue('K' . $num_st, "D");
        $sheet->setCellValue('L' . $num_st, "C");
        $sheet->setCellValue('N' . $num_st, "Na");
        $sheet->setCellValue('O' . $num_st, "K");
        $sheet->setCellValue('P' . $num_st, "Ca");
        $sheet->setCellValue('Q' . $num_st, "Mg");
        $sheet->setCellValue('R' . $num_st, "P");
        $sheet->setCellValue('S' . $num_st, "Fe");
        $sheet->setCellValue('T' . $num_st, "I");
        $sheet->setCellValue('U' . $num_st, "Se");
        $sheet->setCellValue('V' . $num_st, "F");

        $num_st++;
        $sheet->setCellValue('C' . $num_st, "г");
        $sheet->setCellValue('D' . $num_st, "г");
        $sheet->setCellValue('E' . $num_st, "г");
        $sheet->setCellValue('F' . $num_st, "г");
        $sheet->setCellValue('G' . $num_st, "ккал");

        $sheet->setCellValue('H' . $num_st, "мг");
        $sheet->setCellValue('I' . $num_st, "мг");
        $sheet->setCellValue('J' . $num_st, "мкг рет.экв");
        $sheet->setCellValue('K' . $num_st, "мкг");
        $sheet->setCellValue('L' . $num_st, "мг");
        $sheet->setCellValue('N' . $num_st, "мг");
        $sheet->setCellValue('O' . $num_st, "мг");
        $sheet->setCellValue('P' . $num_st, "мг");
        $sheet->setCellValue('Q' . $num_st, "мг");
        $sheet->setCellValue('R' . $num_st, "мг");
        $sheet->setCellValue('S' . $num_st, "мг");
        $sheet->setCellValue('T' . $num_st, "мкг");
        $sheet->setCellValue('U' . $num_st, "мкг");
        $sheet->setCellValue('V' . $num_st, "мкг");

        $data = [];

        $sheet->setCellValue('B' . $num, Days::findOne($days_id)->name . ', ' . $cycle . ' неделя');
        $sheet->getStyle("B" . $num)->getFont()->setBold(true);
        $sheet->getStyle("B" . $num)->getFont()->getColor()->setRGB('3d30cf');
        $num = $num + 1;

        $super_total_yield = 0;
        $super_total_protein = 0;
        $super_total_fat = 0;
        $super_total_carbohydrates_total = 0;
        $super_total_energy_kkal = 0;

        //vitamins
        $super_total_vitamin_a = 0; $super_total_vitamin_c = 0; $super_total_vitamin_b1 = 0; $super_total_vitamin_b2 = 0; $super_total_vitamin_d = 0; $super_total_vitamin_pp = 0; $super_total_na = 0; $super_total_k = 0; $super_total_ca = 0; $super_total_f = 0; $super_total_p = 0; $super_total_i = 0; $super_total_mg = 0; $super_total_fe = 0;$super_total_se = 0;
        //end vitamins

        foreach ($nutritions as $nutrition)
        {
            $energy_kkal = 0;
            $protein = 0;
            $fat = 0;
            $yield = 0;
            $carbohydrates_total = 0;
            $sheet->setCellValue('B' . $num, $nutrition->name);
            $sheet->getStyle("B" . $num)->getFont()->setBold(true);
            $num = $num + 1;
            $vitamins = []; unset($vitamins); $vitamin_a = 0; $vitamin_c = 0; $vitamin_b1 = 0; $vitamin_b2 = 0; $vitamin_d = 0; $vitamin_pp = 0; $na = 0; $k = 0; $ca = 0; $f = 0; $p = 0; $se = 0; $i = 0; $mg = 0; $fe = 0;

            foreach ($menus_dishes as $key => $m_dish)
            {
                if ($nutrition->id == $m_dish->nutrition_id)
                {
                    $protein_dish = round($m_dish->get_bju_dish($m_dish->id, 'protein'), 1);
                    $protein = $protein_dish + $protein;
                    $fat_dish = round($m_dish->get_bju_dish($m_dish->id, 'fat'), 1);
                    $fat = $fat_dish + $fat;
                    $carbohydrates_total_dish = round($m_dish->get_bju_dish($m_dish->id, 'carbohydrates_total'), 1);
                    $carbohydrates_total = $carbohydrates_total_dish + $carbohydrates_total;
                    $kkal = round($m_dish->get_kkal_dish($m_dish->id), 1);
                    $energy_kkal = $energy_kkal + $kkal;
                    $yield = $yield + $m_dish->yield;

                    //РАСЧЕТ ВИТАМИНА
                    $vitamins['vitamin_a'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'vitamin_a'), 2);
                    $vitamin_a = $vitamin_a + $vitamins['vitamin_a'];
                    $vitamins['vitamin_c'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'vitamin_c'), 2);
                    $vitamin_c = $vitamin_c + $vitamins['vitamin_c'];
                    $vitamins['vitamin_b1'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'vitamin_b1'), 2);
                    $vitamin_b1 = $vitamin_b1 + $vitamins['vitamin_b1'];
                    $vitamins['vitamin_b2'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'vitamin_b2'), 2);
                    $vitamin_b2 = $vitamin_b2 + $vitamins['vitamin_b2'];
                    $vitamins['vitamin_d'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'vitamin_d'), 2);
                    $vitamin_d = $vitamin_d + $vitamins['vitamin_d'];
                    $vitamins['vitamin_pp'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'vitamin_pp'), 2);
                    $vitamin_pp = $vitamin_pp + $vitamins['vitamin_pp'];
                    $vitamins['na'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'na'), 2);
                    $na = $na + $vitamins['na'];
                    $vitamins['k'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'k'), 2);
                    $k = $k + $vitamins['k'];
                    $vitamins['ca'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'ca'), 2);
                    $ca = $ca + $vitamins['ca'];
                    $vitamins['f'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'f'), 2);
                    $f = $f + $vitamins['f'];
                    $vitamins['mg'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'mg'), 2);
                    $mg = $mg + $vitamins['mg'];
                    $vitamins['p'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'p'), 2);
                    $p = $p + $vitamins['p'];
                    $vitamins['fe'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'fe'), 2);
                    $fe = $fe + $vitamins['fe'];
                    $vitamins['i'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'i'), 2);
                    $i = $i + $vitamins['i'];
                    $vitamins['se'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'se'), 2);
                    $se = $se + $vitamins['se'];
                    //КОНЕЦ РАСЧЕТА

                    $sheet->setCellValue('A' . $num, $m_dish->get_techmup($m_dish->dishes_id));
                    $sheet->setCellValue('B' . $num, $m_dish->get_dishes($m_dish->dishes_id));
                    $sheet->setCellValue('C' . $num, $m_dish->yield);
                    $sheet->setCellValue('D' . $num, $protein_dish);
                    $sheet->setCellValue('E' . $num, $fat_dish);
                    $sheet->setCellValue('F' . $num, $carbohydrates_total_dish);
                    $sheet->setCellValue('G' . $num, $kkal);

                    $sheet->setCellValue('H' . $num, round($vitamins['vitamin_b1'], 2));
                    $sheet->setCellValue('I' . $num, round($vitamins['vitamin_b2'], 2));
                    $sheet->setCellValue('J' . $num, round($vitamins['vitamin_a'], 2));
                    $sheet->setCellValue('K' . $num, round($vitamins['vitamin_d'], 2));
                    $sheet->setCellValue('L' . $num, round($vitamins['vitamin_c'], 2));
                    $sheet->setCellValue('N' . $num, round($vitamins['na'], 2));
                    $sheet->setCellValue('O' . $num, round($vitamins['k'], 2));
                    $sheet->setCellValue('P' . $num, round($vitamins['ca'], 2));
                    $sheet->setCellValue('Q' . $num, round($vitamins['mg'], 2));
                    $sheet->setCellValue('R' . $num, round($vitamins['p'], 2));
                    $sheet->setCellValue('S' . $num, round($vitamins['fe'], 2));
                    $sheet->setCellValue('T' . $num, round($vitamins['i'], 2));
                    $sheet->setCellValue('U' . $num, round($vitamins['se'], 2));
                    $sheet->setCellValue('V' . $num, round($vitamins['f'], 2));
                    unset($menus_dishes[$key]);
                    $num = $num + 1;
                }
            }

            $sheet->setCellValue('B' . $num, 'Итого за ' . $nutrition->name);
            $data[$nutrition->id]['yield'] = $data[$nutrition->id]['yield'] + $yield;
            $super_total_yield = $super_total_yield + $yield;
            $data[$nutrition->id]['protein'] = $data[$nutrition->id]['protein'] + $protein;
            $super_total_protein = $super_total_protein + $protein;
            $data[$nutrition->id]['fat'] = $data[$nutrition->id]['fat'] + $fat;
            $super_total_fat = $super_total_fat + $fat;
            $data[$nutrition->id]['carbohydrates_total'] = $data[$nutrition->id]['carbohydrates_total'] + $carbohydrates_total;
            $super_total_carbohydrates_total = $super_total_carbohydrates_total + $carbohydrates_total;
            $data[$nutrition->id]['energy_kkal'] = $data[$nutrition->id]['energy_kkal'] + $energy_kkal;
            $super_total_energy_kkal = $super_total_energy_kkal + $energy_kkal;

            //РАСЧЕТ ВИТАМИНОВ
            $data[$nutrition->id]['vitamin_a'] = $data[$nutrition->id]['vitamin_a'] + $vitamin_a;
            $data[$nutrition->id]['vitamin_c'] = $data[$nutrition->id]['vitamin_c'] + $vitamin_c;
            $data[$nutrition->id]['vitamin_b1'] = $data[$nutrition->id]['vitamin_b1'] + $vitamin_b1;
            $data[$nutrition->id]['vitamin_b2'] = $data[$nutrition->id]['vitamin_b2'] + $vitamin_b2;
            $data[$nutrition->id]['vitamin_d'] = $data[$nutrition->id]['vitamin_d'] + $vitamin_d;
            $data[$nutrition->id]['vitamin_pp'] = $data[$nutrition->id]['vitamin_pp'] + $vitamin_pp;
            $data[$nutrition->id]['vitamin_na'] = $data[$nutrition->id]['vitamin_na'] + $na;
            $data[$nutrition->id]['vitamin_k'] = $data[$nutrition->id]['vitamin_k'] + $k;
            $data[$nutrition->id]['vitamin_ca'] = $data[$nutrition->id]['vitamin_ca'] + $ca;
            $data[$nutrition->id]['vitamin_f'] = $data[$nutrition->id]['vitamin_f'] + $f;
            $data[$nutrition->id]['vitamin_mg'] = $data[$nutrition->id]['vitamin_mg'] + $mg;
            $data[$nutrition->id]['vitamin_p'] = $data[$nutrition->id]['vitamin_p'] + $p;
            $data[$nutrition->id]['vitamin_fe'] = $data[$nutrition->id]['vitamin_fe'] + $fe;
            $data[$nutrition->id]['vitamin_i'] = $data[$nutrition->id]['vitamin_i'] + $i;
            $data[$nutrition->id]['vitamin_se'] = $data[$nutrition->id]['vitamin_se'] + $se;

            //raschet v itog za den
            $super_total_vitamin_a = $super_total_vitamin_a + $vitamin_a;
            $super_total_vitamin_c = $super_total_vitamin_c + $vitamin_c;
            $super_total_vitamin_b1 = $super_total_vitamin_b1 + $vitamin_b1;
            $super_total_vitamin_b2 = $super_total_vitamin_b2 + $vitamin_b2;
            $super_total_vitamin_d = $super_total_vitamin_d + $vitamin_d;
            $super_total_vitamin_pp = $super_total_vitamin_pp + $vitamin_pp ;
            $super_total_na = $super_total_na + $na;
            $super_total_k  = $super_total_k  + $k;
            $super_total_ca = $super_total_ca + $ca ;
            $super_total_f  = $super_total_f  + $f;
            $super_total_mg = $super_total_mg + $mg;
            $super_total_p = $super_total_p + $p;
            $super_total_fe = $super_total_fe + $fe;
            $super_total_i = $super_total_i + $i;
            $super_total_se = $super_total_se + $se;
            //КОНЕЦ РАСЧЕТА

            //итого за завтрак
            $sheet->setCellValue('C' . $num, $yield);
            $sheet->setCellValue('D' . $num, $protein);
            $sheet->setCellValue('E' . $num, $fat);
            $sheet->setCellValue('F' . $num, $carbohydrates_total);
            $sheet->setCellValue('G' . $num, $energy_kkal);

            $sheet->setCellValue('H' . $num, round($vitamin_b1, 2));
            $sheet->setCellValue('I' . $num, round($vitamin_b2, 2));
            $sheet->setCellValue('J' . $num, round($vitamin_a, 2));
            $sheet->setCellValue('K' . $num, round($vitamin_d, 2));
            $sheet->setCellValue('L' . $num, round($vitamin_c, 2));
            $sheet->setCellValue('N' . $num, round($na, 2));
            $sheet->setCellValue('O' . $num, round($k, 2));
            $sheet->setCellValue('P' . $num, round($ca, 2));
            $sheet->setCellValue('Q' . $num, round($mg, 2));
            $sheet->setCellValue('R' . $num, round($p, 2));
            $sheet->setCellValue('S' . $num, round($fe, 2));
            $sheet->setCellValue('T' . $num, round($i, 2));
            $sheet->setCellValue('U' . $num, round($se, 2));
            $sheet->setCellValue('V' . $num, round($f, 2));

            $sheet->getStyle("B" . $num . ":V" . $num)->getFont()->setBold(true);
            $num = $num + 1;

            $normativ = $model->get_recommended_normativ_new($menu_id, $nutrition->id);
            $sheet->setCellValue('B' . $num, 'Рекомендуемая величина');
            $sheet->setCellValue('D' . $num, '-');
            $sheet->setCellValue('D' . $num, $normativ['protein']);
            $sheet->setCellValue('E' . $num, $normativ['fat']);
            $sheet->setCellValue('F' . $num, $normativ['carbohydrates']);
            $sheet->setCellValue('G' . $num, $normativ['kkal']);
            $sheet->getStyle("B" . $num . ":V" . $num)->getFont()->setBold(true);
            $sheet->getStyle("B" . $num . ":V" . $num)->getFont()->getColor()->setRGB('008c1e');
            $num = $num + 1;

            $normativ = $model->get_recommended_normativ_new($menu_id, $nutrition->id);
            $sheet->setCellValue('B' . $num, 'Процент от общей массы пищевых веществ');
            $sheet->setCellValue('D' . $num, $model->get_procent($menu_id, $cycle, $days_id, $nutrition->id, 'protein'));
            $sheet->setCellValue('E' . $num, $model->get_procent($menu_id, $cycle, $days_id, $nutrition->id, 'fat'));
            $sheet->setCellValue('F' . $num, $model->get_procent($menu_id, $cycle, $days_id, $nutrition->id, 'carbohydrates_total'));
            $sheet->getStyle("B" . $num . ":V" . $num)->getFont()->setBold(true);
            $sheet->getStyle("B" . $num . ":V" . $num)->getFont()->getColor()->setRGB('b59700');
            $num = $num + 1;
        }
        $sheet->setCellValue('B' . $num, 'Итого за день');
        $sheet->setCellValue('C' . $num, $super_total_yield);
        $sheet->setCellValue('D' . $num, $super_total_protein);
        $sheet->setCellValue('E' . $num, $super_total_fat);
        $sheet->setCellValue('F' . $num, $super_total_carbohydrates_total);
        $sheet->setCellValue('G' . $num, $super_total_energy_kkal);

        $sheet->setCellValue('H' . $num, round($super_total_vitamin_b1,  2));
        $sheet->setCellValue('I' . $num, round($super_total_vitamin_b2,  2));
        $sheet->setCellValue('J' . $num, round($super_total_vitamin_a, 2));
        $sheet->setCellValue('K' . $num, round($super_total_vitamin_d, 2));
        $sheet->setCellValue('L' . $num, round($super_total_vitamin_c,  2));
        $sheet->setCellValue('N' . $num, round($super_total_na, 2));
        $sheet->setCellValue('O' . $num, round($super_total_k, 2));
        $sheet->setCellValue('P' . $num, round($super_total_ca, 2));
        $sheet->setCellValue('Q' . $num, round($super_total_mg, 2));
        $sheet->setCellValue('R' . $num, round($super_total_p, 2));
        $sheet->setCellValue('S' . $num, round($super_total_fe, 2));
        $sheet->setCellValue('T' . $num, round($super_total_i, 2));
        $sheet->setCellValue('U' . $num, round($super_total_se, 2));
        $sheet->setCellValue('V' . $num, round($super_total_f, 2));


        $sheet->getStyle("B" . $num . ":V" . $num)->getFont()->setBold(true);
        $sheet->getStyle("B" . $num . ":V" . $num)->getFont()->getColor()->setRGB('cf3042');
        $num = $num + 2;


        $filename = 'Отчет_Меню_За_День' . date('Y_m_d_H_i', time()) . '.xlsx';
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $objWriter = \PHPExcel_IOFactory::createWriter($document, 'Excel2007');
        $objWriter->save('php://output');
        exit;

    }

    public function actionExportMenusPeriodExcel($menu_id, $cycle, $him)
    {

        require_once Yii::$app->basePath . '/Excel/PHPExcel.php';
        require_once Yii::$app->basePath . '/Excel/PHPExcel/IOFactory.php';

        $document = new \PHPExcel();
        //подгружаем готовый шаблон !!!!
        //$document = \PHPExcel_IOFactory::load('../web/images/generator.xlsx');
        ob_start();

        $model = new MenusDishes();
        $menus_nutrition_id = MenusNutrition::find()->where(['menu_id' => $menu_id])->all();//ВСЕ ПРИЕМЫ ПИЩИ КОНКРЕТНОГО МЕНЮ
        $ids = [];
        foreach ($menus_nutrition_id as $m_id)
        {
            $ids[] = $m_id->nutrition_id;//МАССИВ ID ПРИЕМОВ ПИЩИ КЛНКРЕЬНОГО МЕНЮ
        }
        $nutritions = NutritionInfo::find()->where(['id' => $ids])->all();//ОБЪЕКТ С НАБОРОМ ПРИЕМОВ ПИЩИ ИЗ БАЗЫ С УЧЕТОМ НАШИХ АЙДИ

        $menus_dishes = MenusDishes::find()->
        select(['menus_dishes.id as id', 'menus_dishes.date_fact_menu as date_fact_menu', 'menus_dishes.menu_id as menu_id', 'menus_dishes.cycle as cycle', 'menus_dishes.days_id as days_id', 'menus_dishes.nutrition_id as nutrition_id', 'menus_dishes.dishes_id as dishes_id', 'menus_dishes.yield as yield', 'dishes_category.sort as sort'])->
        leftJoin('dishes', 'menus_dishes.dishes_id = dishes.id')->
        leftJoin('dishes_category', 'dishes.dishes_category_id = dishes_category.id')->
        where(['date_fact_menu' => 0, 'menu_id' => $menu_id])->
        orderby(['cycle' => SORT_ASC, 'days_id' => SORT_ASC, 'nutrition_id' => SORT_ASC, 'sort' => SORT_ASC])->
        all();

        $menus_days_id = MenusDays::find()->where(['menu_id' => $menu_id])->all();//ВСЕ ДНИ КОНКРЕТНОГО МЕНЮ
        $days_ids = [];
        foreach ($menus_days_id as $day_id)
        {
            $days_ids[] = $day_id->days_id;//МАССИВ ID ДНЕЙ КЛНКРЕЬНОГО МЕНЮ
        }

        $days = Days::find()->where(['id' => $days_ids])->all();//ОБЪЕКТ С НАБОРОМ ПРИЕМОВ ПИЩИ ИЗ БАЗЫ С УЧЕТОМ НАШИХ АЙДИ
        $count_my_days = MenusDays::find()->where(['menu_id' => $menu_id])->count();
        $my_menus = Menus::findOne($menu_id);
        $normativ_vitamin_day_vitamin_a = \common\models\NormativVitaminDay::find()->where(['name' => 'vitamin_a', 'age_info_id' => $my_menus->age_info_id])->one()->value;
        $normativ_vitamin_day_k = \common\models\NormativVitaminDay::find()->where(['name' => 'k', 'age_info_id' => $my_menus->age_info_id])->one()->value;

        $menu_cycle_count = $my_menus->cycle;

        if ($cycle == 0)
        {
            $count_my_days = $count_my_days * $menu_cycle_count;
        }

        $cycle_ids = [];
        if ($cycle != 0)
        {
            $cycle_ids[$cycle] = $cycle;
        }
        else
        {
            for ($i = 1; $i <= $menu_cycle_count; $i++)
            {
                $cycle_ids[$i] = $i;//массив из подходящи циклов
            }
        }


        $num = 9;
        $sheet = $document->getActiveSheet();
        $num_st = 7;
        $this->layout = false;

        $sheet->getColumnDimension('A')->setWidth("30");
        $sheet->getColumnDimension('B')->setWidth("50");
        $sheet->getColumnDimension('G')->setWidth("30");

        $sheet->getStyle("B1")->getFont()->setBold(true);
        $sheet->getStyle("B2")->getFont()->setBold(true);
        $sheet->getStyle("B3")->getFont()->setBold(true);
        $sheet->getStyle("B4")->getFont()->setBold(true);
        $sheet->getStyle("B5")->getFont()->setBold(true);
        $sheet->getStyle("A7:V7")->getFont()->setBold(true);
        $sheet->getStyle("A8:V8")->getFont()->setBold(true);

        $sheet->setCellValue('B' . 1, 'Организация: '.Organization::findOne($my_menus->organization_id)->title);
        $sheet->setCellValue('B' . 2, 'Название меню: '.$my_menus->name);
        $sheet->setCellValue('B' . 3, 'Возрастная категория: '.AgeInfo::findOne($my_menus->age_info_id)->name);
        $sheet->setCellValue('B' . 4, 'Характеристика питающихся: '.FeedersCharacters::findOne($my_menus->feeders_characters_id)->name);
        $sheet->setCellValue('B' . 5, 'Срок действия меню: '.date('d.m.Y', $my_menus->date_start).' - '.date('d.m.Y', $my_menus->date_end));

        $sheet->setCellValue('A' . $num_st, "№ рецептуры");
        $sheet->setCellValue('B' . $num_st, "Название блюда");
        $sheet->setCellValue('C' . $num_st, "Масса");
        $sheet->setCellValue('D' . $num_st, "Белки");
        $sheet->setCellValue('E' . $num_st, "Жиры");
        $sheet->setCellValue('F' . $num_st, "Углеводы");
        $sheet->setCellValue('G' . $num_st, "Энергетическая ценность");

        if($him == 1)
        {
            $sheet->setCellValue('H' . $num_st, "B1");
            $sheet->setCellValue('I' . $num_st, "B2");
            $sheet->setCellValue('J' . $num_st, "A");
            $sheet->setCellValue('K' . $num_st, "D");
            $sheet->setCellValue('L' . $num_st, "C");
            $sheet->setCellValue('N' . $num_st, "Na");
            $sheet->setCellValue('O' . $num_st, "K");
            $sheet->setCellValue('P' . $num_st, "Ca");
            $sheet->setCellValue('Q' . $num_st, "Mg");
            $sheet->setCellValue('R' . $num_st, "P");
            $sheet->setCellValue('S' . $num_st, "Fe");
            $sheet->setCellValue('T' . $num_st, "I");
            $sheet->setCellValue('U' . $num_st, "Se");
            $sheet->setCellValue('V' . $num_st, "F");
        }

        $num_st++;
        $sheet->setCellValue('C' . $num_st, "г");
        $sheet->setCellValue('D' . $num_st, "г");
        $sheet->setCellValue('E' . $num_st, "г");
        $sheet->setCellValue('F' . $num_st, "г");
        $sheet->setCellValue('G' . $num_st, "ккал");

        if($him == 1)
        {
            $sheet->setCellValue('H' . $num_st, "мг");
            $sheet->setCellValue('I' . $num_st, "мг");
            $sheet->setCellValue('J' . $num_st, "мкг рет.экв");
            $sheet->setCellValue('K' . $num_st, "мкг");
            $sheet->setCellValue('L' . $num_st, "мг");
            $sheet->setCellValue('N' . $num_st, "мг");
            $sheet->setCellValue('O' . $num_st, "мг");
            $sheet->setCellValue('P' . $num_st, "мг");
            $sheet->setCellValue('Q' . $num_st, "мг");
            $sheet->setCellValue('R' . $num_st, "мг");
            $sheet->setCellValue('S' . $num_st, "мг");
            $sheet->setCellValue('T' . $num_st, "мкг");
            $sheet->setCellValue('U' . $num_st, "мкг");
            $sheet->setCellValue('V' . $num_st, "мкг");
        }

        $count_cycle = 0;
        $count = 0;
        $data = [];
        foreach ($cycle_ids as $cycle_id)
        {
            $count++;
            foreach ($days as $day)
            {
                $sheet->setCellValue('B' . $num, $day->name . ', ' . $cycle_id . ' неделя');
                $sheet->getStyle("B" . $num)->getFont()->setBold(true);
                $sheet->getStyle("B" . $num)->getFont()->getColor()->setRGB('3d30cf');
                $num = $num + 1;

                $super_total_yield = 0;
                $super_total_protein = 0;
                $super_total_fat = 0;
                $super_total_carbohydrates_total = 0;
                $super_total_energy_kkal = 0;

                //vitamins
                $super_total_vitamin_a = 0; $super_total_vitamin_c = 0; $super_total_vitamin_b1 = 0; $super_total_vitamin_b2 = 0; $super_total_vitamin_d = 0; $super_total_vitamin_pp = 0; $super_total_na = 0; $super_total_k = 0; $super_total_ca = 0; $super_total_f = 0; $super_total_p = 0; $super_total_i = 0; $super_total_mg = 0; $super_total_fe = 0;$super_total_se = 0;
                //end vitamins
                foreach ($nutritions as $nutrition)
                {
                    $energy_kkal = 0;
                    $protein = 0;
                    $fat = 0;
                    $carbohydrates_total = 0;
                    $sheet->setCellValue('B' . $num, $nutrition->name);
                    $sheet->getStyle("B" . $num)->getFont()->setBold(true);
                    $num = $num + 1;
                    $vitamins = []; unset($vitamins); $vitamin_a = 0; $vitamin_c = 0; $vitamin_b1 = 0; $vitamin_b2 = 0; $vitamin_d = 0; $vitamin_pp = 0; $na = 0; $k = 0; $ca = 0; $f = 0; $p = 0; $se = 0; $i = 0; $mg = 0; $fe = 0;

                    foreach ($menus_dishes as $key => $m_dish)
                    {

                        if ($nutrition->id == $m_dish->nutrition_id && $m_dish->cycle == $cycle_id && $day->id == $m_dish->days_id)
                        {
                            $protein_dish = round($m_dish->get_bju_dish($m_dish->id, 'protein'), 1);
                            $protein = $protein_dish + $protein;
                            $fat_dish = round($m_dish->get_bju_dish($m_dish->id, 'fat'), 1);
                            $fat = $fat_dish + $fat;
                            $carbohydrates_total_dish = round($m_dish->get_bju_dish($m_dish->id, 'carbohydrates_total'), 1);
                            $carbohydrates_total = $carbohydrates_total_dish + $carbohydrates_total;
                            $kkal = round($m_dish->get_kkal_dish($m_dish->id), 1);
                            $energy_kkal = $energy_kkal + $kkal;

                            //РАСЧЕТ ВИТАМИНА
                            $vitamins['vitamin_a'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'vitamin_a'), 2);
                            $vitamin_a = $vitamin_a + $vitamins['vitamin_a'];
                            $vitamins['vitamin_c'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'vitamin_c'), 2);
                            $vitamin_c = $vitamin_c + $vitamins['vitamin_c'];
                            $vitamins['vitamin_b1'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'vitamin_b1'), 2);
                            $vitamin_b1 = $vitamin_b1 + $vitamins['vitamin_b1'];
                            $vitamins['vitamin_b2'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'vitamin_b2'), 2);
                            $vitamin_b2 = $vitamin_b2 + $vitamins['vitamin_b2'];
                            $vitamins['vitamin_d'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'vitamin_d'), 2);
                            $vitamin_d = $vitamin_d + $vitamins['vitamin_d'];
                            $vitamins['vitamin_pp'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'vitamin_pp'), 2);
                            $vitamin_pp = $vitamin_pp + $vitamins['vitamin_pp'];
                            $vitamins['na'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'na'), 2);
                            $na = $na + $vitamins['na'];
                            $vitamins['k'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'k'), 2);
                            $k = $k + $vitamins['k'];
                            $vitamins['ca'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'ca'), 2);
                            $ca = $ca + $vitamins['ca'];
                            $vitamins['f'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'f'), 2);
                            $f = $f + $vitamins['f'];
                            $vitamins['mg'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'mg'), 2);
                            $mg = $mg + $vitamins['mg'];
                            $vitamins['p'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'p'), 2);
                            $p = $p + $vitamins['p'];
                            $vitamins['fe'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'fe'), 2);
                            $fe = $fe + $vitamins['fe'];
                            $vitamins['i'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'i'), 2);
                            $i = $i + $vitamins['i'];
                            $vitamins['se'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'se'), 2);
                            $se = $se + $vitamins['se'];
                            //КОНЕЦ РАСЧЕТА

                            $sheet->setCellValue('A' . $num, $m_dish->get_techmup($m_dish->dishes_id));
                            $sheet->setCellValue('B' . $num, $m_dish->get_dishes($m_dish->dishes_id));
                            $sheet->setCellValue('C' . $num, $m_dish->yield);
                            $sheet->setCellValue('D' . $num, $protein_dish);
                            $sheet->setCellValue('E' . $num, $fat_dish);
                            $sheet->setCellValue('F' . $num, $carbohydrates_total_dish);
                            $sheet->setCellValue('G' . $num, $kkal);
                            if ($him == 1)
                            {
                                $sheet->setCellValue('H' . $num, round($vitamins['vitamin_b1'], 2));
                                $sheet->setCellValue('I' . $num, round($vitamins['vitamin_b2'], 2));
                                $sheet->setCellValue('J' . $num, round($vitamins['vitamin_a'], 2));
                                $sheet->setCellValue('K' . $num, round($vitamins['vitamin_d'], 2));
                                $sheet->setCellValue('L' . $num, round($vitamins['vitamin_c'], 2));
                                $sheet->setCellValue('N' . $num, round($vitamins['na'], 2));
                                $sheet->setCellValue('O' . $num, round($vitamins['k'], 2));
                                $sheet->setCellValue('P' . $num, round($vitamins['ca'], 2));
                                $sheet->setCellValue('Q' . $num, round($vitamins['mg'], 2));
                                $sheet->setCellValue('R' . $num, round($vitamins['p'], 2));
                                $sheet->setCellValue('S' . $num, round($vitamins['fe'], 2));
                                $sheet->setCellValue('T' . $num, round($vitamins['i'], 2));
                                $sheet->setCellValue('U' . $num, round($vitamins['se'], 2));
                                $sheet->setCellValue('V' . $num, round($vitamins['f'], 2));
                            }

                            unset($menus_dishes[$key]);
                            $num = $num + 1;
                        }
                    }

                    $sheet->setCellValue('B' . $num, 'Итого за ' . $nutrition->name);
                    $yield = $model->get_total_yield($menu_id, $cycle_id, $day->id, $nutrition->id);
                    $data[$nutrition->id]['yield'] = $data[$nutrition->id]['yield'] + $yield;
                    $super_total_yield = $super_total_yield + $yield;
                    $data[$nutrition->id]['protein'] = $data[$nutrition->id]['protein'] + $protein;
                    $super_total_protein = $super_total_protein + $protein;
                    $data[$nutrition->id]['fat'] = $data[$nutrition->id]['fat'] + $fat;
                    $super_total_fat = $super_total_fat + $fat;
                    $data[$nutrition->id]['carbohydrates_total'] = $data[$nutrition->id]['carbohydrates_total'] + $carbohydrates_total;
                    $super_total_carbohydrates_total = $super_total_carbohydrates_total + $carbohydrates_total;
                    $data[$nutrition->id]['energy_kkal'] = $data[$nutrition->id]['energy_kkal'] + $energy_kkal;
                    $super_total_energy_kkal = $super_total_energy_kkal + $energy_kkal;


                    //РАСЧЕТ ВИТАМИНОВ
                    $data[$nutrition->id]['vitamin_a'] = $data[$nutrition->id]['vitamin_a'] + $vitamin_a;
                    $data[$nutrition->id]['vitamin_c'] = $data[$nutrition->id]['vitamin_c'] + $vitamin_c;
                    $data[$nutrition->id]['vitamin_b1'] = $data[$nutrition->id]['vitamin_b1'] + $vitamin_b1;
                    $data[$nutrition->id]['vitamin_b2'] = $data[$nutrition->id]['vitamin_b2'] + $vitamin_b2;
                    $data[$nutrition->id]['vitamin_d'] = $data[$nutrition->id]['vitamin_d'] + $vitamin_d;
                    $data[$nutrition->id]['vitamin_pp'] = $data[$nutrition->id]['vitamin_pp'] + $vitamin_pp;
                    $data[$nutrition->id]['vitamin_na'] = $data[$nutrition->id]['vitamin_na'] + $na;
                    $data[$nutrition->id]['vitamin_k'] = $data[$nutrition->id]['vitamin_k'] + $k;
                    $data[$nutrition->id]['vitamin_ca'] = $data[$nutrition->id]['vitamin_ca'] + $ca;
                    $data[$nutrition->id]['vitamin_f'] = $data[$nutrition->id]['vitamin_f'] + $f;
                    $data[$nutrition->id]['vitamin_mg'] = $data[$nutrition->id]['vitamin_mg'] + $mg;
                    $data[$nutrition->id]['vitamin_p'] = $data[$nutrition->id]['vitamin_p'] + $p;
                    $data[$nutrition->id]['vitamin_fe'] = $data[$nutrition->id]['vitamin_fe'] + $fe;
                    $data[$nutrition->id]['vitamin_i'] = $data[$nutrition->id]['vitamin_i'] + $i;
                    $data[$nutrition->id]['vitamin_se'] = $data[$nutrition->id]['vitamin_se'] + $se;

                    //raschet v itog za den

                    $super_total_vitamin_a = $super_total_vitamin_a + $vitamin_a;
                    $super_total_vitamin_c = $super_total_vitamin_c + $vitamin_c;
                    $super_total_vitamin_b1 = $super_total_vitamin_b1 + $vitamin_b1;
                    $super_total_vitamin_b2 = $super_total_vitamin_b2 + $vitamin_b2;
                    $super_total_vitamin_d = $super_total_vitamin_d + $vitamin_d;
                    $super_total_vitamin_pp = $super_total_vitamin_pp + $vitamin_pp ;
                    $super_total_na = $super_total_na + $na;
                    $super_total_k  = $super_total_k  + $k;
                    $super_total_ca = $super_total_ca + $ca ;
                    $super_total_f  = $super_total_f  + $f;
                    $super_total_mg = $super_total_mg + $mg;
                    $super_total_p = $super_total_p + $p;
                    $super_total_fe = $super_total_fe + $fe;
                    $super_total_i = $super_total_i + $i;
                    $super_total_se = $super_total_se + $se;
                    //КОНЕЦ РАСЧЕТА


                    //итого за завтрак
                    $sheet->setCellValue('C' . $num, $yield);
                    $sheet->setCellValue('D' . $num, $protein);
                    $sheet->setCellValue('E' . $num, $fat);
                    $sheet->setCellValue('F' . $num, $carbohydrates_total);
                    $sheet->setCellValue('G' . $num, $energy_kkal);
                    if ($him == 1)
                    {
                        $sheet->setCellValue('H' . $num, round($vitamin_b1, 2));
                        $sheet->setCellValue('I' . $num, round($vitamin_b2, 2));
                        $sheet->setCellValue('J' . $num, round($vitamin_a, 2));
                        $sheet->setCellValue('K' . $num, round($vitamin_d, 2));
                        $sheet->setCellValue('L' . $num, round($vitamin_c, 2));
                        $sheet->setCellValue('N' . $num, round($na, 2));
                        $sheet->setCellValue('O' . $num, round($k, 2));
                        $sheet->setCellValue('P' . $num, round($ca, 2));
                        $sheet->setCellValue('Q' . $num, round($mg, 2));
                        $sheet->setCellValue('R' . $num, round($p, 2));
                        $sheet->setCellValue('S' . $num, round($fe, 2));
                        $sheet->setCellValue('T' . $num, round($i, 2));
                        $sheet->setCellValue('U' . $num, round($se, 2));
                        $sheet->setCellValue('V' . $num, round($f, 2));
                    }



                    $sheet->getStyle("B" . $num . ":V" . $num)->getFont()->setBold(true);
                    $num = $num + 1;
                }
                $sheet->setCellValue('B' . $num, 'Итого за день');
                $sheet->setCellValue('C' . $num, $super_total_yield);
                $sheet->setCellValue('D' . $num, $super_total_protein);
                $sheet->setCellValue('E' . $num, $super_total_fat);
                $sheet->setCellValue('F' . $num, $super_total_carbohydrates_total);
                $sheet->setCellValue('G' . $num, $super_total_energy_kkal);


                if ($him == 1)
                {
                    $sheet->setCellValue('H' . $num, round($super_total_vitamin_b1,  2));
                    $sheet->setCellValue('I' . $num, round($super_total_vitamin_b2,  2));
                    $sheet->setCellValue('J' . $num, round($super_total_vitamin_a, 2));
                    $sheet->setCellValue('K' . $num, round($super_total_vitamin_d, 2));
                    $sheet->setCellValue('L' . $num, round($super_total_vitamin_c,  2));
                    $sheet->setCellValue('N' . $num, round($super_total_na, 2));
                    $sheet->setCellValue('O' . $num, round($super_total_k, 2));
                    $sheet->setCellValue('P' . $num, round($super_total_ca, 2));
                    $sheet->setCellValue('Q' . $num, round($super_total_mg, 2));
                    $sheet->setCellValue('R' . $num, round($super_total_p, 2));
                    $sheet->setCellValue('S' . $num, round($super_total_fe, 2));
                    $sheet->setCellValue('T' . $num, round($super_total_i, 2));
                    $sheet->setCellValue('U' . $num, round($super_total_se, 2));
                    $sheet->setCellValue('V' . $num, round($super_total_f, 2));

                }


                $sheet->getStyle("B" . $num . ":V" . $num)->getFont()->setBold(true);
                $sheet->getStyle("B" . $num . ":V" . $num)->getFont()->getColor()->setRGB('cf3042');
                $num = $num + 2;

            }
        }


        $sheet->setCellValue('C' . $num, "Масса");
        $sheet->setCellValue('D' . $num, "Белки");
        $sheet->setCellValue('E' . $num, "Жиры");
        $sheet->setCellValue('F' . $num, "Углеводы");
        $sheet->setCellValue('G' . $num, "Энергетическая ценность");
        $sheet->setCellValue('H' . $num, "B1");
        $sheet->setCellValue('I' . $num, "B2");
        $sheet->setCellValue('J' . $num, "A");
        $sheet->setCellValue('K' . $num, "D");
        $sheet->setCellValue('L' . $num, "C");
        $sheet->setCellValue('N' . $num, "Na");
        $sheet->setCellValue('O' . $num, "K");
        $sheet->setCellValue('P' . $num, "Ca");
        $sheet->setCellValue('Q' . $num, "Mg");
        $sheet->setCellValue('R' . $num, "P");
        $sheet->setCellValue('S' . $num, "Fe");
        $sheet->setCellValue('T' . $num, "I");
        $sheet->setCellValue('U' . $num, "Se");
        $sheet->setCellValue('V' . $num, "F");

        $sheet->getStyle("B" . $num . ":V" . $num)->getFont()->setBold(true);
        $num++;
        $sheet->setCellValue('C' . $num, "г");
        $sheet->setCellValue('D' . $num, "г");
        $sheet->setCellValue('E' . $num, "г");
        $sheet->setCellValue('F' . $num, "г");
        $sheet->setCellValue('G' . $num, "ккал");
        $sheet->setCellValue('H' . $num, "мг");
        $sheet->setCellValue('I' . $num, "мг");
        $sheet->setCellValue('J' . $num, "мкг рет.экв");
        $sheet->setCellValue('K' . $num, "мкг");
        $sheet->setCellValue('L' . $num, "мг");
        $sheet->setCellValue('N' . $num, "мг");
        $sheet->setCellValue('O' . $num, "мг");
        $sheet->setCellValue('P' . $num, "мг");
        $sheet->setCellValue('Q' . $num, "мг");
        $sheet->setCellValue('R' . $num, "мг");
        $sheet->setCellValue('S' . $num, "мг");
        $sheet->setCellValue('T' . $num, "мкг");
        $sheet->setCellValue('U' . $num, "мкг");
        $sheet->setCellValue('V' . $num, "мкг");






        $sheet->getStyle("B" . $num . ":V" . $num)->getFont()->setBold(true);
        $data_itog = [];
        $num = $num + 1;
        foreach ($nutritions as $nutrition)
        {

            //ИСКУССТВЕННОЕ ИЗМЕНЕНИЕ ЗНАЧЕНИЙ ДЛЯ МЕНЮ ПО ПРОСЬБЕ ИИ И РОМАНЕНКО МЕНЮ ПО НСК --КОСТЫЛИ--
            //НСК-1
            if(Menus::findOne($menu_id)->id == 16047/*У АДМИНА*/ || /*В АРХИВЕ*/Menus::findOne($menu_id)->id == 16079 || Menus::findOne($menu_id)->parent_id == 16079){
                //по обедам и завтракам
                if($nutrition->id == 1){
                    $data[$nutrition->id]['protein'] = 17.4*$count_my_days;
                    $data[$nutrition->id]['fat'] = 15.9*$count_my_days;
                    $data[$nutrition->id]['vitamin_se'] = 16.1*$count_my_days;
                }
                if($nutrition->id == 3){
                    $data[$nutrition->id]['protein'] = 24*$count_my_days;
                    $data[$nutrition->id]['fat'] = 24*$count_my_days;
                    $data[$nutrition->id]['vitamin_se'] = 14.4*$count_my_days;
                }
            }

            //НСК-2
            if(Menus::findOne($menu_id)->id == 16048/*У АДМИНА*/ || /*В АРХИВЕ*/Menus::findOne($menu_id)->id == 16080 || Menus::findOne($menu_id)->parent_id == 16080){
                //по обедам и завтракам
                if($nutrition->id == 1){
                    $data[$nutrition->id]['protein'] = 20.1*$count_my_days;
                    $data[$nutrition->id]['fat'] = 16*$count_my_days;
                    $data[$nutrition->id]['vitamin_se'] = 15.2*$count_my_days;
                }
                if($nutrition->id == 3){
                    $data[$nutrition->id]['protein'] = 23.6*$count_my_days;
                    $data[$nutrition->id]['fat'] = 23.9*$count_my_days;
                    $data[$nutrition->id]['vitamin_se'] = 15.2*$count_my_days;
                }
            }

            //НСК-3
            if(Menus::findOne($menu_id)->id == 16055/*У АДМИНА*/ || /*В АРХИВЕ*/Menus::findOne($menu_id)->id == 16081 || Menus::findOne($menu_id)->parent_id == 16081){
                //по обедам и завтракам
                if($nutrition->id == 1){
                    $data[$nutrition->id]['protein'] = 15.8*$count_my_days;
                    $data[$nutrition->id]['fat'] = 15.9*$count_my_days;
                    $data[$nutrition->id]['vitamin_se'] = 11.9*$count_my_days;
                }
                if($nutrition->id == 3){
                    $data[$nutrition->id]['protein'] = 24.0*$count_my_days;
                    $data[$nutrition->id]['fat'] = 23.9*$count_my_days;
                    $data[$nutrition->id]['vitamin_se'] = 18.7*$count_my_days;
                }
            }


            $data_vit_a = round($data[$nutrition->id]['vitamin_a']/$count_my_days, 2);$procent = \common\models\NutritionProcent::find()->where(['type_org' => \common\models\Organization::findOne(Yii::$app->user->identity->organization_id)->type_org, 'nutrition_id' =>$nutrition->id])->one()->procent/100; if($data_vit_a <= $normativ_vitamin_day_vitamin_a*1.5*$procent){ $data_itog['vitamin_a'] = $data_itog['vitamin_a'] + $data_vit_a;}else{$data_vit_a = $normativ_vitamin_day_vitamin_a*1.5*$procent; $data_itog['vitamin_a'] = $data_itog['vitamin_a'] + $data_vit_a;}
            $data_vit_k = round($data[$nutrition->id]['vitamin_k']/$count_my_days, 2);$procent = \common\models\NutritionProcent::find()->where(['type_org' => \common\models\Organization::findOne(Yii::$app->user->identity->organization_id)->type_org, 'nutrition_id' =>$nutrition->id])->one()->procent/100; if($data_vit_k <= $normativ_vitamin_day_k*1.5*$procent){ $data_itog['vitamin_k'] = $data_itog['vitamin_k'] + $data_vit_k;}else{$data_vit_k = $normativ_vitamin_day_k*1.5*$procent; $data_itog['vitamin_k'] = $data_itog['vitamin_k'] + $data_vit_k;}
            $sheet->setCellValue('B' . $num, 'Средние показатели за ' . $nutrition->name);
            $sheet->setCellValue('C' . $num, round($data[$nutrition->id]['yield'] / $count_my_days, 2));
            $sheet->setCellValue('D' . $num, round($data[$nutrition->id]['protein'] / $count_my_days, 2));
            $sheet->setCellValue('E' . $num, round($data[$nutrition->id]['fat'] / $count_my_days, 2));
            $sheet->setCellValue('F' . $num, round($data[$nutrition->id]['carbohydrates_total'] / $count_my_days, 2));
            $sheet->setCellValue('G' . $num, round($data[$nutrition->id]['energy_kkal'] / $count_my_days, 2));


            //ВЫВОД ВИТАМИНА
            $sheet->setCellValue('H' . $num, round($data[$nutrition->id]['vitamin_b1'] / $count_my_days, 2));
            $sheet->setCellValue('I' . $num, round($data[$nutrition->id]['vitamin_b2'] / $count_my_days, 2));
            $sheet->setCellValue('J' . $num, $data_vit_a);
            $sheet->setCellValue('K' . $num, round($data[$nutrition->id]['vitamin_d'] / $count_my_days, 2));
            $sheet->setCellValue('L' . $num, round($data[$nutrition->id]['vitamin_c'] / $count_my_days, 2));
            $sheet->setCellValue('N' . $num, round($data[$nutrition->id]['vitamin_na'] / $count_my_days, 2));
            $sheet->setCellValue('O' . $num, $data_vit_k);
            $sheet->setCellValue('P' . $num, round($data[$nutrition->id]['vitamin_ca'] / $count_my_days, 2));
            $sheet->setCellValue('Q' . $num, round($data[$nutrition->id]['vitamin_mg'] / $count_my_days, 2));
            $sheet->setCellValue('R' . $num, round($data[$nutrition->id]['vitamin_p'] / $count_my_days, 2));
            $sheet->setCellValue('S' . $num, round($data[$nutrition->id]['vitamin_fe'] / $count_my_days, 2));
            $sheet->setCellValue('T' . $num, round($data[$nutrition->id]['vitamin_i'] / $count_my_days, 2));
            $sheet->setCellValue('U' . $num, round($data[$nutrition->id]['vitamin_se'] / $count_my_days, 2));
            $sheet->setCellValue('V' . $num, round($data[$nutrition->id]['vitamin_f'] / $count_my_days, 2));
            //КОНЕЦ

            $sheet->getStyle("B" . $num . ":V" . $num)->getFont()->setBold(true);
            $sheet->getStyle("B" . $num . ":V" . $num)->getFont()->getColor()->setRGB('cf3042');
            $num = $num + 1;

            $data_itog['yield'] = $data_itog['yield'] + round($data[$nutrition->id]['yield'] / $count_my_days, 2);
            $data_itog['protein'] = $data_itog['protein'] + round($data[$nutrition->id]['protein'] / $count_my_days, 2);
            $data_itog['fat'] = $data_itog['fat'] + round($data[$nutrition->id]['fat'] / $count_my_days, 2);
            $data_itog['carbohydrates_total'] = $data_itog['carbohydrates_total'] + round($data[$nutrition->id]['carbohydrates_total'] / $count_my_days, 2);
            $data_itog['energy_kkal'] = $data_itog['energy_kkal'] + round($data[$nutrition->id]['energy_kkal'] / $count_my_days, 2);


            //$data_itog['vitamin_a'] = $data_itog['vitamin_a'] + $data_vit_a;
            $data_itog['vitamin_c'] = $data_itog['vitamin_c'] + round($data[$nutrition->id]['vitamin_c']/$count_my_days, 2);
            $data_itog['vitamin_b1'] = $data_itog['vitamin_b1'] + round($data[$nutrition->id]['vitamin_b1']/$count_my_days, 2);
            $data_itog['vitamin_b2'] = $data_itog['vitamin_b2'] + round($data[$nutrition->id]['vitamin_b2']/$count_my_days, 2);
            $data_itog['vitamin_d'] = $data_itog['vitamin_d'] + round($data[$nutrition->id]['vitamin_d']/$count_my_days, 2);
            $data_itog['vitamin_pp'] = $data_itog['vitamin_pp'] + round($data[$nutrition->id]['vitamin_pp']/$count_my_days, 2);
            $data_itog['vitamin_na'] = $data_itog['vitamin_na'] + round($data[$nutrition->id]['vitamin_na']/$count_my_days, 2);
            //$data_itog['vitamin_k'] = $data_itog['vitamin_k'] + $data_vit_k;
            $data_itog['vitamin_ca'] = $data_itog['vitamin_ca'] + round($data[$nutrition->id]['vitamin_ca']/$count_my_days, 2);
            $data_itog['vitamin_f'] = $data_itog['vitamin_f'] + round($data[$nutrition->id]['vitamin_f']/$count_my_days, 2);
            $data_itog['vitamin_mg'] = $data_itog['vitamin_mg'] + round($data[$nutrition->id]['vitamin_mg']/$count_my_days, 2);
            $data_itog['vitamin_p'] = $data_itog['vitamin_p'] + round($data[$nutrition->id]['vitamin_p']/$count_my_days, 2);
            $data_itog['vitamin_fe'] = $data_itog['vitamin_fe'] + round($data[$nutrition->id]['vitamin_fe']/$count_my_days, 2);
            $data_itog['vitamin_i'] = $data_itog['vitamin_i'] + round($data[$nutrition->id]['vitamin_i']/$count_my_days, 2);
            $data_itog['vitamin_se'] = $data_itog['vitamin_se'] + round($data[$nutrition->id]['vitamin_se']/$count_my_days, 2);

        }

        $sheet->setCellValue('B' . $num, 'Средние показатели за период');
        $sheet->setCellValue('C' . $num, round($data_itog['yield'], 1));
        $sheet->setCellValue('D' . $num, round($data_itog['protein'], 1));
        $sheet->setCellValue('E' . $num, round($data_itog['fat'], 1));
        $sheet->setCellValue('F' . $num, round($data_itog['carbohydrates_total'], 1));
        $sheet->setCellValue('G' . $num, round($data_itog['energy_kkal'], 1));


        $sheet->setCellValue('H' . $num,round($data_itog['vitamin_b1'], 1));
        $sheet->setCellValue('I' . $num,round($data_itog['vitamin_b2'], 1));
        $sheet->setCellValue('J' . $num,round($data_itog['vitamin_a'], 1));
        $sheet->setCellValue('K' . $num,round($data_itog['vitamin_d'], 1));
        $sheet->setCellValue('L' . $num,round($data_itog['vitamin_c'], 1 ));
        $sheet->setCellValue('N' . $num,round($data_itog['vitamin_na'], 1));
        $sheet->setCellValue('O' . $num,round($data_itog['vitamin_k'], 1));
        $sheet->setCellValue('P' . $num,round($data_itog['vitamin_ca'], 1));
        $sheet->setCellValue('Q' . $num,round($data_itog['vitamin_mg'], 1));
        $sheet->setCellValue('R' . $num,round($data_itog['vitamin_p'], 1));
        $sheet->setCellValue('S' . $num,round($data_itog['vitamin_fe'], 1));
        $sheet->setCellValue('T' . $num,round($data_itog['vitamin_i'], 1));
        $sheet->setCellValue('U' . $num,round($data_itog['vitamin_se'] , 1));
        $sheet->setCellValue('V' . $num,round($data_itog['vitamin_f'], 1));


        $sheet->getStyle("B" . $num . ":V" . $num)->getFont()->setBold(true);
        $sheet->getStyle("B" . $num . ":V" . $num)->getFont()->getColor()->setRGB('cf3042');
        $num = $num + 1;


        $filename = 'Отчет_Меню_Период_' . date('Y_m_d_H_i', time()) . '.xlsx';
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $objWriter = \PHPExcel_IOFactory::createWriter($document, 'Excel2007');
        $objWriter->save('php://output');
        exit;

    }



    public function actionExcelProductsList($menu_id, $days_id)
    {
        $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $menu_id])->orderby(['dishes_id' => SORT_ASC])->all();
        $dishes_ids = [];
        $categories_ids = [];

        foreach ($menus_dishes as $m_dish)
        {
            $dishes_products = DishesProducts::find()->where(['dishes_id' => $m_dish->dishes_id])->all();
            foreach ($dishes_products as $d_product)
            {
                if (!in_array($d_product->products_id, $dishes_ids))
                {
                    $dishes_ids[] = $d_product->products_id;
                }
            }
        }
        $products = Products::find()->where(['id' => $dishes_ids])->orderby(['sort' => SORT_ASC])->all();
        foreach ($products as $product)
        {
            if (!in_array($product->products_category_id, $categories_ids))
            {
                $categories_ids[] = $product->products_category_id;
            }
        }
        $products_categories = ProductsCategory::find()->where(['id' => $categories_ids])->orderby(['sort' => SORT_ASC])->all();

        $my_menus = Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 0])->all();
        $my_menus_items = ArrayHelper::map($my_menus, 'id', 'name');
        $first_menu = Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 0])->one();
        $menu_cycle_count = $first_menu->cycle;
        $menu_cycle = [];
        $menu_cycle[0] = 'Показать за все недели';
        for ($i = 1; $i <= $menu_cycle_count; $i++)
        {
            $menu_cycle[$i] = $i;//массив из подходящи циклов
        }
//    !!! В $post['days_id'] ХРАНИТСЯ ИНФОРМАЦИЯ БРУТТО/НЕТТО    !!!!
        $chemistry_items = [0 => 'Брутто', 1 => 'Нетто'];
        $params_menu = ['class' => 'form-control', 'options' => [$first_menu->id => ['Selected' => true]]];
        $params_chemistry = ['class' => 'form-control', 'options' => [0 => ['Selected' => true]]];

        $my_menus = Menus::findOne($menu_id);
        $menu_cycle_count = $my_menus->cycle;
        $menu_cycle = [];
        for ($i = 1; $i <= $menu_cycle_count; $i++)
        {
            $menu_cycle[$i] = $i;//массив из подходящи циклов
        }
        $my_days = MenusDays::find()->where(['menu_id' => $menu_id])->all();
        foreach ($my_days as $m_day)
        {
            $ids[] = $m_day->days_id;
        }
        $days = Days::find()->where(['id' => $ids])->all();

        $chemistry_items = [0 => 'Брутто', 1 => 'Нетто'];
        $params_menu = ['class' => 'form-control', 'options' => [$post['menu_id'] => ['Selected' => true]]];
        $params_chemistry = ['class' => 'form-control', 'options' => [$post['days_id'] => ['Selected' => true]]];

        $style_heder = array(
            'font' => array(
                'name' => 'Times New Roman',
                'size' => 12,
                'bold' => true,
            )
        );
        require '../../vendor/autoload.php';
        $array_num = ['C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ', 'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ', 'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ', 'EA', 'EB', 'EC', 'ED', 'EE', 'EF', 'EG', 'EH', 'EI', 'EJ', 'EK', 'EL', 'EM', 'EN', 'EO', 'EP', 'EQ', 'ER', 'ES', 'ET', 'EU', 'EV', 'EW', 'EX', 'EY', 'EZ', 'FA', 'FB', 'FC', 'FD', 'FE', 'FF', 'FG', 'FH', 'FI', 'FJ', 'FK', 'FL', 'FM', 'FN', 'FO', 'FP', 'FQ', 'FR', 'FS', 'FT', 'FU', 'FV', 'FW', 'FX', 'FY', 'FZ',
        ];
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $sheet->mergeCells("A2:A3");
        $sheet->setCellValue("A2", "№");

        $sheet->mergeCells("B2:B3");
        $sheet->setCellValue("B2", "Продукт");
        $sheet->getColumnDimension('B')->setWidth("60");

        $num = 0;
        foreach ($menu_cycle as $cycle)
        {
            $sheet->setCellValue($array_num[$num] . '2', $cycle . ' неделя ');
            foreach ($days as $day)
            {
                $sheet->setCellValue($array_num[$num] . '3', $day->name);
                $num++;
            }
        }
        $sheet->mergeCells($array_num[$num] . '2' . ':' . $array_num[$num] . '3');
        $sheet->setCellValue($array_num[$num] . '2', 'Итого');
        $num++;
        $sheet->mergeCells($array_num[$num] . '2' . ':' . $array_num[$num] . '3');
        $sheet->setCellValue($array_num[$num] . '2', 'Среднесуточное значение');

        if ($days_id == 0)
        {
            $sheet->mergeCells('A1:' . $array_num[$num] . '1');
            $sheet->setCellValue('A1', 'Перечень продуктов, Брутто, г');
        }
        else
        {
            $sheet->mergeCells('A1:' . $array_num[$num] . '1');
            $sheet->setCellValue('A1', 'Перечень продуктов, Нетто, г');
        }

        $array_num2 = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ', 'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ', 'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ', 'EA', 'EB', 'EC', 'ED', 'EE', 'EF', 'EG', 'EH', 'EI', 'EJ', 'EK', 'EL', 'EM', 'EN', 'EO', 'EP', 'EQ', 'ER', 'ES', 'ET', 'EU', 'EV', 'EW', 'EX', 'EY', 'EZ', 'FA', 'FB', 'FC', 'FD', 'FE', 'FF', 'FG', 'FH', 'FI', 'FJ', 'FK', 'FL', 'FM', 'FN', 'FO', 'FP', 'FQ', 'FR', 'FS', 'FT', 'FU', 'FV', 'FW', 'FX', 'FY', 'FZ',
        ];

        $column = 4;

        $number_row = 1;
        $i = 0;
        foreach ($products_categories as $product_cat)
        {

            foreach ($products as $product)
            {

                if ($product_cat->id == $product->products_category_id)
                {

                    //$brutto_netto_products в нем будут лежать нормативы по брутто нужных продуктов. Чтобы картоха морковь свекла вывелись по количеству раз равных количеству нормативов брутто
                    //Вся процедура начнется если в выпадающем списке указано вариация брутто-->
                    $brutto_netto_products = [];
                    if ($days_id == 0 && ($product->id == 14 || $product->id == 142 || $product->id == 152))
                    {
                        $brutto_netto_products = \common\models\BruttoNettoKoef::find()->where(['products_id' => $product->id])->all();
                        //print_r($brutto_netto_products);exit;
                    }
                    else
                    {
                        $brutto_netto_products[] = 1;
                    }

                    foreach ($brutto_netto_products as $brutto_netto_product)
                    {


                        $num2 = 0;
                        $totality = 0;
                        $sheet->setCellValue($array_num2[$num2] . $column, $number_row);
                        $num2++;
                        $products_change = ProductsChangeOrganization::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'products_id' => $product->id])->one();
                        if (!empty($products_change))
                        {
                            $val_cel = Products::findOne($products_change->change_products_id)->name;
                            $sheet->setCellValue($array_num2[$num2] . $column, $val_cel);
                        }
                        else
                        {

                            //десь к названию продукта выводим продолжительность норматива для каждого пункта-->

                            //КАРТОХА-->
                            if ($days_id == 0 && $brutto_netto_product->products_id == 14 && $brutto_netto_product->season == 2)
                            {
                                $sheet->setCellValue($array_num2[$num2] . $column, $product->name . '(01.09-31.10)');
                            }
                            elseif ($days_id == 0 && $brutto_netto_product->products_id == 14 && $brutto_netto_product->season == 3)
                            {
                                $sheet->setCellValue($array_num2[$num2] . $column, $product->name . '(31.10-31.12)');
                            }
                            elseif ($days_id == 0 && $brutto_netto_product->products_id == 14 && $brutto_netto_product->season == 4)
                            {
                                $sheet->setCellValue($array_num2[$num2] . $column, $product->name . '(31.12-28.02)');
                            }
                            elseif ($days_id == 0 && $brutto_netto_product->products_id == 14 && $brutto_netto_product->season == 1)
                            {
                                $sheet->setCellValue($array_num2[$num2] . $column, $product->name . '(29.02-01.09)');
                                //МОРКОВЬ-->
                            }
                            elseif ($days_id == 0 && $brutto_netto_product->products_id == 142 && $brutto_netto_product->season == 1)
                            {
                                $sheet->setCellValue($array_num2[$num2] . $column, $product->name . '(01.09-31.12)');
                            }
                            elseif ($days_id == 0 && $brutto_netto_product->products_id == 142 && $brutto_netto_product->season == 3)
                            {
                                $sheet->setCellValue($array_num2[$num2] . $column, $product->name . '(01.01-31.08)');
                                //СВЕКЛУХА-->
                            }
                            elseif ($days_id == 0 && $brutto_netto_product->products_id == 152 && $brutto_netto_product->season == 1)
                            {
                                $sheet->setCellValue($array_num2[$num2] . $column, $product->name . '(01.09-31.12)');
                            }
                            elseif ($days_id == 0 && $brutto_netto_product->products_id == 152 && $brutto_netto_product->season == 3)
                            {
                                $sheet->setCellValue($array_num2[$num2] . $column, $product->name . '(01.01-31.08)');
                            }
                            else
                            {
                                $sheet->setCellValue($array_num2[$num2] . $column, $product->name);
                            }
                        }
                        //Заполнение end-->

                        //}
                        $num2++;
                        foreach ($menu_cycle as $cycle)
                        {
                            foreach ($days as $day)
                            {

                                $total = $product->get_total_yield_day($product->id, $menu_id, $cycle, $day->id, $days_id);
                                if ($total['yield'] != '-' && $days_id == 0 && ($product->id == 14 || $product->id == 142 || $product->id == 152))
                                {
                                    $total['yield'] = round($total['yield'] * $brutto_netto_product->koeff_netto, 1);
                                }

                                $sheet->setCellValue($array_num2[$num2] . $column, $total['yield']);
                                if ($total['yield'] == '-')
                                {
                                    $total['yield'] = 0;
                                }
                                $totality = $total['yield'] + $totality;
                                $num2++;
                            }

                        }
                        $sheet->setCellValue($array_num2[$num2] . $column, $totality);
                        $num2++;
                        $sheet->setCellValue($array_num2[$num2] . $column, round($totality / (count($menu_cycle) * count($days)), 2));
                        $column++;
                        $number_row++;
                    }
                }
            }

        }


        $filename = 'Перечень продуктов.xlsx'; //save our workbook as this file name
        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        die();
    }


    public function actionExcelProductsListNutrition($menu_id, $days_id)
    {


        $nutritions = MenusNutrition::find()->where(['menu_id' => $menu_id])->orderBy(['nutrition_id'=> SORT_ASC])->all();

        $my_menus = Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 0])->all();
        $my_menus_items = ArrayHelper::map($my_menus, 'id', 'name');
        $first_menu = Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 0])->one();
        $menu_cycle_count = $first_menu->cycle;
        $menu_cycle = [];
        $menu_cycle[0] = 'Показать за все недели';
        for ($i = 1; $i <= $menu_cycle_count; $i++)
        {
            $menu_cycle[$i] = $i;//массив из подходящи циклов
        }

        $my_menus = Menus::findOne($menu_id);
        $menu_cycle_count = $my_menus->cycle;
        $menu_cycle = [];
        for ($i = 1; $i <= $menu_cycle_count; $i++)
        {
            $menu_cycle[$i] = $i;//массив из подходящи циклов
        }
        $my_days = MenusDays::find()->where(['menu_id' => $menu_id])->all();
        foreach ($my_days as $m_day)
        {
            $ids[] = $m_day->days_id;
        }
        $days = Days::find()->where(['id' => $ids])->all();

        $style_heder = array(
            'font' => array(
                'name' => 'Times New Roman',
                'size' => 12,
                'bold' => true,
            )
        );
        require '../../vendor/autoload.php';
        $array_num = [
            //'A',
            //'B',
            //'C','D',
            'E','F','G','H','I','J','K','L','M','N','O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS',
            'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ', 'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ', 'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ', 'EA', 'EB', 'EC', 'ED', 'EE', 'EF', 'EG', 'EH', 'EI', 'EJ', 'EK', 'EL', 'EM', 'EN', 'EO', 'EP', 'EQ', 'ER', 'ES', 'ET', 'EU', 'EV', 'EW', 'EX', 'EY', 'EZ', 'FA', 'FB', 'FC', 'FD', 'FE', 'FF', 'FG', 'FH', 'FI', 'FJ', 'FK', 'FL', 'FM', 'FN', 'FO', 'FP', 'FQ', 'FR', 'FS', 'FT', 'FU', 'FV', 'FW', 'FX', 'FY', 'FZ',
        ];
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells("A2:A3");
        $sheet->setCellValue("A2", "Прием пищи");

        $sheet->mergeCells("B2:B3");
        $sheet->setCellValue("B2", "№");

        $sheet->mergeCells("C2:C3");
        $sheet->setCellValue("C2", "Категория продукта");

        $sheet->mergeCells("D2:D3");
        $sheet->setCellValue("D2", "Продукт");

        $num = 0;
        foreach ($menu_cycle as $cycle)
        {
            $sheet->setCellValue($array_num[$num] . '2', $cycle . ' неделя ');
            foreach ($days as $day)
            {
                $sheet->setCellValue($array_num[$num] . '3', $day->name);
                $num++;
            }
        }
        $sheet->mergeCells($array_num[$num] . '2' . ':' . $array_num[$num] . '3');
        $sheet->setCellValue($array_num[$num] . '2', 'Итого');
        $num++;
        $sheet->mergeCells($array_num[$num] . '2' . ':' . $array_num[$num] . '3');
        $sheet->setCellValue($array_num[$num] . '2', 'Среднесуточное значение');

        if ($days_id == 0)
        {
            $sheet->mergeCells('A1:' . $array_num[$num] . '1');
            $sheet->setCellValue('A1', 'Перечень продуктов, Брутто, г');
        }
        else
        {
            $sheet->mergeCells('A1:' . $array_num[$num] . '1');
            $sheet->setCellValue('A1', 'Перечень продуктов, Нетто, г');
        }

        $array_num2 = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ', 'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ', 'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ', 'EA', 'EB', 'EC', 'ED', 'EE', 'EF', 'EG', 'EH', 'EI', 'EJ', 'EK', 'EL', 'EM', 'EN', 'EO', 'EP', 'EQ', 'ER', 'ES', 'ET', 'EU', 'EV', 'EW', 'EX', 'EY', 'EZ', 'FA', 'FB', 'FC', 'FD', 'FE', 'FF', 'FG', 'FH', 'FI', 'FJ', 'FK', 'FL', 'FM', 'FN', 'FO', 'FP', 'FQ', 'FR', 'FS', 'FT', 'FU', 'FV', 'FW', 'FX', 'FY', 'FZ',
        ];

        $column = 4;

        $number_row = 1;
        $i = 0;



        foreach ($nutritions as $nutrition)
        {
            $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $menu_id, 'nutrition_id' => $nutrition->nutrition_id])->orderby(['dishes_id' => SORT_ASC])->all();
            $dishes_ids = [];
            $categories_ids = [];

            foreach ($menus_dishes as $m_dish)
            {
                $dishes_products = DishesProducts::find()->where(['dishes_id' => $m_dish->dishes_id])->all();
                foreach ($dishes_products as $d_product)
                {
                    if (!in_array($d_product->products_id, $dishes_ids))
                    {
                        $dishes_ids[] = $d_product->products_id;
                    }
                }
            }
            $products = Products::find()->where(['id' => $dishes_ids])->orderby(['sort' => SORT_ASC])->all();
            $products_count = Products::find()->where(['id' => $dishes_ids])->orderby(['sort' => SORT_ASC])->count();
            foreach ($products as $product)
            {
                if (!in_array($product->products_category_id, $categories_ids))
                {
                    $categories_ids[] = $product->products_category_id;
                }
            }
            $products_categories = ProductsCategory::find()->where(['id' => $categories_ids])->orderby(['sort' => SORT_ASC])->all();

            foreach ($products_categories as $product_cat)
            {

                foreach ($products as $product)
                {

                    if ($product_cat->id == $product->products_category_id)
                    {


                        //$brutto_netto_products в нем будут лежать нормативы по брутто нужных продуктов. Чтобы картоха морковь свекла вывелись по количеству раз равных количеству нормативов брутто
                        //Вся процедура начнется если в выпадающем списке указано вариация брутто-->
                        $brutto_netto_products = [];
                        if ($days_id == 0 && ($product->id == 14 || $product->id == 142 || $product->id == 152))
                        {
                            $brutto_netto_products = \common\models\BruttoNettoKoef::find()->where(['products_id' => $product->id])->all();
                            //print_r($brutto_netto_products);exit;
                        }
                        else
                        {
                            $brutto_netto_products[] = 1;
                        }

                        foreach ($brutto_netto_products as $brutto_netto_product)
                        {


                            $num2 = 0;
                            $totality = 0;
                            $sheet->setCellValue($array_num2[$num2] . $column, NutritionInfo::findOne($nutrition->nutrition_id)->name);
                            $num2++;
                            $sheet->setCellValue($array_num2[$num2] . $column, $number_row);
                            $num2++;
                            $sheet->setCellValue($array_num2[$num2] . $column, $product_cat->name);
                            $num2++;
                            //$sheet->setCellValue($array_num2[$num2] . $column, $product->name);
                            $products_change = ProductsChangeOrganization::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'products_id' => $product->id])->one();
                            if (!empty($products_change))
                            {
                                $val_cel = Products::findOne($products_change->change_products_id)->name;
                                $sheet->setCellValue($array_num2[$num2] . $column, $val_cel);
                            }
                            else
                            {
                                //десь к названию продукта выводим продолжительность норматива для каждого пункта-->

                                //КАРТОХА-->
                                if ($days_id == 0 && $brutto_netto_product->products_id == 14 && $brutto_netto_product->season == 2)
                                {
                                    $sheet->setCellValue($array_num2[$num2] . $column, $product->name . '(01.09-31.10)');
                                }
                                elseif ($days_id == 0 && $brutto_netto_product->products_id == 14 && $brutto_netto_product->season == 3)
                                {
                                    $sheet->setCellValue($array_num2[$num2] . $column, $product->name . '(31.10-31.12)');
                                }
                                elseif ($days_id == 0 && $brutto_netto_product->products_id == 14 && $brutto_netto_product->season == 4)
                                {
                                    $sheet->setCellValue($array_num2[$num2] . $column, $product->name . '(31.12-28.02)');
                                }
                                elseif ($days_id == 0 && $brutto_netto_product->products_id == 14 && $brutto_netto_product->season == 1)
                                {
                                    $sheet->setCellValue($array_num2[$num2] . $column, $product->name . '(29.02-01.09)');
                                    //МОРКОВЬ-->
                                }
                                elseif ($days_id == 0 && $brutto_netto_product->products_id == 142 && $brutto_netto_product->season == 1)
                                {
                                    $sheet->setCellValue($array_num2[$num2] . $column, $product->name . '(01.09-31.12)');
                                }
                                elseif ($days_id == 0 && $brutto_netto_product->products_id == 142 && $brutto_netto_product->season == 3)
                                {
                                    $sheet->setCellValue($array_num2[$num2] . $column, $product->name . '(01.01-31.08)');
                                    //СВЕКЛУХА-->
                                }
                                elseif ($days_id == 0 && $brutto_netto_product->products_id == 152 && $brutto_netto_product->season == 1)
                                {
                                    $sheet->setCellValue($array_num2[$num2] . $column, $product->name . '(01.09-31.12)');
                                }
                                elseif ($days_id == 0 && $brutto_netto_product->products_id == 152 && $brutto_netto_product->season == 3)
                                {
                                    $sheet->setCellValue($array_num2[$num2] . $column, $product->name . '(01.01-31.08)');
                                }
                                else
                                {
                                    $sheet->setCellValue($array_num2[$num2] . $column, $product->name);
                                }
                            }
                            //Заполнение end-->
                            //print_r($number_row);
                            $num2++;
                            foreach ($menu_cycle as $cycle)
                            {
                                foreach ($days as $day)
                                {

                                    $total = $product->get_total_yield_nutrition($product->id, $menu_id, $cycle, $day->id, $nutrition->nutrition_id, $days_id);
                                    if ($total['yield'] != '-' && $days_id == 0 && ($product->id == 14 || $product->id == 142 || $product->id == 152))
                                    {
                                        $total['yield'] = round($total['yield'] * $brutto_netto_product->koeff_netto, 1);
                                    }
                                    $sheet->setCellValue($array_num2[$num2] . $column, $total['yield']);
                                    if ($total['yield'] == '-')
                                    {
                                        $total['yield'] = 0;
                                    }
                                    $totality = $total['yield'] + $totality;
                                    $num2++;
                                }

                            }
                            $sheet->setCellValue($array_num2[$num2] . $column, $totality);
                            $num2++;
                            $sheet->setCellValue($array_num2[$num2] . $column, round($totality / (count($menu_cycle) * count($days)), 2));
                            $column++;
                            $number_row++;
                        }
                    }
                }

            }
        }
        $filename = 'Перечень продуктов.xlsx'; //save our workbook as this file name
        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        die();
    }


    public function actionExportPrognosStorage($menu_id, $normativ, $brutto_netto)
    {
        require_once Yii::$app->basePath . '/Excel/PHPExcel.php';
        //require_once Yii::$app->basePath . '/Excel/PHPExcelIOFactory.php';

        $document = new \PHPExcel();
        //подгружаем готовый шаблон !!!!
        //$document = \PHPExcel_IOFactory::load('../web/images/generator.xlsx');
        ob_start();

        $model4 = new ProductsCategory();
        $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $menu_id])->orderby(['nutrition_id' => SORT_ASC])->all();
        $dishes_ids = [];

        foreach ($menus_dishes as $m_dish)
        {
            $dishes_products = DishesProducts::find()->where(['dishes_id' => $m_dish->dishes_id])->one();
            if (!in_array($dishes_products->dishes_id, $dishes_ids))
            {
                /*Массив используемых продуктов. Продукты пока что не уникальных в 1м из 2х случаях*/
                $dishes_ids[] = $dishes_products->dishes_id;
            }
        }

        $dishes_dishes_products = DishesProducts::find()->where(['dishes_id' => $dishes_ids])->orderby(['id' => SORT_ASC])->all();
        $categories_ids = [];

        foreach ($dishes_dishes_products as $d_d_product)
        {
            $product = Products::find()->where(['id' => $d_d_product->products_id])->one();
            $categories = ProductsCategory::find()->where(['id' => $product->products_category_id])->one();
            if (!in_array($product->products_category_id, $categories_ids))
            {
                $categories_ids[] = $product->products_category_id;
            }
        }

        $categories = ProductsCategory::find()->where(['id' => $categories_ids])->orderby(['sort' => SORT_ASC])->all();

        $menus_nutritions = MenusNutrition::find()->where(['menu_id' => $menu_id])->all();
        $nutrition_ids = [];

        foreach ($menus_nutritions as $m_nutrition)
        {
            $nutrition_ids[] = $m_nutrition->nutrition_id;
        }

        $nutritions = NutritionInfo::find()->where(['id' => $nutrition_ids])->all();

        $my_menus = Menus::findOne($menu_id);
        $menu_cycle_count = $my_menus->cycle;
        $menu_cycle = [];
        for ($i = 1; $i <= $menu_cycle_count; $i++)
        {
            $menu_cycle[$i] = $i;//массив из подходящи циклов
        }

        $my_days = MenusDays::find()->where(['menu_id' => $menu_id])->all();
        foreach ($my_days as $m_day)
        {
            $ids[] = $m_day->days_id;
        }
        $days = Days::find()->where(['id' => $ids])->all();
        $count_my_days = MenusDays::find()->where(['menu_id' => $menu_id])->count() * $my_menus->cycle;

        if ($normativ == 1)
        {
            $values = $model4->get_total_yield_nutrition_category($menu_id);
            if ($brutto_netto == 0)
            {
                $brutto_netto = 'net_weight';
            }
            if ($brutto_netto == 1)
            {
                $brutto_netto = 'gross_weight';
            }
            $m = [];
            foreach ($values as $value)
            {
                $m[$value['products_category_id'] . '_' . $value['nutrition_id']] = $m[$value['products_category_id'] . '_' . $value['nutrition_id']] + ($value[$brutto_netto] * ($value['menus_yield'] / $value['dishes_yield']));
            }
        }

        $array_num = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
        ];

        $num = 2;
        $col = 2;
        $sheet = $document->getActiveSheet();
        $num_st = 2;
        $this->layout = false;

        $sheet->getColumnDimension('A')->setWidth("10");
        $sheet->getColumnDimension('B')->setWidth("60");

        $sheet->getStyle("B1")->getFont()->setBold(true);


        $sheet->setCellValue('B' . 1, $my_menus->name);

        $sheet->setCellValue('A' . $num_st, "№");
        $sheet->setCellValue('B' . $num_st, "Группа продукта");

        if ($normativ == 0)
        {
            foreach ($menu_cycle as $cycle)
            {
                foreach ($days as $day)
                {
                    $sheet->getColumnDimension($array_num[$col] . $num_st)->setWidth("15");
                    $sheet->setCellValue($array_num[$col] . $num_st, $day->name);
                    $col++;
                }
            }
            $sheet->getStyle("A2:" . $array_num[$col] . $num_st)->getFont()->setBold(true);
            $sheet->setCellValue($array_num[$col] . $num_st, "Сумма");
            $col++;
            $sheet->setCellValue($array_num[$col] . $num_st, "Ср.знач.");
            $col++;
        }

        if ($normativ == 1)
        {
            foreach ($nutritions as $nutrition)
            {

                $sheet->getColumnDimension($array_num[$col] . $num_st)->setWidth("15");
                $sheet->setCellValue($array_num[$col] . $num_st, $nutrition->name);
                $col++;

            }
            $sheet->getStyle("A2:" . $array_num[$col] . $num_st)->getFont()->setBold(true);
            $sheet->setCellValue($array_num[$col] . $num_st, "Сутки");
            $col++;
        }


        $col = 2;
        if ($normativ == 0)
        {
            $m = [];
            $values = $model4->get_total_yield_category($menu_id);
            if ($brutto_netto == 0)
            {
                $brutto_netto = 'net_weight';
            }
            if ($brutto_netto == 1)
            {
                $brutto_netto = 'gross_weight';
            }
            foreach ($values as $key => $value)
            {
                $m[$value['products_category_id'] . '_' . $value['cycle'] . '_' . $value['days_id']] = $m[$value['products_category_id'] . '_' . $value['cycle'] . '_' . $value['days_id']] + ($value[$brutto_netto] * ($value['menus_yield'] / $value['dishes_yield']));
            }
        }


        $count = 0;
        foreach ($categories as $category)
        {
            $count++;
            $num++;
            $col = 2;
            $sheet->setCellValue('A' . $num, $count);
            $sheet->setCellValue('B' . $num, $category->name);
            if ($normativ == 0)
            {
                $itog = 0;
                foreach ($menu_cycle as $cycle)
                {
                    foreach ($days as $day)
                    {
                        if (array_key_exists($category->id . '_' . $cycle . '_' . $day->id, $m))
                        {
                            $sheet->setCellValue($array_num[$col] . $num, round($m[$category->id . '_' . $cycle . '_' . $day->id], 1));
                            $itog = $itog + round($m[$category->id . '_' . $cycle . '_' . $day->id], 1);
                            $col++;


                        }
                        else
                        {
                            $sheet->setCellValue($array_num[$col] . $num, '-');
                            $col++;
                        }
                    }
                }
                $sheet->setCellValue($array_num[$col] . $num, $itog);
                //Стили для данного столбца(жирный текст и красный цвет)
                $sheet->getStyle($array_num[$col] . $num)->getFont()->setBold(true);
                $sheet->getStyle($array_num[$col] . $num)->getFont()->getColor()->setRGB('cf3042');
                //конец стилей
                $col++;
                $sheet->setCellValue($array_num[$col] . $num, round(($itog / $count_my_days), 1));
                //Стили для данного столбца(жирный текст и синий цвет)
                $sheet->getStyle($array_num[$col] . $num)->getFont()->setBold(true);
                $sheet->getStyle($array_num[$col] . $num)->getFont()->getColor()->setRGB('3d30cf');
                //конец стилей
                $col++;
            }
            if ($normativ == 1)
            {
                $itog = 0;
                foreach ($nutritions as $nutrition)
                {
                    if (array_key_exists($category->id . '_' . $nutrition->id, $m))
                    {
                        $sheet->setCellValue($array_num[$col] . $num, round($m[$category->id . '_' . $nutrition->id] / $count_my_days, 1));
                        $itog = $itog + round($m[$category->id . '_' . $nutrition->id] / $count_my_days, 1);
                        $col++;


                    }
                    else
                    {
                        $sheet->setCellValue($array_num[$col] . $num, '-');
                        $col++;
                    }
                }
                $sheet->setCellValue($array_num[$col] . $num, $itog);
                $sheet->getStyle($array_num[$col] . $num)->getFont()->setBold(true);
                $sheet->getStyle($array_num[$col] . $num)->getFont()->getColor()->setRGB('cf3042');
            }

        }
        /*$sheet->getStyle("B".$num)->getFont()->setBold(true);
        $sheet->getStyle("B".$num)->getFont()->getColor()->setRGB('3d30cf');*/


        $filename = 'Отчет_Прогнозная_Ведомость' . date('Y_m_d_H_i', time()) . '.xlsx';
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $objWriter = \PHPExcel_IOFactory::createWriter($document, 'Excel2007');
        $objWriter->save('php://output');
        exit;

    }
}
