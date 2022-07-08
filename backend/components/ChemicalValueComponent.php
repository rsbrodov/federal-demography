<?php
namespace app\components;

use common\models\Organization;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ChemicalValueComponent extends Component{
    public function init(){
        parent::init();
    }

    public function variativity_mas(){
        $variativity_mas = [
            'zavtrak_kashi_garniri_yaich' => 'zavtrak_kashi_garniri_yaich',
            'zavtrak_masn_ryb' => 'zavtrak_masn_ryb',
            'zavtrak_napitki' => 'zavtrak_napitki',
            'zavtrak_souse' => 'zavtrak_souse',
            'obed_pervie' => 'obed_pervie',
            'obed_holod' => 'obed_holod',
            'obed_garniri' => 'obed_garniri',
            'obed_myasn_ryb' => 'obed_myasn_ryb',
            'obed_napitki' => 'obed_napitki',
            'obed_souse' => 'obed_souse',
            'ushin_holod' => 'ushin_holod',
            'ushin_garniri' => 'ushin_garniri',
            'ushin_myas_ryb' => 'ushin_myas_ryb',
            'ushin_napitki' => 'ushin_napitki',
            'ushin_souse' => 'ushin_souse',
        ];
        return $variativity_mas;
    }

    public function days_mas(){
        $days_mas = [
            'days1' => 1,
            'days2' => 2,
            'days3' => 3,
            'days4' => 4,
            'days5' => 5,
            'days6' => 6,
            'days7' => 7,
        ];
        return $days_mas;
    }

    public function nutritions_mas(){
        $nutritions_mas = [
            'nutrition1' => 1,
            'nutrition2' => 2,
            'nutrition3' => 3,
            'nutrition4' => 4,
            'nutrition5' => 5,
            'nutrition6' => 6,
        ];
        return $nutritions_mas;
    }
    
}
?>