<?
interface MenusInterface{

    public function get_count_download($id);//Количество производных от объекта
    public function get_monitoring_common($id, $field);//Мониторинговые данные
    public function get_total_org($dataProvider, $field);//Футер итог по меню мониторинг
}
?>