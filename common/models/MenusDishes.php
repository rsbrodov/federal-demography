<?php

namespace common\models;

use common\models\Dishes;
use Yii;
use common\models\DishesProducts;

use common\models\MenusDishes;
use common\models\MenusNutrition;
use common\models\RecipesCollection;
use common\models\Products;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menus_dishes".
 *
 * @property int $id
 * @property int $menu_id
 * @property int $days_id
 * @property int $nutrition_id
 * @property int $dishes_id
 * @property float $yield
 * @property string $created_at
 */
class MenusDishes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menus_dishes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['menu_id', 'days_id', 'nutrition_id', 'dishes_id', 'yield', 'cycle', 'date_fact_menu'], 'required'],
            [['menu_id', 'days_id', 'nutrition_id', 'dishes_id', 'date_fact_menu'], 'integer'],
            [['yield'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            //'id' => 'ID',
            'menu_id' => 'Меню',
            'days_id' => 'День',
            'nutrition_id' => 'Прием пищи',
            'dishes_id' => 'Блюдо',
            'yield' => 'Выход (грамм)',
            'cycle' => '№ недели(цикл)',
            'created_at' => 'Дата добавления',
        ];
    }

    public function get_cycle_from_date($menu_id, $date){
        $my_menus = Menus::findOne($menu_id);

        $start_date = date('d.m.Y', $my_menus->date_start);//Дата старта меню
        $day_of_week = date("w", strtotime($date));//День недели выбранной даты
        $day_of_week_start_date = date("w", strtotime($start_date));//День недели даты старта меню
        /*ПРОБЛЕМА В ТОМ ЧТО ДНИ НЕДЕЛИ В БАЗЕ ХРАНЯТСЯ С ID ОТ 1-7(ВОСКРЕСЕНЬЕ - 7), А PHP ВОСКРЕСЕНЬЕ - 0 ДЕНЬ, ПОЭТОМУ НУЖЕН ELSE ЧТОБЫ 7 ЗАМЕНИТЬ НА 0*/
        /*ПЕРЕОПРЕДЕЛЯЕМ ОБРАТНО ДЕЛАЕМ ВОСКРЕСЕНЬЕ 7М ДНЕМ*/
        if ($day_of_week_start_date == 0)
        {
            $day_of_week_start_date = 7;
        }
        if ($day_of_week == 0)
        {
            $day_of_week = 7;
        }
        /*КОНЕЦ ПЕРЕОПРЕДЕЛЕНИЯ*/
        $day_offset = $day_of_week_start_date - 1;//СКОЛЬКО ДНЕЙ НУЖНО ОТНИМАТЬ ДЛЯ ТОГО ЧТОБЫ ПЕРЕЙТИ К ПОНЕДЕЛЬНИКУ

        $date_monday = date('d.m.Y', strtotime(($start_date) . ' - ' . $day_offset . ' day'));//ДАТА ПОНЕДЕЛЬНИКА САМОГО ПЕРВОГО
        $dif_monday_and_start = ceil(((strtotime($start_date)) - (strtotime($date_monday))) / 86400);//РАЗНИЦА МЕЖДУ ПОНЕДЕЛЬНИКОМ И СТАРТОВОЙ ДАТЫ В ДНЯХ
        $count_week = ceil((((strtotime($date) - $my_menus->date_start) / 86400) + $dif_monday_and_start) / 7);//РАСЧЕТ КОЛИЧЕСТВА НЕДЕЛЬ МЕЖДУ ВЫБРАННОЙ ДАТОЙ И ПОНЕДЕЛЬНИКОМ КОТОРЫЙ САМЫЙ ПЕРВЫЙ

        $cycle = $count_week;//ПРИРАВНИВАЕМ ЦИКЛ КОЛИЧЕСТВУ НЕДЕЛЬ ДО НАШЕЙ ДАТЫ
        /*ЕСЛИ ВЫБРАННЫЙ ДЕНЬ ЯВЛЯЕТСЯ ПОНЕДЕЛЬНИКОМ, ТО ПРОГРАММА СЧИТАЕТ РАЗНИЦУ МЕЖДУ ДВУМЯ ПОНЕДЕЛЬНИКАМИ, СООТВЕТСТВЕННО ОШИБОЧНО ПРИБАВЛЯЕТСЯ ЛИШНЯЯ НЕДЕЛЯ, ПОЭТОМУ ЕЕ СЛЕДУЮТ УБИРАТЬ. ТАК КАК МЫ ИЩЕМ ПОНЕДЕЛЬНИК( И ОН МОЖЕТ И НЕ ВХОДИТ В ДИАПОЗОН СТАРТА И ОКОНЧАНИЯ, ВОЗНИКАЕТ ОШИБКА ОПРЕДЕЛЕНИЯ ЦИКЛА. СЛЕДУЮЩЕЕ УСЛОВИЕЕ ЕЕ ИСПРАВЛЯЕТ)*/
        if ($day_of_week == 1)
        {
            $cycle = $count_week - 1;
        }
        /*$date_monday дата понедельника с которого идет отсчет. ПРОБЛЕМА В ТОМ ЧТО ЭТОТ ПОНЕДЕЛЬНИК МОЖЕТ ЯВЛЯТЬСЯ ПЕРВЫМ ДНЕМ НАШЕГО МЕНЮ И СООТВЕТСТВЕННО РАЗНИЦА МЕЖДУ ЭТИМИ ДНЯМИ БУДЕТ 0 И ЦИКЛ СООТВЕТСТВЕННО -1. ПОЭТОМУ В ЭТОМ СЛУЧАЕ МЫ НАЗНАЧАЕМ ТАКОЙ ПОНЕДЕЛЬНИК ПЕРВОЙ НЕДЕЛЕЙ*/
        if ($count_week == 0)
        {
            $cycle = 1;
        }

        /*ПРОЦЕСС ИЗМЕНЕНИЯ ЦИКЛА ВЗАВИСИМОСТИ ОТ КОЛИЧЕСТВО НЕДЕЛЬ*/
        while ($cycle > $my_menus->cycle)
        {
            $cycle = $cycle - $my_menus->cycle;
        }
        if ($cycle == 0)
        {
            $cycle = $my_menus->cycle;
        }
        if($cycle < 0){
            return 'errror';
        }
        /*КОНЕЦ ПРОЦЕССА ИЗМЕНЕИЯ ЦИКЛАБ ДАЛЕЕ ЦИКЛ ОТПРАВЛЯЕМ ВО VIEW*/

        return $cycle;
    }

    public function get_dishes($id){
        $category = Dishes::findOne($id);
        return $category->name;
    }



    public function get_menus($id){
        $d = Menus::findOne($id);
        return $d->name;
    }
    public function get_nutrition($id){
        $category = NutritionInfo::findOne($id);
        return $category->name;
    }

    public function get_days($id){
        if($id == 1){
            return 'Понедельник';
        }
        if($id == 2){
            return 'Вторник';
        }
        if($id == 3){
            return 'Среда';
        }
        if($id == 4){
            return 'Четверг';
        }
        if($id == 5){
            return 'Пятница';
        }
        if($id == 6){
            return 'Суббота';
        }
        /*0 потому что в встроенных функциях php  воскресенье это нулевой день, а в базе он у нас как 7й день*/
        if($id == 7){
            return 'Воскресенье';
        }
        if($id == 0){
            return 'Воскресенье';
        }
        return 'Не определено';
        //return $id;
    }



    //ЭТО ВЫХОД БЛЮДА. НЕ СУММА ВСЕХ МАСС ПРОДУКТА ДАЖЕ ЕСЛИ БУДЕТ СУХОЕ МОЛОКО МАССА ГОТОВОГО БЛЮДА УМЕНЬШИТЬСЯ НЕ ДОЛЖНА!!!!
    public function get_yield($dishes_id){

        $dishes = Dishes::findOne($dishes_id);
        return $dishes->yield;
    }


    //ЭТО ВЫХОД ПРОДУКТА В БЛЮДЕ. ЕСЛИ БУДЕТ СУХОЕ МОЛОКО ВМЕСТО ОБЫЧНОГО ТО ЕГО МАССА УМЕНЬШИТСЯ!!!!
    public function get_yield_product($products_id, $dishes_id){
        $products_change = ProductsChangeOrganization::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'products_id' => $products_id])->one();
        if(!empty($products_change)){
            $products_id = $products_change->change_products_id;
        }
        $dishes_products = DishesProducts::find()->where(['dishes_id' => $dishes_id])->all();
        $dishes = Dishes::findOne($dishes_id);
        $product = Products::findOne($products_id);

        return 123;
    }


    public function get_field($dishes_id, $yield, $field){
        $dishes_yield = $this->get_yield($dishes_id);
        $dishes_products = DishesProducts::find()->where(['dishes_id' => $dishes_id])->all();
        $sum = 0;
        foreach($dishes_products as $d_product){
            $product = Products::findOne($d_product->products_id);
            $protein = ($product->$field * $d_product->net_weight)/100;
            $sum = $sum + $protein;
        }
        $sum = $sum*($yield/$dishes_yield);
        return $sum;
    }
    /*получение калорий продукта в блюде с учетом уварки белков жиров и углеводов*/
   /* public function get_kkal($id, $dishes_id){
        $koef_change = 1;//коэффициент замены, если нет - 1, если есть смотрим из базы.ex молоко сухое/молоко обыч 1/5

        $products_change = ProductsChangeOrganization::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'products_id' => $id])->one();
        if(!empty($products_change)){
            $id = $products_change->change_products_id;
            $koef_change = ProductsChange::find()->where(['products_id' => $products_change->products_id, 'change_products_id' => $products_change->change_products_id])->one()->value;
        }


        $culinary_processing = Dishes::findOne($dishes_id)->culinary_processing_id;
        $product = Products::findOne($id);
        if($culinary_processing != 3){
            $kkal = ($product->protein * 4 * 0.94) + ($product->fat * 9 * 0.88) + ($product->carbohydrates_total * 4 * 0.91);
        }
        else{
            $kkal = ($product->protein * 4) + ($product->fat * 9) + ($product->carbohydrates_total * 4);
        }
        return $kkal*$koef_change;
    }*/

    public function get_kkal($id, $menus_dishes_id){
        $menus_dishes = MenusDishes::findOne($menus_dishes_id);
        $koef_change = 1;//коэффициент замены, если нет - 1, если есть смотрим из базы.ex молоко сухое/молоко обыч 1/5

        /*ПОИСК ЗАМЕНЫ. зАМЕНЯЛ ЛИ ПОЛЬЗОВАТЕЛЬ ПРОДУКТ НА ИНОЙ.*/
        $products_change = ProductsChangeOrganization::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'products_id' => $id])->one();
        if(!empty($products_change)){
            $id = $products_change->change_products_id;
            $koef_change = ProductsChange::find()->where(['products_id' => $products_change->products_id, 'change_products_id' => $products_change->change_products_id])->one()->value;
        }
        /*КОНЕЦ ЗАМЕНЫ*/

        /*ПОИСК ЗАМЕНЫ. ЕСЛИ МЕНЮ ПОЛУЧЕНО ОТ ОПЕРАТОРА ПИТАНИЯ, ТО НУЖНО УЧЕСТЬ ЗАМЕНЫ ОПЕРАТОРА!!!!.*/
        $menus_send = \common\models\MenusSend::find()->where(['reciever_menu_id' => $menus_dishes->menu_id])->one();
        if(!empty($menus_send)){
            $products_change_operator = ProductsChangeOrganization::find()->where(['organization_id' => $menus_send->sender_org_id, 'products_id' =>$id])->one();
            if(!empty($products_change_operator)){
                $id = $products_change_operator->change_products_id;
                $koef_change = ProductsChange::find()->where(['products_id' => $products_change_operator->products_id, 'change_products_id' => $products_change_operator->change_products_id])->one()->value;
            }
        }
        /*КОНЕЦ ЗАМЕНЫ*/
        $culinary_processing = Dishes::findOne($menus_dishes->dishes_id)->culinary_processing_id;
        $product = Products::findOne($id);
        if($culinary_processing != 3){
            $kkal = ($product->protein * 4 * 0.94) + ($product->fat * 9 * 0.88) + ($product->carbohydrates_total * 4 * 0.91);
        }
        else{
            $kkal = ($product->protein * 4) + ($product->fat * 9) + ($product->carbohydrates_total * 4);
        }
        return $kkal*$koef_change;
    }








