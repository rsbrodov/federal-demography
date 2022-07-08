<?php

namespace backend\modules\visit\controllers;

use common\models\User;
use yii\web\Controller;

/**
 * VisitController for the `visit` module
 */
class VisitController extends Controller
{
    public function actionReport()
    {
        $type_org_info = User::find()->alias('u')->
        select(['tor.name', 'COUNT(*) as count_auth'])->
        innerJoin('user_autorization_statistic as ua', 'u.id = ua.user_id')->
        innerJoin('organization as o', 'u.organization_id = o.id')->
        innerJoin('type_organization  as tor', 'o.type_org = tor.id ')->
        innerJoin('region as r', 'o.region_id = r.id ')->
        where(['>=' ,'ua.created_at', '2021-09-01 00:00:00' ])->
        groupBy(['tor.id'])->
        orderBy(['count_auth' => SORT_DESC])->
        asArray()->
        all();

        $region_type_org_info = User::find()->alias('u')->
        select(['r.name as region_name', 'tor.name', 'COUNT(*) as count_auth'])->
        innerJoin('user_autorization_statistic as ua', 'u.id = ua.user_id')->
        innerJoin('organization as o', 'u.organization_id = o.id')->
        innerJoin('type_organization  as tor', 'o.type_org = tor.id ')->
        innerJoin('region as r', 'o.region_id = r.id ')->
        where(['>=' ,'ua.created_at', '2021-09-01 00:00:00'])->
        groupBy(['r.id', 'tor.id'])->
        having(['>=' ,'count_auth', 9])->
        orderBy(['count_auth' => SORT_DESC])->
        asArray()->
        all();

        $region_info = User::find()->alias('u')->
        select(['r.name as region_name', 'COUNT(*) as count_auth'])->
        innerJoin('user_autorization_statistic as ua', 'u.id = ua.user_id')->
        innerJoin('organization as o', 'u.organization_id = o.id')->
        innerJoin('type_organization  as tor', 'o.type_org = tor.id ')->
        innerJoin('region as r', 'o.region_id = r.id ')->
        where(['>=' ,'ua.created_at', '2021-09-01 00:00:00'])->
        groupBy(['r.id'])->
        having(['>=' ,'count_auth', 9])->
        orderBy(['count_auth' => SORT_DESC])->
        asArray()->
        all();

        return $this->render('report', [
            'type_org_info' => $type_org_info,
            'region_type_org_info' => $region_type_org_info,
            'region_info' => $region_info,
        ]);
    }
}
