<?php

namespace common\models;

use common\models\Organization;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class OrganizationSearch extends Organization
{

    public function rules()
    {
        return [
            //[['id', 'federal_district_id'], 'integer'],
            [['title', 'short_title', 'type_org'], 'safe'],
        ];
    }


    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    public function search($params)
    {
        $query = Organization::find()->orderby(['created_at' => SORT_DESC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                //'forcePageParam' => false,
                //'pageSizeParam' => false,
                'pageSize' => 50
            ]
        ]);


        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'short_title', $this->short_title])
            ->andFilterWhere(['=', 'type_org', $this->type_org]);

        /* $query->andFilterWhere(['like', 'name', $this->name])
             ->andFilterWhere(['=', 'products_category_id', $this->products_category_id])
             ->andFilterWhere(['=', 'products_subcategory_id', $this->products_subcategory_id]);*/

        /*$query->andFilterWhere(['like', 'ugroup', $this->ugroup]);

        if(Yii::$app->user->can('admin')){
            $query->andFilterWhere(['=', 'city_id', $this->city_id]);

            $query->andFilterWhere(['like', 'ugroup', $this->ugroup]);
        }*/

        return $dataProvider;
    }
}