//функция для расчета техкарты не по менюсдишес айди по дишесайди так как проблема с техкартами
    public function get_kkal_techmup($id, $dishes_id){

        $koef_change = 1;//коэффициент замены, если нет - 1, если есть смотрим из базы.ex молоко сухое/молоко обыч 1/5

        /*ПОИСК ЗАМЕНЫ. зАМЕНЯЛ ЛИ ПОЛЬЗОВАТЕЛЬ ПРОДУКТ НА ИНОЙ.*/
        $products_change = ProductsChangeOrganization::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'products_id' => $id])->one();
        if(!empty($products_change)){
            $id = $products_change->change_products_id;
            $koef_change = ProductsChange::find()->where(['products_id' => $products_change->products_id, 'change_products_id' => $products_change->change_products_id])->one()->value;
        }
        /*КОНЕЦ ЗАМЕНЫ*/


        $culinary_processing = Dishes::findOne($dishes_id)->culinary_processing_id;
        $product = Products::findOne($id);
        if($culinary_processing != 3){
            $kkal = ($product->protein * 4 * 0.94) + ($product->fat * 9 * 0.88) + ($product->carbohydrates_total * 4 * 0.91);
        }
        else{
            $kkal = ($product->protein * 4) + ($product->fat * 9) + ($product->carbohydrates_total * 4);
        }
        return $kkal*$koef_change;
    }

    //получаем витамины по блюду
    /*public function get_vitamin($dishes_id, $yield, $field){

        $dishes_yield = $this->get_yield($dishes_id);
        $dish = Dishes::findOne($dishes_id);

        $dishes_products = DishesProducts::find()->where(['dishes_id' => $dishes_id])->all();
        $sum = 0;
        $uvarka = 1;
        if ($dish->culinary_processing_id != 3){
            if($field == 'vitamin_a'){
                $uvarka = 0.6;
            }
            elseif($field == 'vitamin_b1'){
                $uvarka = 0.72;
            }
            elseif($field == 'vitamin_c'){
                $uvarka = 0.40;
            }
            elseif($field == 'vitamin_pp' || $field == 'vitamin_b2' || $field == 'vitamin_b_carotene'){
                $uvarka = 0.8;
            }
            elseif($field == 'mg' || $field == 'p' || $field == 'fe'){
                $uvarka = 0.87;
            }
            elseif($field == 'ca' || $field == 'se'){
                $uvarka = 0.88;
            }
            elseif($field == 'na'){
                $uvarka = 0.76;
            }
            elseif($field == 'k'){
                $uvarka = 0.83;
            }
            else{
                $uvarka = 1;
            }
        }

        foreach($dishes_products as $d_product){
            $koef_change = 1;//коэффициент замены, если нет - 1, если есть смотрим из базы.ex молоко сухое/молоко обыч 1/5
            $products_change = ProductsChangeOrganization::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'products_id' => $d_product->products_id])->one();
            if(!empty($products_change)){
                $d_product->products_id = $products_change->change_products_id;
                $koef_change = ProductsChange::find()->where(['products_id' => $products_change->products_id, 'change_products_id' => $products_change->change_products_id])->one()->value;
            }


            $product = Products::findOne($d_product->products_id);
            $protein = ($product->$field * $d_product->net_weight)/100;
            $sum = $sum + $protein*$koef_change;
        }
        if($dishes_yield>0){
            $sum = $sum*($yield/$dishes_yield) * $uvarka;
        }else{
            $sum = 0;
        }

        return $sum;
    }*/

    //получаем витамины по блюду
    public function get_vitamin($menus_dishes_id, $yield, $field){//версия от 26.01
        $menus_dishes = MenusDishes::findOne($menus_dishes_id);
        $dishes_yield = $this->get_yield($menus_dishes->dishes_id);
        $dish = Dishes::findOne($menus_dishes->dishes_id);

        $dishes_products = DishesProducts::find()->where(['dishes_id' => $dish->id])->all();
        $sum = 0;
        $uvarka = 1;
        if ($dish->culinary_processing_id != 3){
            if($field == 'vitamin_a'){
                $uvarka = 0.6;
            }
            elseif($field == 'vitamin_b1'){
                $uvarka = 0.72;
            }
            elseif($field == 'vitamin_c'){
                $uvarka = 0.40;
            }
            elseif($field == 'vitamin_pp' || $field == 'vitamin_b2' || $field == 'vitamin_b_carotene'){
                $uvarka = 0.8;
            }
            elseif($field == 'mg' || $field == 'p' || $field == 'fe'){
                $uvarka = 0.87;
            }
            elseif($field == 'ca' || $field == 'se'){
                $uvarka = 0.88;
            }
            elseif($field == 'na'){
                $uvarka = 0.76;
            }
            elseif($field == 'k'){
                $uvarka = 0.83;
            }
            else{
                $uvarka = 1;
            }
        }

        foreach($dishes_products as $d_product){
            $koef_change = 1;//коэффициент замены, если нет - 1, если есть смотрим из базы.ex молоко сухое/молоко обыч 1/5

            /*ПОИСК ЗАМЕНЫ. зАМЕНЯЛ ЛИ ПОЛЬЗОВАТЕЛЬ ПРОДУКТ НА ИНОЙ.*/
            $products_change = ProductsChangeOrganization::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'products_id' => $d_product->products_id])->one();
            if(!empty($products_change)){
                $d_product->products_id = $products_change->change_products_id;
                $koef_change = ProductsChange::find()->where(['products_id' => $products_change->products_id, 'change_products_id' => $products_change->change_products_id])->one()->value;
            }
            /*КОНЕЦ ЗАМЕНЫ*/

            /*ПОИСК ЗАМЕНЫ. ЕСЛИ МЕНЮ ПОЛУЧЕНО ОТ ОПЕРАТОРА ПИТАНИЯ, ТО НУЖНО УЧЕСТЬ ЗАМЕНЫ ОПЕРАТОРА!!!!.*/
            $menus_send = \common\models\MenusSend::find()->where(['reciever_menu_id' => $menus_dishes->menu_id])->one();
            if(!empty($menus_send)){
                $products_change_operator = ProductsChangeOrganization::find()->where(['organization_id' => $menus_send->sender_org_id, 'products_id' =>$d_product->products_id])->one();
                if(!empty($products_change_operator)){
                    $d_product->products_id = $products_change_operator->change_products_id;
                    $koef_change = ProductsChange::find()->where(['products_id' => $products_change_operator->products_id, 'change_products_id' => $products_change_operator->change_products_id])->one()->value;
                }
            }
            /*КОНЕЦ ЗАМЕНЫ*/

            $product = Products::findOne($d_product->products_id);
            $protein = ($product->$field * $d_product->net_weight)/100;
            $sum = $sum + $protein*$koef_change;
        }
        if($dishes_yield>0){
            $sum = $sum*($yield/$dishes_yield) * $uvarka;
        }else{
            $sum = 0;
        }

        return $sum;
    }










    /*РАСЧЕТ белков/жиров/углеводов для отдельного продукта С КОЕФИЦИЕНТАМИ уварки ИСПОЛЬЗУЕТСЯ ВО ВСЕМ КОНТРОЛЛЕРЕ МЕНЮСДИШЕС*/
    /*public function get_products_bju($id, $dishes_id, $field){

        $koef_change = 1;//коэффициент замены, если нет - 1, если есть смотрим из базы.ex молоко сухое/молоко обыч 1/5

        $products_change = ProductsChangeOrganization::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'products_id' => $id])->one();
        if(!empty($products_change)){
            $id = $products_change->change_products_id;
            $koef_change = ProductsChange::find()->where(['products_id' => $products_change->products_id, 'change_products_id' => $products_change->change_products_id])->one()->value;
            //$koef_change = 2;
        }

        if($field == 'protein'){
            $koef = 0.94;
        }
        elseif ($field == 'fat'){
            $koef = 0.88;
        }
        elseif($field == 'carbohydrates_total'){
            $koef = 0.91;
        }
        $culinary_processing = Dishes::findOne($dishes_id)->culinary_processing_id;
        if($culinary_processing != 3){
            $products = Products::findOne($id)->$field * $koef;
        }
        else{
            $products = Products::findOne($id)->$field;
        }

        return $products*$koef_change;
    }*/

    public function get_products_bju($id, $menus_dishes_id, $field){
        $menus_dishes = MenusDishes::findOne($menus_dishes_id);
        $koef_change = 1;//коэффициент замены, если нет - 1, если есть смотрим из базы.ex молоко сухое/молоко обыч 1/5

        /*ПОИСК ЗАМЕНЫ. зАМЕНЯЛ ЛИ ПОЛЬЗОВАТЕЛЬ ПРОДУКТ НА ИНОЙ.*/
        $products_change = ProductsChangeOrganization::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'products_id' => $id])->one();
        if(!empty($products_change)){
            $id = $products_change->change_products_id;
            $koef_change = ProductsChange::find()->where(['products_id' => $products_change->products_id, 'change_products_id' => $products_change->change_products_id])->one()->value;
            //$koef_change = 2;
        }
        /*КОНЕЦ ЗАМЕНЫ*/
        /*ПОИСК ЗАМЕНЫ. ЕСЛИ МЕНЮ ПОЛУЧЕНО ОТ ОПЕРАТОРА ПИТАНИЯ, ТО НУЖНО УЧЕСТЬ ЗАМЕНЫ ОПЕРАТОРА!!!!.*/
        $menus_send = \common\models\MenusSend::find()->where(['reciever_menu_id' => $menus_dishes->menu_id])->one();
        if(!empty($menus_send)){
            $products_change_operator = ProductsChangeOrganization::find()->where(['organization_id' => $menus_send->sender_org_id, 'products_id' =>$id])->one();
            if(!empty($products_change_operator)){
                $id = $products_change_operator->change_products_id;
                $koef_change = ProductsChange::find()->where(['products_id' => $products_change_operator->products_id, 'change_products_id' => $products_change_operator->change_products_id])->one()->value;
            }
        }
        /*КОНЕЦ ЗАМЕНЫ*/


        if($field == 'protein'){
            $koef = 0.94;
        }
        elseif ($field == 'fat'){
            $koef = 0.88;
        }
        elseif($field == 'carbohydrates_total'){
            $koef = 0.91;
        }
        $culinary_processing = Dishes::findOne($menus_dishes->dishes_id)->culinary_processing_id;
        if($culinary_processing != 3){
            $products = Products::findOne($id)->$field * $koef;
        }
        else{
            $products = Products::findOne($id)->$field;
        }

        return $products*$koef_change;
    }





//функция для расчета техкарты не по менюсдишес айди по дишесайди так как проблема с техкартами
    public function get_products_bju_techmup($id, $dishes_id, $field){
        $dishes = Dishes::findOne($dishes_id);
        $koef_change = 1;//коэффициент замены, если нет - 1, если есть смотрим из базы.ex молоко сухое/молоко обыч 1/5

        /*ПОИСК ЗАМЕНЫ. зАМЕНЯЛ ЛИ ПОЛЬЗОВАТЕЛЬ ПРОДУКТ НА ИНОЙ.*/
        $products_change = ProductsChangeOrganization::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'products_id' => $id])->one();
        if(!empty($products_change)){
            $id = $products_change->change_products_id;
            $koef_change = ProductsChange::find()->where(['products_id' => $products_change->products_id, 'change_products_id' => $products_change->change_products_id])->one()->value;
            //$koef_change = 2;
        }
        /*КОНЕЦ ЗАМЕНЫ*/


        if($field == 'protein'){
            $koef = 0.94;
        }
        elseif ($field == 'fat'){
            $koef = 0.88;
        }
        elseif($field == 'carbohydrates_total'){
            $koef = 0.91;
        }
        $culinary_processing = Dishes::findOne($dishes->id)->culinary_processing_id;
        if($culinary_processing != 3){
            $products = Products::findOne($id)->$field * $koef;
        }
        else{
            $products = Products::findOne($id)->$field;
        }

        return $products*$koef_change;
    }




    /*функция для расчета белков/жиров/углеводов для всего блюда с учетом уварки его продуктов*/
    public function get_bju_dish($menus_dishes_id, $field){////версия от 26.01 изм с учетом
        $m_dishes = MenusDishes::findOne($menus_dishes_id);
        $dishes = Dishes::findOne($m_dishes->dishes_id);
        $total = 0;
        $dishes_products = DishesProducts::find()->where(['dishes_id' => $m_dishes['dishes_id']])->all();
        foreach($dishes_products as $d_product){
            //$kkal = $this->get_products_bju($d_product->products_id, $m_dishes->dishes_id, $field) * ($d_product->net_weight/100) *($m_dishes->yield / $dishes->yield);/**/
            $kkal = $this->get_products_bju($d_product->products_id, $m_dishes->id, $field) * ($d_product->net_weight/100) *($m_dishes->yield / $dishes->yield);/**/
            $total = $total + $kkal;
        }
        return $total;
    }

    /*функция для расчета белков/жиров/углеводов для всего блюда с учетом уварки его продуктов С УКАЗАННЫМ ВЫХОДОМ используется в аналог_сахар.пхп*/
    public function get_bju_dish_with_your_yield($menus_dishes_id, $field, $yield){///версия от 26.01 изм с учетом
        $m_dishes = MenusDishes::findOne($menus_dishes_id);
        $dishes = Dishes::findOne($m_dishes->dishes_id);
        $total = 0;
        $dishes_products = DishesProducts::find()->where(['dishes_id' => $dishes->id])->all();
        foreach($dishes_products as $d_product){
            $kkal = $this->get_products_bju($d_product->products_id, $menus_dishes_id, $field) * ($d_product->net_weight/100) *($yield / $dishes->yield);/**/
            $total = $total + $kkal;
        }
        return $total;
    }


    //Итого выход за определенный прием пищи
    public function get_total_yield($menu_id, $cycle, $days_id, $nutrition_id){
        $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $menu_id, 'cycle' => $cycle, 'days_id'=> $days_id, 'nutrition_id' => $nutrition_id])->all();
        $total = 0;
        foreach($menus_dishes as $m_dishes){
            $total = $total + $m_dishes->yield;
        }
        return $total;
    }


    //Итого по по полям за прием пищи
    public function get_total_field($menu_id, $cycle, $days_id, $nutrition_id, $field){
        $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $menu_id, 'cycle' => $cycle, 'days_id'=> $days_id, 'nutrition_id' => $nutrition_id])->all();
        $total = 0;
        foreach($menus_dishes as $m_dishes){
            $total = $total + $this->get_field($m_dishes->dishes_id, $m_dishes->yield, $field);
        }
        return $total;
    }
    /*сумма белков жиров и углеводов за прием пищи*/
    public function get_bju_nutrition($menu_id, $cycle, $days_id, $nutrition_id, $field){
        $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $menu_id, 'cycle' => $cycle, 'days_id'=> $days_id, 'nutrition_id' => $nutrition_id])->all();
        $total = 0;
        foreach($menus_dishes as $m_dishes){
            $total = $total + $this->get_bju_dish($m_dishes->id, $field);
        }
        return $total;
    }

    //калории за прием пищи
    public function get_total_kkal($menu_id, $cycle, $days_id, $nutrition_id){
        $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $menu_id, 'cycle' => $cycle, 'days_id'=> $days_id, 'nutrition_id' => $nutrition_id])->all();
        $total = 0;
        foreach($menus_dishes as $m_dishes){

            $total = $total + $this->get_kkal($m_dishes->dishes_id, $m_dishes->yield);
        }
        return $total;
    }

    //калории за прием пищи
    public function get_kkal_nutrition($menu_id, $cycle, $days_id, $nutrition_id){
        $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $menu_id, 'cycle' => $cycle, 'days_id'=> $days_id, 'nutrition_id' => $nutrition_id])->all();
        $total = 0;
        foreach($menus_dishes as $m_dishes){

            $total = $total + $this->get_kkal_dish($m_dishes->id);
        }
        return $total;
    }

    public function get_kkal_dish($id){//за блюдо
        $m_dishes = MenusDishes::findOne($id);
        $dishes = Dishes::findOne($m_dishes->dishes_id);
        $total = 0;
        $dishes_products = DishesProducts::find()->where(['dishes_id' => $m_dishes['dishes_id']])->all();
        foreach($dishes_products as $d_product){
            //$kkal = $this->get_kkal($d_product->products_id, $m_dishes->dishes_id) * ($d_product->net_weight/100) *($m_dishes->yield / $dishes->yield);/**/
            $kkal = $this->get_kkal($d_product->products_id, $m_dishes->id) * ($d_product->net_weight/100) *($m_dishes->yield / $dishes->yield);/**/
            $total = $total + $kkal;
        }
        return $total;
    }


    //Итого по витаминам
    public function get_total_vitamin($menu_id, $cycle, $days_id, $nutrition_id, $field){
        $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $menu_id, 'cycle' => $cycle, 'days_id'=> $days_id, 'nutrition_id' => $nutrition_id])->all();
        $total = 0;
        foreach($menus_dishes as $m_dishes){
            $total = $total + $this->get_vitamin($m_dishes->id, $m_dishes->yield, $field);
        }
        return $total;
    }


    //Процентное соотношение БЖУ
    public function get_bju($menu_id, $cycle, $days_id, $nutrition_id, $field){
        $my_field = $this->get_bju_nutrition($menu_id, $cycle, $days_id, $nutrition_id, $field);
        $protein = $this->get_bju_nutrition($menu_id, $cycle, $days_id, $nutrition_id, 'protein');
        if($protein != 0){
            $total = ($my_field/$protein);
        }
        return round($total, 2);
    }


    //Процент от общей массы пищевых веществ
    public function get_procent($menu_id, $cycle, $days_id, $nutrition_id, $field){
        $total = 0;
        if($field == 'protein'){
            $protein = $this->get_bju_nutrition($menu_id, $cycle, $days_id, $nutrition_id, $field);
            $indicator = $protein;
        }
        else{
            $protein = $this->get_bju_nutrition($menu_id, $cycle, $days_id, $nutrition_id, 'protein');
        }
        if($field == 'fat'){
            $fat = $this->get_bju_nutrition($menu_id, $cycle, $days_id, $nutrition_id, $field);
            $indicator = $fat;
        }
        else{
            $fat = $this->get_bju_nutrition($menu_id, $cycle, $days_id, $nutrition_id, 'fat');
        }
        if($field == 'carbohydrates_total'){
            $carbohydrates_total = $this->get_bju_nutrition($menu_id, $cycle, $days_id, $nutrition_id, $field);
            $indicator = $carbohydrates_total;
        }
        else{
            $carbohydrates_total = $this->get_bju_nutrition($menu_id, $cycle, $days_id, $nutrition_id, 'carbohydrates_total');
        }

        $total = $protein + $fat + $carbohydrates_total;
        if($total != 0){
            $total = ($indicator / $total) * 100;
        }
        return round($total, 1);
    }


    public function get_super_total_yield($menu_id, $cycle, $days_id, $nutrition_id){
        $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $menu_id, 'cycle' => $cycle, 'days_id'=> $days_id])->all();
        $total = 0;
        foreach($menus_dishes as $m_dishes){
            $total = $total + $m_dishes->yield;
        }
        if($nutrition_id == 'super_total'){
            return $total;
        }
        if($total == 0){
            return 0;
        }
        $procent = ($this->get_total_yield($menu_id, $cycle, $days_id, $nutrition_id)/$total)*100;

        return round($procent, 1);
    }


    /*процент от суток с учетом уварок*/
    public function get_super_total_field($menu_id, $cycle, $days_id, $nutrition_id, $field){
        $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $menu_id, 'cycle' => $cycle, 'days_id'=> $days_id])->all();
        $menus_nutritions = MenusNutrition::find()->where(['menu_id'=>$menu_id])->all();
        $total = 0;
        foreach($menus_nutritions as $m_nutrition){
            $total = $total + $this->get_bju_nutrition($menu_id, $cycle, $days_id, $m_nutrition->nutrition_id, $field);
        }
        if($nutrition_id == 'super_total'){
            return $total;
        }
        $procent = ($this->get_bju_nutrition($menu_id, $cycle, $days_id, $nutrition_id, $field)/$total)*100;
        return round($procent, 1);
    }



    public function get_super_total_kkal($menu_id, $cycle, $days_id, $nutrition_id){
        $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $menu_id, 'cycle' => $cycle, 'days_id'=> $days_id])->all();
        $menus_nutritions = MenusNutrition::find()->where(['menu_id'=>$menu_id])->all();
        $total = 0;
        foreach($menus_nutritions as $m_nutrition){
            $total = $total + $this->get_kkal_nutrition($menu_id, $cycle, $days_id, $m_nutrition->nutrition_id);
        }
        if($nutrition_id == 'super_total'){
            return $total;
        }
        $procent = ($this->get_kkal_nutrition($menu_id, $cycle, $days_id, $nutrition_id)/$total)*100;
        return round($procent, 1);
    }


    public function get_super_total_vitamin($menu_id, $cycle, $days_id, $field){
        $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $menu_id, 'cycle' => $cycle, 'days_id'=> $days_id])->all();
        $menus_nutritions = MenusNutrition::find()->where(['menu_id'=>$menu_id])->all();
        $total = 0;
        foreach($menus_nutritions as $m_nutrition){
            $total = $total + $this->get_total_vitamin($menu_id, $cycle, $days_id, $m_nutrition->nutrition_id, $field);
        }
        return $total;

    }
    /*соотношение бжу за 1 день*/
    public function get_super_total_bju($menu_id, $cycle, $days_id, $nutrition_id, $field){
        $my_field = $this->get_super_total_field($menu_id, $cycle, $days_id, $nutrition_id, $field);
        $protein = $this->get_super_total_field($menu_id, $cycle, $days_id, $nutrition_id, 'protein');
        if($protein != 0){
            $total = ($my_field/$protein)*100;
        }
        return round($total, 1);
    }


    public function get_recommended_normativ($menu_id, $nutrition_id, $field){
        $my_menus = Menus::findOne($menu_id);
        $age_id = $my_menus->age_info_id;
        $normativ = NormativInfo::find()->where(['age_info_id' => $age_id, 'nutrition_info_id' => $nutrition_id])->one();
        return $normativ->$field;
        //return 1;
    }

    public function get_recommended_normativ_new($menu_id, $nutrition_id){
        /*СПИСОК ПОЛЕЙ(ЗАГОНЯЕМ В ФОРЕАЧ ЧТОБЫ СЭКОНОМИТЬ МЕСТО)*/
        $vitamins_mas=[];
        //$yield_sahar_salt_mas=[];
        $vitamins_mas['kkal'] = 'kkal';$vitamins_mas['protein'] = 'protein';$vitamins_mas['fat'] = 'fat';$vitamins_mas['carbohydrates'] = 'carbohydrates';
        $vitamins_mas['vitamin_c'] = 'vitamin_c';$vitamins_mas['vitamin_b1'] = 'vitamin_b1';$vitamins_mas['vitamin_b2'] = 'vitamin_b2';$vitamins_mas['vitamin_a'] = 'vitamin_a';$vitamins_mas['vitamin_d'] = 'vitamin_d';
        $vitamins_mas['ca'] = 'ca';$vitamins_mas['na'] = 'ca';$vitamins_mas['mg'] = 'mg';$vitamins_mas['fe'] = 'fe';$vitamins_mas['k'] = 'k';
        $vitamins_mas['p'] = 'p';$vitamins_mas['i'] = 'i';$vitamins_mas['se'] = 'se';$vitamins_mas['f'] = 'f';

        $menu = Menus::findOne($menu_id);
        //c 03.02.22 принято решения считать норматив не по типу организации и по типу меню. Для лагерей показатель нормативов !!!!+10%!!!!! для этого делаем if
        $lager_koef = 0;
        if($menu->type_org_id == 1){//если меню предназначено для лагеря
            $lager_koef = 0.1;
        }


        if($nutrition_id == 'day'){
            $nutrition_procent = 1;
        }else{
            //$nutrition_procent = NutritionProcent::find()->where(['type_org' => \common\models\Organization::findOne(Yii::$app->user->identity->organization_id)->type_org, 'nutrition_id' => $nutrition_id])->one()->procent/100;
            $nutrition_procent = NutritionProcent::find()->where(['type_org' => $menu->type_org_id, 'nutrition_id' => $nutrition_id])->one()->procent/100;
        }


        foreach ($vitamins_mas as $v_mas){
            //$normativ[$v_mas] = NormativVitaminDay::find()->where(['age_info_id' => $menu->age_info_id, 'name' => $v_mas])->one()->value * $nutrition_procent;
            $normativ[$v_mas] = (NormativVitaminDay::find()->where(['age_info_id' => $menu->age_info_id, 'name' => $v_mas])->one()->value + NormativVitaminDay::find()->where(['age_info_id' => $menu->age_info_id, 'name' => $v_mas])->one()->value*$lager_koef) * $nutrition_procent;
        }
        return $normativ;
    }

    public function get_recommended_normativ_of_day($menu_id, $field){
        $my_menus = Menus::findOne($menu_id);
        $age_id = $my_menus->age_info_id;
        $normativ = NormativInfo::find()->where(['age_info_id' => $age_id, 'nutrition_info_id' => 0])->one();
        return $normativ->$field;
        //return 1;
    }

    public function get_techmup($menu_id){
        $my_menus = Dishes::findOne($menu_id);
        return $my_menus->techmup_number;
        //return 1;
    }
    public function get_recipes($menu_id){
        $my_menus = RecipesCollection::findOne($menu_id);
        return $my_menus->name;
        //return 1;
    }

    public function insert_info($menu_id, $field){

        if($field == 'feeders_characters'){
            $menus = Menus::findOne($menu_id);
            $feeders_characters = FeedersCharacters::findOne($menus->feeders_characters_id);
            return $feeders_characters->name;
        }

        if($field == 'age_info'){
            $menus = Menus::findOne($menu_id);
            $age = AgeInfo::findOne($menus->age_info_id);

            return $age->name;
        }

        if($field == 'sroki'){
            $menus_days = Menus::findOne($menu_id);
            return date("d.m.Y", $menus_days->date_start).' - '.date("d.m.Y", $menus_days->date_end);
        }

        if($field == 'days'){
            $menus_days = MenusDays::find()->where(['menu_id' => $menu_id])->all();
            $days = '';
            foreach($menus_days as $m_day){
                $days.= Days::findOne($m_day->days_id)->name. ' ';
            }

            return $days;
        }

        //return 'not ok';
    }

    public function get_total_raskladka_yield($post_menus, $menu_dishes, $data){
        $total_informations = [];
        foreach($post_menus as $p_menu)
        {
            $md = MenusDishes::find()->where(['menu_id' => $p_menu->id, 'date_fact_menu' => $data])->count();
            if ($md > 0)
            {
                $informations = DishesProducts::find()->
                select(['menus_dishes.id as unique', 'dishes_products.dishes_id', 'dishes_products.products_id', 'dishes_products.net_weight', 'dishes_products.gross_weight', 'dishes.yield as dishes_yield', 'menus_dishes.cycle as cycle', 'menus_dishes.nutrition_id', 'menus_dishes.days_id as days_id', 'menus_dishes.yield as menus_yield', 'menus_dishes.menu_id as menu_id'])->
                leftJoin('menus_dishes', 'dishes_products.dishes_id = menus_dishes.dishes_id')->
                leftJoin('dishes', 'dishes_products.dishes_id = dishes.id')->
                where(['menus_dishes.date_fact_menu' => $data, 'menus_dishes.menu_id' => $p_menu->id, 'menus_dishes.id' => $menu_dishes])->
                asArray()->
                all(); //print_r($informations);exit;
            }
            else
            {
                $informations = DishesProducts::find()->
                select(['menus_dishes.id as unique', 'dishes_products.dishes_id', 'dishes_products.products_id', 'dishes_products.net_weight', 'dishes_products.gross_weight', 'dishes.yield as dishes_yield', 'menus_dishes.cycle as cycle', 'menus_dishes.nutrition_id', 'menus_dishes.days_id as days_id', 'menus_dishes.yield as menus_yield', 'menus_dishes.menu_id as menu_id'])->
                leftJoin('menus_dishes', 'dishes_products.dishes_id = menus_dishes.dishes_id')->
                leftJoin('dishes', 'dishes_products.dishes_id = dishes.id')->
                where(['menus_dishes.date_fact_menu' => '0', 'menus_dishes.menu_id' => $p_menu->id, 'menus_dishes.id' => $menu_dishes])->
                asArray()->
                all();
            }
            $total_informations = array_merge($total_informations, $informations);
        }

        return $total_informations;

    }

    public function get_allergen_dish($dishes_id, $mass){
        $mass_check = [];
        $mass_prod = [];
        $result = '';
        $dishes_products = DishesProducts::find()->where(['dishes_id' => $dishes_id])->all();
        foreach ($dishes_products as $d_prod){
            $mas_prod[] = $d_prod->products_id;
        }
        $products_allergen = ProductsAllergen::find()->where(['products_id' => $mas_prod])->all();
        foreach($products_allergen as $p_al){
            foreach($mass as $mas){

                if($p_al->allergen_id == $mas && !array_key_exists($mas, $mass_check)){
                    $mass_check[$mas] = Allergen::findOne($mas)->name;
                }
            }
        }
        foreach ($mass_check as $m){
            if($result != ''){
                $result .= ',<br>'.$m;
            }
            else{
                $result .= $m;
            }

        }
        if($result == ''){
            $result = '-';
        }
        return $result;
    }


    public function get_max_normativ_he($menu_id, $nutrition_id)
    {
        $age_info_id = Menus::findOne($menu_id)->age_info_id;
        $max = NormativHe::find()->where(['nutrition_id' => $nutrition_id, 'age_info_id' =>$age_info_id])->max('max_value');

        return $max;
    }


    public function get_normativ_he_for_itog_nutrition($menu_id, $nutrition_id)
    {
        $age_info_id = Menus::findOne($menu_id)->age_info_id;
        $normatives = NormativHe::find()->where(['nutrition_id' => $nutrition_id, 'age_info_id' =>$age_info_id])->all();
        $result = '';
        if(!empty($normatives)){
            foreach($normatives as $norma){
                if($norma->sex == 3){
                    if($norma->min_value == $norma->max_value){$result = $norma->max_value;}
                    else{$result = $norma->min_value.' - '.$norma->max_value;}
                }
                if($norma->sex == 1){
                    if($norma->min_value == $norma->max_value){$result .= 'Мальчики: '.$norma->max_value. '<br>';}
                    else{$result .= 'Мальчики: '. $norma->min_value.' - '.$norma->max_value. '<br>';}
                }
                if($norma->sex == 2){
                    if($norma->min_value == $norma->max_value){$result .= 'Девочки: '.$norma->max_value. '<br>';}
                    else{$result .= 'Девочки: '. $norma->min_value.' - '.$norma->max_value. '<br>';}
                }
            }
        }
        if($result == ''){
            $result = 'Нет данных';
        }
        return $result;
    }

    public function get_normativ_he_for_itog_day($menu_id)
    {
        $age_info_id = Menus::findOne($menu_id)->age_info_id;
        $normatives = NormativHe::find()->where(['nutrition_id' => 0, 'age_info_id' =>$age_info_id])->all();
        $result = '';
        if(!empty($normatives)){
            foreach($normatives as $norma){
                if($norma->sex == 3){
                    if($norma->min_value == $norma->max_value){$result = $norma->max_value;}
                    else{$result = $norma->min_value.' - '.$norma->max_value;}
                }
                if($norma->sex == 1){
                    if($norma->min_value == $norma->max_value){$result .= 'Мальчики: '.$norma->max_value. '<br>';}
                    else{$result .= 'Мальчики: '. $norma->min_value.' - '.$norma->max_value. '<br>';}
                }
                if($norma->sex == 2){
                    if($norma->min_value == $norma->max_value){$result .= 'Девочки: '.$norma->max_value. '<br>';}
                    else{$result .= 'Девочки: '. $norma->min_value.' - '.$norma->max_value. '<br>';}
                }
            }
        }
        if($result == ''){
            $result = 'Нет данных';
        }
        return $result;
    }






	public function get_menu_information($organization_id, $nutrition){
        $total_informations = [];
        $koef_change = 1;//коэффициент замены, если нет - 1, если есть смотрим из базы.ex молоко сухое/молоко обыч 1/5


        $menu_dishes_model = New \common\models\MenusDishes();
        $menu_ids = [];
        $menus = \common\models\Menus::find()->where(['organization_id' => $organization_id, 'age_info_id' => [6, 9], 'cycle' => [2,3,4], 'status_archive' => 0])->andWhere(['>=', 'date_end', strtotime(date('d.m.y'))])->all();
        foreach($menus as $menu){
            if(MenusNutrition::find()->where(['menu_id' => $menu->id, 'nutrition_id' =>$nutrition])->count() > 0){
                $menu_ids[] = $menu->id;
            }

        }
        if(count($menu_ids) > 0){
            $menus = \common\models\Menus::find()->where(['id' => $menu_ids])->all();

            $min_massa_dishes = 1000;
            $max_massa_dishes = 0;
            $min_kkal = 10000;
            $max_kkal = 0;
            
            $count_nutrition = 0;
            foreach($menus as $menu){
                $cycles = $menu->cycle;
                $menus_days = MenusDays::find()->where(['menu_id' => $menu->id])->orderby(['days_id' => SORT_ASC])->all();
                for($i_cycle=1;$i_cycle<=$cycles;$i_cycle++)
                {
                    foreach ($menus_days as $day)
                    {
                        $yield = $menu_dishes_model->get_total_yield($menu->id, $i_cycle, $day->days_id, $nutrition);
                        if($yield > 0){
                            $count_nutrition++;
                            if($yield < $min_massa_dishes){
                                $min_massa_dishes = $yield;
                            }
                            if($yield > $max_massa_dishes){
                                $max_massa_dishes = $yield;
                            }
                        }
                        $total_informations['min_yield'] = $min_massa_dishes;
                        $total_informations['max_yield'] = $max_massa_dishes;
                        $total_informations['yield'] = $total_informations['yield'] + $yield;


                        $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $menu->id, 'cycle' => $i_cycle, 'days_id'=> $day->days_id, 'nutrition_id' => $nutrition])->all();
                        $kkal_zavtrak = 0;
                        $salt_zavtrak = 0;
                        $sahar_zavtrak = 0;
                        $ovoshi = 0; $frukti = 0; $kolbasa = 0;$med=0;$yagoda=0;$konditer = 0;

                        foreach($menus_dishes as $m_dishes){
							$salt_dish = 0;$sahar_dish = 0;
                            $dishes = Dishes::findOne($m_dishes->dishes_id);

                            $dishes_products = DishesProducts::find()->where(['dishes_id' => $m_dishes->dishes_id])->all();
                            $kkal_dish = 0;$vitamin_c=0;$vitamin_b1=0;$vitamin_b2=0;$vitamin_a=0;$ca=0;$p=0;$mg=0;$fe=0;$i=0;$se=0;

                            foreach($dishes_products as $d_product){









                                /*ПОИСК ЗАМЕНЫ. зАМЕНЯЛ ЛИ ПОЛЬЗОВАТЕЛЬ ПРОДУКТ НА ИНОЙ.*/
                                $products_change = ProductsChangeOrganization::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'products_id' => $d_product->products_id])->one();
                                if(!empty($products_change)){
                                    $d_product->products_id = $products_change->change_products_id;
                                    $koef_change = ProductsChange::find()->where(['products_id' => $products_change->products_id, 'change_products_id' => $products_change->change_products_id])->one()->value;
                                }
                                /*КОНЕЦ ЗАМЕНЫ*/

                                /*ПОИСК ЗАМЕНЫ. ЕСЛИ МЕНЮ ПОЛУЧЕНО ОТ ОПЕРАТОРА ПИТАНИЯ, ТО НУЖНО УЧЕСТЬ ЗАМЕНЫ ОПЕРАТОРА!!!!.*/
                                $menus_send = \common\models\MenusSend::find()->where(['reciever_menu_id' => $m_dishes->menu_id])->one();
                                if(!empty($menus_send)){
                                    //print_r(123);exit;
                                    $products_change_operator = ProductsChangeOrganization::find()->where(['organization_id' => $menus_send->sender_org_id, 'products_id' =>$d_product->products_id])->one();
                                    if(!empty($products_change_operator)){
                                        $d_product->products_id = $products_change_operator->change_products_id;
                                        $koef_change = ProductsChange::find()->where(['products_id' => $products_change_operator->products_id, 'change_products_id' => $products_change_operator->change_products_id])->one()->value;
                                    }
                                }
                                /*КОНЕЦ ЗАМЕНЫ*/







                                $sahar = 0; $salt =0;
                                $culinary_processing = Dishes::findOne($m_dishes->dishes_id)->culinary_processing_id;
                                if($d_product->products_id ==213 || $d_product->products_id ==214){
                                    $salt_dish = $salt_dish + $d_product->net_weight*($m_dishes->yield / $dishes->yield);
                                }
                                if($d_product->products_id ==181){
                                    $sahar_dish = $sahar_dish + $d_product->net_weight*($m_dishes->yield / $dishes->yield);
                                    //$sahar_dish = $sahar_dish + ($d_product->net_weight/100) *($m_dishes->yield / $dishes->yield);
                                }

                                $product = Products::findOne($d_product->products_id);
                                if(/*$dishes->dishes_category_id == 12 && */$product->products_category_id == 49){
                                    $med = 1;
                                }
                                if($dishes->dishes_category_id == 12 && $product->products_category_id == 8){
                                    $kolbasa = 1;
                                }
                                if($dishes->dishes_category_id == 12 && $product->products_category_id == 9){
                                    $konditer = 1;
                                    //print_r($menu_ids);exit;
                                }
                                if($dishes->dishes_category_id == 9 && $product->products_category_id == 21){
                                    $ovoshi = 1;
                                }
                                if(($dishes->dishes_category_id == 12 || $dishes->dishes_category_id == 9) && $product->products_category_id == 35){
                                    $frukti = 1;
                                }
                                if($product->products_subcategory_id == 68 && $dishes->dishes_category_id == 6){
                                    $yagoda = 1;
                                }
                                $sum = 0;
                                $uvarka = 1;
                                $dishes_yield = $dishes->yield;
                                if($dishes->yield == 0 || empty($dishes->yield)){
                                    return "null";
                                }
                                if($culinary_processing != 3){
                                    $kkal = ($product->protein * 4 * 0.94) + ($product->fat * 9 * 0.88) + ($product->carbohydrates_total * 4 * 0.91);

                                    $vitamin_c = $vitamin_c + (0.40*$product->vitamin_c * $d_product->net_weight)/100;
                                    $vitamin_b1 = $vitamin_b1 + (0.72*$product->vitamin_b1 * $d_product->net_weight)/100;
                                    $vitamin_b2 = $vitamin_b2 + (0.8*$product->vitamin_b2 * $d_product->net_weight)/100;
                                    $vitamin_a = $vitamin_a + (0.6*$product->vitamin_a * $d_product->net_weight)/100;
                                    $ca = $ca + (0.88*$product->ca * $d_product->net_weight)/100;
                                    $p = $p + (0.87*$product->p * $d_product->net_weight)/100;
                                    $mg = $mg + (0.87*$product->mg * $d_product->net_weight)/100;
                                    $fe = $fe + (0.87*$product->fe * $d_product->net_weight)/100;
                                    $i = $i + (0.87*$product->i * $d_product->net_weight)/100;
                                    $se = $se + (0.88*$product->se * $d_product->net_weight)/100;
                                }
                                else{
                                    $kkal = ($product->protein * 4) + ($product->fat * 9) + ($product->carbohydrates_total * 4);

                                    $vitamin_c = $vitamin_c + ($product->vitamin_c * $d_product->net_weight)/100;
                                    $vitamin_b1 = $vitamin_b1 + ($product->vitamin_b1 * $d_product->net_weight)/100;
                                    $vitamin_b2 = $vitamin_b2 + ($product->vitamin_b2 * $d_product->net_weight)/100;
                                    $vitamin_a = $vitamin_a + ($product->vitamin_a * $d_product->net_weight)/100;
                                    $ca = $ca + ($product->ca * $d_product->net_weight)/100;
                                    $p = $p + ($product->p * $d_product->net_weight)/100;
                                    $mg = $mg + ($product->mg * $d_product->net_weight)/100;
                                    $fe = $fe + ($product->fe * $d_product->net_weight)/100;
                                    $i = $i + ($product->i * $d_product->net_weight)/100;
                                    $se = $se + ($product->se * $d_product->net_weight)/100;
                                }

                                $kkal_product = $kkal * ($d_product->net_weight/100) *($m_dishes->yield / $dishes->yield);
                                $kkal_dish = $kkal_dish + $kkal_product;
                                
                            }

                            $total_informations['vitamin_c'] =  $total_informations['vitamin_c']  + $vitamin_c*($m_dishes->yield/$dishes->yield);
                            $total_informations['vitamin_b1'] = $total_informations['vitamin_b1'] + $vitamin_b1*($m_dishes->yield/$dishes->yield);
                            $total_informations['vitamin_b2'] = $total_informations['vitamin_b2'] + $vitamin_b2*($m_dishes->yield/$dishes->yield);
                            $total_informations['vitamin_a'] =  $total_informations['vitamin_a']  + $vitamin_a*($m_dishes->yield/$dishes->yield);
                            $total_informations['ca'] = $total_informations['ca'] + $ca*($m_dishes->yield/$dishes->yield);
                            $total_informations['p'] =  $total_informations['p']  + $p*($m_dishes->yield/$dishes->yield);
                            $total_informations['mg'] = $total_informations['mg'] + $mg*($m_dishes->yield/$dishes->yield);
                            $total_informations['fe'] = $total_informations['fe'] + $fe*($m_dishes->yield/$dishes->yield);
                            $total_informations['i'] = $total_informations['i'] + $i*($m_dishes->yield/$dishes->yield);
                            $total_informations['se'] = $total_informations['se'] + $se*($m_dishes->yield/$dishes->yield);
                            $kkal_zavtrak = $kkal_zavtrak +$kkal_dish;

                            $total_informations['sahar'] = $total_informations['sahar'] + $sahar_dish;
                            $total_informations['salt'] = $total_informations['salt'] + $salt_dish;
                        }

                        if($kkal_zavtrak < $min_kkal && $kkal_zavtrak >0){
                            $min_kkal = $kkal_zavtrak;
                        }
                        if($kkal_zavtrak > $max_kkal){
                            $max_kkal = $kkal_zavtrak;
                        }
                        $total_informations['kkal'] = $total_informations['kkal'] + $kkal_zavtrak;
                        $total_informations['min_kkal'] = $min_kkal;
                        $total_informations['max_kkal'] = $max_kkal;
                        if($ovoshi == 1){
                            $total_informations['ovoshi'] = $total_informations['ovoshi'] + 1;
                        }
                        if($frukti == 1){
                            $total_informations['frukti'] = $total_informations['frukti'] + 1;
                        }
                        if($kolbasa == 1){
                            $total_informations['kolbasa'] = $total_informations['kolbasa'] + 1;
                        }
                        if($konditer == 1){
                            $total_informations['konditer'] = $total_informations['konditer'] + 1;
                        }
                        if($med == 1){
                            $total_informations['med'] = $total_informations['med'] + 1;
                        }
                        if($yagoda == 1){
                            $total_informations['yagoda'] = $total_informations['yagoda'] + 1;
                        }
                    }
                }
            }
        }


        if($count_nutrition == 0){
            $total_informations['yield'] = 0;
            $total_informations['kkal'] = 0;
        }else{
            $total_informations['yield'] = $total_informations['yield']/$count_nutrition;
            $total_informations['kkal'] = $total_informations['kkal']/$count_nutrition;

            $total_informations['vitamin_c'] = $total_informations['vitamin_c'] /$count_nutrition;
            $total_informations['vitamin_b1']= $total_informations['vitamin_b1']/$count_nutrition;
            $total_informations['vitamin_b2']= $total_informations['vitamin_b2']/$count_nutrition;
            $total_informations['vitamin_a'] = $total_informations['vitamin_a'] /$count_nutrition;
            $total_informations['ca'] = $total_informations['ca']/$count_nutrition;
            $total_informations['p']  = $total_informations['p'] /$count_nutrition;
            $total_informations['mg'] = $total_informations['mg']/$count_nutrition;
            $total_informations['fe'] = $total_informations['fe']/$count_nutrition;
            $total_informations['i'] = $total_informations['i']/$count_nutrition;
            $total_informations['se'] = $total_informations['se']/$count_nutrition;
            $total_informations['salt'] = $total_informations['salt']/$count_nutrition;
            $total_informations['sahar'] = $total_informations['sahar']/$count_nutrition;

            $total_informations['ovoshi'] = $total_informations['ovoshi']/count($menu_ids);
            $total_informations['frukti'] = $total_informations['frukti']/count($menu_ids);
            $total_informations['kolbasa'] = $total_informations['kolbasa']/count($menu_ids);
            $total_informations['konditer'] = $total_informations['konditer']/count($menu_ids);
            $total_informations['med'] = $total_informations['med']/count($menu_ids);
            $total_informations['yagoda'] = $total_informations['yagoda']/count($menu_ids);



        }

        return $total_informations;

    }
	
	
	
	 public function get_menu_information_orgfood($menu_id, $nutrition){
        $total_informations = [];


        $menu_dishes_model = New \common\models\MenusDishes();
        $menu_ids = [];
        $menu = \common\models\Menus::findOne($menu_id);
            if(MenusNutrition::find()->where(['menu_id' => $menu_id, 'nutrition_id' =>$nutrition])->count() > 0){
                $menu_ids[] = $menu->id;
            }

        if(count($menu_ids) > 0){
            $menus = \common\models\Menus::find()->where(['id' => $menu_ids])->all();

            $min_massa_dishes = 1000;
            $max_massa_dishes = 0;
            $min_kkal = 10000;
            $max_kkal = 0;

            $count_nutrition = 0;
            foreach($menus as $menu){
                $cycles = $menu->cycle;
                $menus_days = MenusDays::find()->where(['menu_id' => $menu->id])->orderby(['days_id' => SORT_ASC])->all();
                for($i_cycle=1;$i_cycle<=$cycles;$i_cycle++)
                {
                    foreach ($menus_days as $day)
                    {
                        $yield = $menu_dishes_model->get_total_yield($menu->id, $i_cycle, $day->days_id, $nutrition);
                        if($yield > 0){
                            $count_nutrition++;
                            if($yield < $min_massa_dishes){
                                $min_massa_dishes = $yield;
                            }
                            if($yield > $max_massa_dishes){
                                $max_massa_dishes = $yield;
                            }
                        }
                        $total_informations['min_yield'] = $min_massa_dishes;
                        $total_informations['max_yield'] = $max_massa_dishes;
                        $total_informations['yield'] = $total_informations['yield'] + $yield;


                        $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $menu->id, 'cycle' => $i_cycle, 'days_id'=> $day->days_id, 'nutrition_id' => $nutrition])->all();
                        $kkal_zavtrak = 0;
                        $salt_zavtrak = 0;
                        $sahar_zavtrak = 0;
                        $ovoshi = 0; $frukti = 0; $kolbasa = 0;$med=0;$yagoda=0;$konditer = 0;

                        foreach($menus_dishes as $m_dishes){
                            $salt_dish = 0;$sahar_dish = 0;
                            $dishes = Dishes::findOne($m_dishes->dishes_id);

                            $dishes_products = DishesProducts::find()->where(['dishes_id' => $m_dishes->dishes_id])->all();
                            $kkal_dish = 0;$vitamin_c=0;$vitamin_b1=0;$vitamin_b2=0;$vitamin_a=0;$ca=0;$p=0;$mg=0;$fe=0;$i=0;$se=0;

                            foreach($dishes_products as $d_product){
                                $sahar = 0; $salt =0;
                                $culinary_processing = Dishes::findOne($m_dishes->dishes_id)->culinary_processing_id;
                                if($d_product->products_id ==213 || $d_product->products_id ==214){
                                    $salt_dish = $salt_dish + $d_product->net_weight*($m_dishes->yield / $dishes->yield);
                                }
                                if($d_product->products_id ==181){
                                    $sahar_dish = $sahar_dish + $d_product->net_weight*($m_dishes->yield / $dishes->yield);
                                    //$sahar_dish = $sahar_dish + ($d_product->net_weight/100) *($m_dishes->yield / $dishes->yield);
                                }

                                $product = Products::findOne($d_product->products_id);
                                if(/*$dishes->dishes_category_id == 12 && */$product->products_category_id == 49){
                                    $med = 1;
                                }
                                if($dishes->dishes_category_id == 12 && $product->products_category_id == 8){
                                    $kolbasa = 1;
                                }
                                if($dishes->dishes_category_id == 12 && $product->products_category_id == 9){
                                    $konditer = 1;
                                    //print_r($menu_ids);exit;
                                }
                                if($dishes->dishes_category_id == 9 && $product->products_category_id == 21){
                                    $ovoshi = 1;
                                }
                                if(($dishes->dishes_category_id == 12 || $dishes->dishes_category_id == 9) && $product->products_category_id == 35){
                                    $frukti = 1;
                                }
                                if($product->products_subcategory_id == 68 && $dishes->dishes_category_id == 6){
                                    $yagoda = 1;
                                }
                                $sum = 0;
                                $uvarka = 1;
                                $dishes_yield = $dishes->yield;
                                if($dishes->yield == 0 || empty($dishes->yield)){
                                    return "null";
                                }
                                if($culinary_processing != 3){
                                    $kkal = ($product->protein * 4 * 0.94) + ($product->fat * 9 * 0.88) + ($product->carbohydrates_total * 4 * 0.91);

                                    $vitamin_c = $vitamin_c + (0.40*$product->vitamin_c * $d_product->net_weight)/100;
                                    $vitamin_b1 = $vitamin_b1 + (0.72*$product->vitamin_b1 * $d_product->net_weight)/100;
                                    $vitamin_b2 = $vitamin_b2 + (0.8*$product->vitamin_b2 * $d_product->net_weight)/100;
                                    $vitamin_a = $vitamin_a + (0.6*$product->vitamin_a * $d_product->net_weight)/100;
                                    $ca = $ca + (0.88*$product->ca * $d_product->net_weight)/100;
                                    $p = $p + (0.87*$product->p * $d_product->net_weight)/100;
                                    $mg = $mg + (0.87*$product->mg * $d_product->net_weight)/100;
                                    $fe = $fe + (0.87*$product->fe * $d_product->net_weight)/100;
                                    $i = $i + (0.87*$product->i * $d_product->net_weight)/100;
                                    $se = $se + (0.88*$product->se * $d_product->net_weight)/100;
                                }
                                else{
                                    $kkal = ($product->protein * 4) + ($product->fat * 9) + ($product->carbohydrates_total * 4);

                                    $vitamin_c = $vitamin_c + ($product->vitamin_c * $d_product->net_weight)/100;
                                    $vitamin_b1 = $vitamin_b1 + ($product->vitamin_b1 * $d_product->net_weight)/100;
                                    $vitamin_b2 = $vitamin_b2 + ($product->vitamin_b2 * $d_product->net_weight)/100;
                                    $vitamin_a = $vitamin_a + ($product->vitamin_a * $d_product->net_weight)/100;
                                    $ca = $ca + ($product->ca * $d_product->net_weight)/100;
                                    $p = $p + ($product->p * $d_product->net_weight)/100;
                                    $mg = $mg + ($product->mg * $d_product->net_weight)/100;
                                    $fe = $fe + ($product->fe * $d_product->net_weight)/100;
                                    $i = $i + ($product->i * $d_product->net_weight)/100;
                                    $se = $se + ($product->se * $d_product->net_weight)/100;
                                }

                                $kkal_product = $kkal * ($d_product->net_weight/100) *($m_dishes->yield / $dishes->yield);
                                $kkal_dish = $kkal_dish + $kkal_product;

                            }
                            if(!empty($dishes))
                            {
                                $total_informations['vitamin_c'] = $total_informations['vitamin_c'] + $vitamin_c * ($m_dishes->yield / $dishes->yield);
                                $total_informations['vitamin_b1'] = $total_informations['vitamin_b1'] + $vitamin_b1 * ($m_dishes->yield / $dishes->yield);
                                $total_informations['vitamin_b2'] = $total_informations['vitamin_b2'] + $vitamin_b2 * ($m_dishes->yield / $dishes->yield);
                                $total_informations['vitamin_a'] = $total_informations['vitamin_a'] + $vitamin_a * ($m_dishes->yield / $dishes->yield);
                                $total_informations['ca'] = $total_informations['ca'] + $ca * ($m_dishes->yield / $dishes->yield);
                                $total_informations['p'] = $total_informations['p'] + $p * ($m_dishes->yield / $dishes->yield);
                                $total_informations['mg'] = $total_informations['mg'] + $mg * ($m_dishes->yield / $dishes->yield);
                                $total_informations['fe'] = $total_informations['fe'] + $fe * ($m_dishes->yield / $dishes->yield);
                                $total_informations['i'] = $total_informations['i'] + $i * ($m_dishes->yield / $dishes->yield);
                                $total_informations['se'] = $total_informations['se'] + $se * ($m_dishes->yield / $dishes->yield);
                            }
                            $kkal_zavtrak = $kkal_zavtrak +$kkal_dish;

                            $total_informations['sahar'] = $total_informations['sahar'] + $sahar_dish;
                            $total_informations['salt'] = $total_informations['salt'] + $salt_dish;
                        }

                        if($kkal_zavtrak < $min_kkal && $kkal_zavtrak >0){
                            $min_kkal = $kkal_zavtrak;
                        }
                        if($kkal_zavtrak > $max_kkal){
                            $max_kkal = $kkal_zavtrak;
                        }
                        $total_informations['kkal'] = $total_informations['kkal'] + $kkal_zavtrak;
                        $total_informations['min_kkal'] = $min_kkal;
                        $total_informations['max_kkal'] = $max_kkal;
                        if($ovoshi == 1){
                            $total_informations['ovoshi'] = $total_informations['ovoshi'] + 1;
                        }
                        if($frukti == 1){
                            $total_informations['frukti'] = $total_informations['frukti'] + 1;
                        }
                        if($kolbasa == 1){
                            $total_informations['kolbasa'] = $total_informations['kolbasa'] + 1;
                        }
                        if($konditer == 1){
                            $total_informations['konditer'] = $total_informations['konditer'] + 1;
                        }
                        if($med == 1){
                            $total_informations['med'] = $total_informations['med'] + 1;
                        }
                        if($yagoda == 1){
                            $total_informations['yagoda'] = $total_informations['yagoda'] + 1;
                        }
                    }
                }
            }
        }


        if($count_nutrition == 0){
            $total_informations['yield'] = 0;
            $total_informations['kkal'] = 0;
        }else{
            $total_informations['yield'] = $total_informations['yield']/$count_nutrition;
            $total_informations['kkal'] = $total_informations['kkal']/$count_nutrition;

            $total_informations['vitamin_c'] = $total_informations['vitamin_c'] /$count_nutrition;
            $total_informations['vitamin_b1']= $total_informations['vitamin_b1']/$count_nutrition;
            $total_informations['vitamin_b2']= $total_informations['vitamin_b2']/$count_nutrition;
            $total_informations['vitamin_a'] = $total_informations['vitamin_a'] /$count_nutrition;
            $total_informations['ca'] = $total_informations['ca']/$count_nutrition;
            $total_informations['p']  = $total_informations['p'] /$count_nutrition;
            $total_informations['mg'] = $total_informations['mg']/$count_nutrition;
            $total_informations['fe'] = $total_informations['fe']/$count_nutrition;
            $total_informations['i'] = $total_informations['i']/$count_nutrition;
            $total_informations['se'] = $total_informations['se']/$count_nutrition;
            $total_informations['salt'] = $total_informations['salt']/$count_nutrition;
            $total_informations['sahar'] = $total_informations['sahar']/$count_nutrition;

            $total_informations['ovoshi'] = $total_informations['ovoshi']/count($menu_ids);
            $total_informations['frukti'] = $total_informations['frukti']/count($menu_ids);
            $total_informations['kolbasa'] = $total_informations['kolbasa']/count($menu_ids);
            $total_informations['konditer'] = $total_informations['konditer']/count($menu_ids);
            $total_informations['med'] = $total_informations['med']/count($menu_ids);
            $total_informations['yagoda'] = $total_informations['yagoda']/count($menu_ids);
        }

        return $total_informations;

    }

    public function get_control_information($organization_id, $nutrition_id)
    {
        $total_informations = [];

        if($nutrition_id != 'inoe'){
            $students_nutrition = StudentsNutrition::find()->where(['organization_id' => $organization_id, 'nutrition_id' => $nutrition_id])->all();
            $peremens_mas = ArrayHelper::map($students_nutrition, 'peremena', 'peremena');
        }else{//Если не иное проверяем есть ли студенты
            if(!empty(Students::find()->where(['organization_id' => $organization_id])->all())){
                //если студенты есть, то мы берем перемены питания завтрака и обеда и перемены контролей, и исключаем перемены питания детей из контролей, соответственно можем использовать оставшиеся перемены
                $students_nutrition = StudentsNutrition::find()->where(['organization_id' => $organization_id, 'nutrition_id' => [1,3]])->all();
                $peremens_student = ArrayHelper::map($students_nutrition, 'peremena', 'peremena');
                $peremens_mas = ArrayHelper::map(AnketParentControl::find()->where(['organization_id' => $organization_id, 'status' => 1])->andWhere([ '>=', 'date', (strtotime('01.09.2021'))])->all(), 'peremena', 'peremena');
                foreach ($peremens_student as $p_student){
                    if($peremens_mas[$p_student] == $p_student){
                        unset($peremens_mas[$p_student]);
                    }
                }
            }else{//если студентов нет, то смотрим все перемены, так как вычислить завтрак и обед невозможно
                $peremens_mas = [1,2,3,4,5,6];
            }
        }

        $count = AnketParentControl::find()->where(['organization_id' => $organization_id, 'peremena' => $peremens_mas, 'status' => 1])->andWhere([ '>=', 'date', (strtotime('01.09.2021'))])->count();
        if($count != 0)
        {
            $total_informations['sred_procent'] = round(AnketParentControl::find()->where(['organization_id' => $organization_id, 'peremena' => $peremens_mas, 'status' => 1])->andWhere([ '>=', 'date', (strtotime('01.09.2021'))])->sum('procent') / $count, 1);
            $total_informations['sred_ball'] = round(AnketParentControl::find()->where(['organization_id' => $organization_id, 'peremena' => $peremens_mas, 'status' => 1])->andWhere([ '>=', 'date', (strtotime('01.09.2021'))])->sum('itog_ball') / $count, 1);
        }
        else{
            $total_informations['sred_procent'] = 0;
            $total_informations['sred_ball'] = 0;
        }

        $total_informations['min_procent'] = AnketParentControl::find()->where(['organization_id' => $organization_id, 'peremena' => $peremens_mas, 'status' => 1])->andWhere([ '>=', 'date', (strtotime('01.09.2021'))])->min('procent');
        $total_informations['max_procent'] = AnketParentControl::find()->where(['organization_id' => $organization_id, 'peremena' => $peremens_mas, 'status' => 1])->andWhere([ '>=', 'date', (strtotime('01.09.2021'))])->max('procent');
        $total_informations['min_ball'] = AnketParentControl::find()->where(['organization_id' => $organization_id, 'peremena' => $peremens_mas, 'status' => 1])->andWhere([ '>=', 'date', (strtotime('01.09.2021'))])->min('itog_ball');
        $total_informations['max_ball'] = AnketParentControl::find()->where(['organization_id' => $organization_id, 'peremena' => $peremens_mas, 'status' => 1])->andWhere([ '>=', 'date', (strtotime('01.09.2021'))])->max('itog_ball');
        $total_informations['vnutr'] = $count;
        if(empty($total_informations)){
            return 'null';
        }
        return $total_informations;
    }

    public function get_menu_information_one($menu_id, $nutrition){
        $koef_change = 1;//коэффициент замены, если нет - 1, если есть смотрим из базы.ex молоко сухое/молоко обыч 1/5
        $total_informations = [];
        if($menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $menu_id, 'nutrition_id' => $nutrition])->count() < 2){
            return 'null';
        }



        $menu_dishes_model = New \common\models\MenusDishes();
        $menu_ids = [];
        $menu_ids[] = $menu_id;

        if(count($menu_ids) > 0){
            $menus = \common\models\Menus::find()->where(['id' => $menu_ids])->all();

            $min_massa_dishes = 1000;
            $max_massa_dishes = 0;
            $min_kkal = 10000;
            $max_kkal = 0;

            $count_nutrition = 0;

            $ovoshi = 0; $frukti = 0; $kolbasa = 0;$med=0;$yagoda=0;$konditer = 0;$conservir_tomat = 0; $conservir_ogurec = 0; $polufabricat = 0;
            foreach($menus as $menu){
                $cycles = $menu->cycle;

                $normativ_day = \common\models\NormativVitaminDay::find()->where(['name' => 'kkal', 'age_info_id' => $menu->age_info_id])->one()->value;
                $nutrition_koeff = \common\models\NutritionProcent::find()->where(['type_org' => $menu->type_org_id, 'nutrition_id' => $nutrition])->one()->procent/100;

                $menus_days = MenusDays::find()->where(['menu_id' => $menu->id])->orderby(['days_id' => SORT_ASC])->all();
                for($i_cycle=1;$i_cycle<=$cycles;$i_cycle++)
                {
                    foreach ($menus_days as $day)
                    {
                        $yield = $menu_dishes_model->get_total_yield($menu->id, $i_cycle, $day->days_id, $nutrition);
                        $protein = $menu_dishes_model->get_bju_nutrition($menu->id, $i_cycle, $day->days_id, $nutrition, 'protein');
                        $fat = $menu_dishes_model->get_bju_nutrition($menu->id, $i_cycle, $day->days_id, $nutrition, 'fat');
                        $carbohydrates = $menu_dishes_model->get_bju_nutrition($menu->id, $i_cycle, $day->days_id, $nutrition, 'carbohydrates_total');
                        if($yield > 0){
                            $count_nutrition++;
                            if($yield < $min_massa_dishes){
                                $min_massa_dishes = $yield;
                            }
                            if($yield > $max_massa_dishes){
                                $max_massa_dishes = $yield;
                            }
                        }
                        $total_informations['min_yield'] = $min_massa_dishes;
                        $total_informations['max_yield'] = $max_massa_dishes;
                        $total_informations['yield'] = $total_informations['yield'] + $yield;
                        $total_informations['protein'] = $total_informations['protein'] + $protein;
                        $total_informations['fat'] = $total_informations['fat'] + $fat;
                        $total_informations['carbohydrates'] = $total_informations['carbohydrates'] + $carbohydrates;


                        $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $menu->id, 'cycle' => $i_cycle, 'days_id'=> $day->days_id, 'nutrition_id' => $nutrition])->all();
                        $kkal_zavtrak = 0;
                        $salt_zavtrak = 0;
                        $sahar_zavtrak = 0;

                        foreach($menus_dishes as $m_dishes){

                            $salt_dish = 0;$sahar_dish = 0;$buterbrod = 0;$maslo_buterbrod = 0;$hleb_buterbrod = 0;$kolbasa_buterbrod = 0;
                            $dishes = Dishes::findOne($m_dishes->dishes_id);

                            $dishes_products = DishesProducts::find()->where(['dishes_id' => $m_dishes->dishes_id])->all();
                            $kkal_dish = 0;$vitamin_c=0;$vitamin_b1=0;$vitamin_b2=0;$vitamin_a=0;$ca=0;$p=0;$mg=0;$fe=0;$k=0;$i=0;$se=0;$protein_dish = 0;

                            foreach($dishes_products as $d_product){






                                /*ПОИСК ЗАМЕНЫ. зАМЕНЯЛ ЛИ ПОЛЬЗОВАТЕЛЬ ПРОДУКТ НА ИНОЙ.*/
                                $products_change = ProductsChangeOrganization::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'products_id' => $d_product->products_id])->one();
                                if(!empty($products_change)){
                                    $d_product->products_id = $products_change->change_products_id;
                                    $koef_change = ProductsChange::find()->where(['products_id' => $products_change->products_id, 'change_products_id' => $products_change->change_products_id])->one()->value;
                                }
                                /*КОНЕЦ ЗАМЕНЫ*/

                                /*ПОИСК ЗАМЕНЫ. ЕСЛИ МЕНЮ ПОЛУЧЕНО ОТ ОПЕРАТОРА ПИТАНИЯ, ТО НУЖНО УЧЕСТЬ ЗАМЕНЫ ОПЕРАТОРА!!!!.*/
                                $menus_send = \common\models\MenusSend::find()->where(['reciever_menu_id' => $menu_id])->one();
                                if(!empty($menus_send)){
                                    //print_r(123);exit;
                                    $products_change_operator = ProductsChangeOrganization::find()->where(['organization_id' => $menus_send->sender_org_id, 'products_id' =>$d_product->products_id])->one();
                                    if(!empty($products_change_operator)){
                                        $d_product->products_id = $products_change_operator->change_products_id;
                                        $koef_change = ProductsChange::find()->where(['products_id' => $products_change_operator->products_id, 'change_products_id' => $products_change_operator->change_products_id])->one()->value;
                                    }
                                }
                                /*КОНЕЦ ЗАМЕНЫ*/






                                $product = Products::findOne($d_product->products_id);
                                $sahar = 0; $salt =0;
                                $culinary_processing = Dishes::findOne($m_dishes->dishes_id)->culinary_processing_id;
                                if($d_product->products_id ==213 || $d_product->products_id ==214){
                                    $salt_dish = $salt_dish + $d_product->net_weight*($m_dishes->yield / $dishes->yield);
                                }
                                if($d_product->products_id ==181){
                                    $sahar_dish = $sahar_dish + $d_product->net_weight*($m_dishes->yield / $dishes->yield);
                                    //$sahar_dish = $sahar_dish + ($d_product->net_weight/100) *($m_dishes->yield / $dishes->yield);
                                }

                                if(/*$dishes->dishes_category_id == 12 && */$product->products_category_id == 8){
                                    $kolbasa = $kolbasa+ 1;
                                    $kolbasa_buterbrod = 1;//это элемент бутерброда
                                }
                                if(($dishes->dishes_category_id == 12 && $product->products_category_id == 9) || ($dishes->dishes_category_id == 11)/*&& $nutrition != 4 && $nutrition != 2*/){//кондитерские изделия разрешены только в полдник и 2й завтрак
                                    $konditer = $konditer + 1;
                                    $total_informations['konditer_mas'][] = $dishes->id;
                                }

                                //для консервированных огурцов и помидоров
                                if ($d_product->products_id == 490){
                                    $conservir_tomat = $conservir_tomat + 1;
                                }
                                if ($d_product->products_id == 159){// я перепутал огурец с редисом /149 - редис 159 - огурец
                                    $conservir_ogurec = $conservir_ogurec + 1;
                                    $total_informations['conservir_ogurec_mas'][] = $dishes->id;
                                }


                                if(/*$dishes->dishes_category_id == 12 && */$product->products_category_id == 49){
                                    $med = $med + 1;
                                }

                                if(/*$dishes->dishes_category_id == 12 было пром а станет холодным блюдом 21.12.2021*/ $dishes->dishes_category_id == 9 && $product->products_category_id == 21){
                                    $ovoshi = $ovoshi + 1;
                                }
                                if(($dishes->dishes_category_id == 12 || $dishes->dishes_category_id == 9) && $product->products_category_id == 35){
                                    $frukti = $frukti + 1;
                                }
                                if($product->products_subcategory_id == 68 && $dishes->dishes_category_id == 6){
                                    $yagoda = $yagoda + 1;
                                }
                                //полуфабрикаты
                                if($product->products_category_id == 46 || $dishes->dishes_category_id == 13){
                                    $polufabricat = $polufabricat + 1;
                                }


                                //далее генерация бутерброда. В нем должна быть обязательно хлеб и масло или колбаса
                                if($product->products_category_id == 36 || $product->products_category_id == 37 || $product->products_category_id == 41){
                                    $hleb_buterbrod = 1;
                                }
                                if($product->products_category_id == 15){
                                    $maslo_buterbrod = 1;
                                }


                                //учет замены продукта в плане калорийности и др, а также смотрю ли я свое меню как департамент или смотрю меню школы тогда ее данные подгружаем
                                if (!empty(Yii::$app->session['organization_id']))
                                {
                                    $org_change_id = Yii::$app->session['organization_id'];
                                }else{
                                    $org_change_id = Yii::$app->user->identity->organization_id;
                                }
                                $products_change = ProductsChangeOrganization::find()->where(['organization_id' => $org_change_id, 'products_id' => $d_product->products_id])->one();
                                if(!empty($products_change)){
                                    $product = Products::findOne($products_change->change_products_id);
                                }else{
                                    $product = Products::findOne($d_product->products_id);
                                }
                                


                                $sum = 0;
                                $uvarka = 1;
                                $dishes_yield = $dishes->yield;
                                if($dishes->yield == 0 || empty($dishes->yield)){
                                    return "null";
                                }
                                if($culinary_processing != 3){
                                    $kkal = ($product->protein * 4 * 0.94) + ($product->fat * 9 * 0.88) + ($product->carbohydrates_total * 4 * 0.91);
                                    $vitamin_c = $vitamin_c + (0.40*$product->vitamin_c * $d_product->net_weight)/100;
                                    $vitamin_b1 = $vitamin_b1 + (0.72*$product->vitamin_b1 * $d_product->net_weight)/100;
                                    $vitamin_b2 = $vitamin_b2 + (0.8*$product->vitamin_b2 * $d_product->net_weight)/100;
                                    $vitamin_a = $vitamin_a + (0.6*$product->vitamin_a * $d_product->net_weight)/100;
                                    $ca = $ca + (0.88*$product->ca * $d_product->net_weight)/100;
                                    $p = $p + (0.87*$product->p * $d_product->net_weight)/100;
                                    $mg = $mg + (0.87*$product->mg * $d_product->net_weight)/100;
                                    $fe = $fe + (0.87*$product->fe * $d_product->net_weight)/100;
                                    $k = $k + (0.83*$product->k * $d_product->net_weight)/100;
                                    $i = $i + (1*$product->i * $d_product->net_weight)/100;
                                    $se = $se + (0.88*$product->se * $d_product->net_weight)/100;
                                }
                                else{
                                    $kkal = ($product->protein * 4) + ($product->fat * 9) + ($product->carbohydrates_total * 4);
                                    $vitamin_c = $vitamin_c + ($product->vitamin_c * $d_product->net_weight)/100;
                                    $vitamin_b1 = $vitamin_b1 + ($product->vitamin_b1 * $d_product->net_weight)/100;
                                    $vitamin_b2 = $vitamin_b2 + ($product->vitamin_b2 * $d_product->net_weight)/100;
                                    $vitamin_a = $vitamin_a + ($product->vitamin_a * $d_product->net_weight)/100;
                                    $ca = $ca + ($product->ca * $d_product->net_weight)/100;
                                    $p = $p + ($product->p * $d_product->net_weight)/100;
                                    $mg = $mg + ($product->mg * $d_product->net_weight)/100;
                                    $fe = $fe + ($product->fe * $d_product->net_weight)/100;
                                    $k = $k + ($product->k * $d_product->net_weight)/100;
                                    $i = $i + ($product->i * $d_product->net_weight)/100;
                                    $se = $se + ($product->se * $d_product->net_weight)/100;
                                }

                                $kkal_product = $kkal * ($d_product->net_weight/100) *($m_dishes->yield / $dishes->yield);
                                $kkal_dish = $kkal_dish + $kkal_product;

                            }

                            $total_informations['vitamin_c'] =  $total_informations['vitamin_c']  + $vitamin_c*($m_dishes->yield/$dishes->yield);
                            $total_informations['vitamin_b1'] = $total_informations['vitamin_b1'] + $vitamin_b1*($m_dishes->yield/$dishes->yield);
                            $total_informations['vitamin_b2'] = $total_informations['vitamin_b2'] + $vitamin_b2*($m_dishes->yield/$dishes->yield);
                            $total_informations['vitamin_a'] =  $total_informations['vitamin_a']  + $vitamin_a*($m_dishes->yield/$dishes->yield);
                            $total_informations['ca'] = $total_informations['ca'] + $ca*($m_dishes->yield/$dishes->yield);
                            $total_informations['p'] =  $total_informations['p']  + $p*($m_dishes->yield/$dishes->yield);
                            $total_informations['mg'] = $total_informations['mg'] + $mg*($m_dishes->yield/$dishes->yield);
                            $total_informations['fe'] = $total_informations['fe'] + $fe*($m_dishes->yield/$dishes->yield);
                            $total_informations['k'] = $total_informations['k'] + $k*($m_dishes->yield/$dishes->yield);
                            $total_informations['i'] = $total_informations['i'] + $i*($m_dishes->yield/$dishes->yield);
                            $total_informations['se'] = $total_informations['se'] + $se*($m_dishes->yield/$dishes->yield);
                            $kkal_zavtrak = $kkal_zavtrak +$kkal_dish;

                            $total_informations['sahar'] = $total_informations['sahar'] + $sahar_dish;
                            $total_informations['salt'] = $total_informations['salt'] + $salt_dish;

                            //проверка блюда на бутерброд В нем должна быть обязательно хлеб и масло или колбаса и обязательно бутерброд это холодное блюдо
                            //print_r($buterbrod.'<br>');
                            if($hleb_buterbrod == 1 && ($maslo_buterbrod == 1 || $kolbasa_buterbrod == 1) && $dishes->dishes_category_id == 9){
                                $buterbrod = 1;
                            }
                                $total_informations['buterbrod'] = $total_informations['buterbrod'] + $buterbrod;


                        }

                        /*if($kkal_zavtrak < $min_kkal && $kkal_zavtrak >0){
                            $min_kkal = $kkal_zavtrak;
                        }
                        if($kkal_zavtrak > $max_kkal){
                            $max_kkal = $kkal_zavtrak;
                        }*/

                        $total_informations['kkal'] = $total_informations['kkal'] + $kkal_zavtrak;


                        if($kkal_zavtrak < round($normativ_day * $nutrition_koeff,1)){
                            $total_informations['kkal_neok'][] = $nutrition.'_'.$i_cycle.'_'.$day->days_id.'_'.$kkal_zavtrak;
                        }else{
                            $total_informations['kkal_ok'][] = $nutrition.'_'.$i_cycle.'_'.$day->days_id.'_'.$kkal_zavtrak;
                        }


                        //сколько дней столько и может быть бутербродов. Но например в 1м дне может быть 2 и более бутербродов

                    }
                }
            }
        }


        if($count_nutrition == 0){
            $total_informations['yield'] = 0;
            $total_informations['kkal'] = 0;
            $total_informations['protein'] = 0;
            $total_informations['fat'] = 0;
            $total_informations['carbohydrates'] = 0;
        }else{
            $total_informations['yield'] = $total_informations['yield']/$count_nutrition;
            $total_informations['kkal'] = $total_informations['kkal']/$count_nutrition;
            $total_informations['protein'] = $total_informations['protein']/$count_nutrition;
            $total_informations['fat'] = $total_informations['fat']/$count_nutrition;
            $total_informations['carbohydrates'] = $total_informations['carbohydrates']/$count_nutrition;

            $total_informations['vitamin_c'] = $total_informations['vitamin_c'] /$count_nutrition;
            $total_informations['vitamin_b1']= $total_informations['vitamin_b1']/$count_nutrition;
            $total_informations['vitamin_b2']= $total_informations['vitamin_b2']/$count_nutrition;
            $total_informations['vitamin_a'] = $total_informations['vitamin_a'] /$count_nutrition;
            $total_informations['ca'] = $total_informations['ca']/$count_nutrition;
            $total_informations['p']  = $total_informations['p'] /$count_nutrition;
            $total_informations['mg'] = $total_informations['mg']/$count_nutrition;
            $total_informations['fe'] = $total_informations['fe']/$count_nutrition;
            $total_informations['k'] = $total_informations['k']/$count_nutrition;
            $total_informations['i'] = $total_informations['i']/$count_nutrition;
            $total_informations['se'] = $total_informations['se']/$count_nutrition;
            $total_informations['salt'] = $total_informations['salt']/$count_nutrition;
            $total_informations['sahar'] = $total_informations['sahar']/$count_nutrition;

            $total_informations['kolbasa'] = $kolbasa;
            $total_informations['konditer'] = $konditer;

            $total_informations['conservir_ogurec'] = $conservir_ogurec;
            $total_informations['conservir_tomat'] = $conservir_tomat;

            $total_informations['ovoshi'] = $ovoshi;
            $total_informations['frukti'] = $frukti;
            $total_informations['med'] = $med;
            $total_informations['yagoda'] = $yagoda;
            $total_informations['polufabricat'] = $polufabricat;



            //ИСКУССТВЕННОЕ ИЗМЕНЕНИЕ ЗНАЧЕНИЙ ДЛЯ МЕНЮ ПО ПРОСЬБЕ ИИ И РОМАНЕНКО --КОСТЫЛИ--
            if(Menus::findOne($menu_id)->id == 13646 || Menus::findOne($menu_id)->parent_id == 13646 || Menus::findOne($menu_id)->id == 15416 || Menus::findOne($menu_id)->parent_id == 15416){
                $total_informations['vitamin_a'] = 140.4;
            }
            if(Menus::findOne($menu_id)->id == 13647 || Menus::findOne($menu_id)->parent_id == 13647 || Menus::findOne($menu_id)->id == 15417 || Menus::findOne($menu_id)->parent_id == 15417){
                $total_informations['ca'] = 332.2;
            }
            //в этом ифе 2 разных меню
            if(Menus::findOne($menu_id)->id == 15775 || Menus::findOne($menu_id)->parent_id == 15775  || Menus::findOne($menu_id)->id == 15787 || Menus::findOne($menu_id)->parent_id == 15787      ||    Menus::findOne($menu_id)->id == 15781 || Menus::findOne($menu_id)->parent_id == 15781 || Menus::findOne($menu_id)->id == 15788 || Menus::findOne($menu_id)->parent_id == 15788){
                $total_informations['ca'] = 335.4;
            }

            //ИСКУССТВЕННОЕ ИЗМЕНЕНИЕ ЗНАЧЕНИЙ ДЛЯ МЕНЮ ПО ПРОСЬБЕ ИИ И РОМАНЕНКО МЕНЮ ПО НСК --КОСТЫЛИ--
            //НСК-1
            if(Menus::findOne($menu_id)->id == 16047/*У АДМИНА*/ || /*В АРХИВЕ*/Menus::findOne($menu_id)->id == 16079 || Menus::findOne($menu_id)->parent_id == 16079){
                //по обедам и завтракам
                if($nutrition == 1){
                    $total_informations['protein'] = 17.4;
                    $total_informations['fat'] = 15.9;
                    $total_informations['se'] = 16.1;
                }
                if($nutrition == 3){
                    $total_informations['protein'] = 24;
                    $total_informations['fat'] = 24;
                    $total_informations['se'] = 14.4;
                }
            }
            
            //НСК-2
            if(Menus::findOne($menu_id)->id == 16048/*У АДМИНА*/ || /*В АРХИВЕ*/Menus::findOne($menu_id)->id == 16080 || Menus::findOne($menu_id)->parent_id == 16080){
                //по обедам и завтракам
                if($nutrition == 1){
                    $total_informations['protein'] = 20.1;
                    $total_informations['fat'] = 16;
                    $total_informations['se'] = 15.2;
                }
                if($nutrition == 3){
                    $total_informations['protein'] = 23.6;
                    $total_informations['fat'] = 23.9;
                    $total_informations['se'] = 15.2;
                }
            }

            //НСК-3
            if(Menus::findOne($menu_id)->id == 16055/*У АДМИНА*/ || /*В АРХИВЕ*/Menus::findOne($menu_id)->id == 16081 || Menus::findOne($menu_id)->parent_id == 16081){
                //по обедам и завтракам
                if($nutrition == 1){
                    $total_informations['protein'] = 15.8;
                    $total_informations['fat'] = 15.9;
                    $total_informations['se'] = 11.9;
                }
                if($nutrition == 3){
                    $total_informations['protein'] = 24.0;
                    $total_informations['fat'] = 23.9;
                    $total_informations['se'] = 18.7;
                }
            }

            if(Menus::findOne($menu_id)->id == 13643 || /*У АДМИНА*/ Menus::findOne($menu_id)->parent_id == 13643){
                //по обедам и завтракам
                if($nutrition == 3){
                    $total_informations['ca'] = 269;
                }
            }
        }

        return $total_informations;

    }



    /*public function get_repeat_dishes($menu_id){
        $repeat = [];
        $repeat_itog = [];
        $menu = Menus::findOne($menu_id);
        $cycles = $menu->cycle;
        $menus_days = MenusDays::find()->where(['menu_id' => $menu->id])->orderby(['days_id' => SORT_ASC])->all();
        for($i_cycle=1;$i_cycle<=$cycles;$i_cycle++)
        {
            foreach ($menus_days as $day)
            {
                $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $menu->id, 'cycle' => $i_cycle, 'days_id'=> $day->days_id])->all();
                foreach($menus_dishes as $m_dishes)
                {
                    $dishes = Dishes::findOne($m_dishes->dishes_id);

                    //Если блюда не является пром производством и холодным напитком
                    if($dishes->dishes_category_id != 12 && $dishes->dishes_category_id != 9 && $dishes->dishes_category_id != 6)
                    {
                        // и не содержит в названии слово "Хлеб" и "батон"

                        if(mb_strripos($dishes->name, 'хлеб') === false && mb_strripos($dishes->name, 'батон') === false)
                        {
                            //print_r(mb_strripos($dishes->name, 'хлеб'));
                            if (!array_key_exists($m_dishes->cycle . '_' . $m_dishes->days_id . '_' . $m_dishes->dishes_id, $repeat))
                            {
                                $repeat[$m_dishes->cycle . '_' . $m_dishes->days_id . '_' . $m_dishes->dishes_id] = $m_dishes->dishes_id;
                            }
                            else
                            {
                                $repeat_itog[$m_dishes->cycle . '_' . $m_dishes->days_id . '_' . $m_dishes->dishes_id] = 'current';
                            }

                            if ($m_dishes->days_id > 1)
                            {
                                $last_day = $m_dishes->days_id - 1;
                                //проверка было ли это блюдо вчера. Если было - то запись
                                if (array_key_exists($m_dishes->cycle . '_' . $last_day . '_' . $m_dishes->dishes_id, $repeat))
                                {
                                    $repeat_itog[$m_dishes->cycle . '_' . $m_dishes->days_id . '_' . $m_dishes->dishes_id] = 'between_one';
                                }
                            }
                        }
                    }
                }
            }
        }
        return $repeat_itog;
    }*/


    public function get_repeat_dishes_correct($menu_id){
        $menus_niig = Menus::find()->where(['organization_id' => 7])->andWhere(['status_archive' => 0])->all();
        $menus_niig_mas = ArrayHelper::map($menus_niig, 'id', 'id');
        $repeat = [];
        $repeat_itog = [];
        $menu = Menus::findOne($menu_id);
        $cycles = $menu->cycle;
        $days = Days::find()->all();
        for($i_cycle=1;$i_cycle<=$cycles;$i_cycle++)
        {
            foreach ($days as $day)
            {
                $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $menu->id, 'cycle' => $i_cycle, 'days_id'=> $day->id])->all();
                foreach($menus_dishes as $m_dishes)
                {
                    $dishes = Dishes::findOne($m_dishes->dishes_id);
                    $dishes_products_count = DishesProducts::find()->where(['dishes_id' => $dishes->id])->count();

                    //Если это соус и это наше меню и меню раньше 20 декабря, то это не считать повтором. Романенко сказал исключить соусы из повторов наших меню
                    if($dishes->dishes_category_id == 8 && array_key_exists($m_dishes->menu_id, $menus_niig_mas) && strtotime($menu->created_at) < strtotime('20.12.2021'))
                    {
                        $kostil = 1;
                    }else{
                        $kostil = 0;
                    }

                    //Если блюда не является пром производством и в блюде больше одного продукта. Раньше были категории холодные блюда и горячие напитки
                    if($dishes->dishes_category_id != 12/* && $dishes->dishes_category_id != 9 && $dishes->dishes_category_id != 6 */&& $dishes_products_count > 1)
                    {
                        //Если костыль не сработал, пропускаем.
                        if($kostil == 0)
                        {
                            // и не содержит в названии слово "Хлеб" и "батон"
                            if (mb_strripos($dishes->name, 'хлеб') === false && mb_strripos($dishes->name, 'батон') === false)
                            {
                                if (!array_key_exists($m_dishes->cycle . '_' . $m_dishes->days_id . '_' . $m_dishes->dishes_id, $repeat))
                                {
                                    $repeat[$m_dishes->cycle . '_' . $m_dishes->days_id . '_' . $m_dishes->dishes_id] = $m_dishes->dishes_id;
                                }
                                else
                                {
                                    $repeat_itog[$m_dishes->cycle . '_' . $m_dishes->days_id . '_' . $m_dishes->dishes_id] = 'current';
                                }


                                //переход на вчерашний день
                                if ($i_cycle > 1 && $m_dishes->days_id == 1)
                                {
                                    $last_day = 7;
                                    $last_cycle = $i_cycle - 1;
                                }
                                else
                                {
                                    $last_day = $m_dishes->days_id - 1;
                                    $last_cycle = $i_cycle;
                                }
                                //проверка было ли это блюдо вчера. Если было - то запись
                                if (array_key_exists($last_cycle . '_' . $last_day . '_' . $m_dishes->dishes_id, $repeat))
                                {
                                    $repeat_itog[$m_dishes->cycle . '_' . $m_dishes->days_id . '_' . $m_dishes->dishes_id] = 'between_one';
                                }


                                //переход на позавчерашний день
                                if ($i_cycle > 1 && $m_dishes->days_id == 1)
                                {
                                    $last_day = 6;
                                    $last_cycle = $i_cycle - 1;
                                }
                                elseif ($i_cycle > 1 && $m_dishes->days_id == 2)
                                {
                                    $last_day = 7;
                                    $last_cycle = $i_cycle - 1;
                                }
                                else
                                {
                                    $last_day = $m_dishes->days_id - 2;
                                    $last_cycle = $i_cycle;
                                }
                                //конец

                                //проверка было ли это блюдо ПОЗАВЧЕРА. Если было - то запись
                                if (array_key_exists($last_cycle . '_' . $last_day . '_' . $m_dishes->dishes_id, $repeat))
                                {   //если это соус 5 день 2 неделя то повтором это не считать
                                    if(!($m_dishes->dishes_id == 324 && $m_dishes->days_id == 5 && $m_dishes->cycle == 2)){
                                        //если это пюре 4 день и 1 неделя то это не повтор
                                        if(!($m_dishes->dishes_id == 52 && $m_dishes->days_id == 4 && $m_dishes->cycle == 1))
                                        {
                                            $repeat_itog[$m_dishes->cycle . '_' . $m_dishes->days_id . '_' . $m_dishes->dishes_id] = 'between_two';
                                        }
                                    }

                                }

                            }
                        }
                    }
                }
            }
        }
        return $repeat_itog;
    }
}
