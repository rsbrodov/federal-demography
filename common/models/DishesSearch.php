<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Dishes;
use yii\db\Query;


class DishesSearch extends Dishes
{

    public function rules()
    {
        return [
            [['name', 'dishes_category_id', 'recipes_collection_id', 'techmup_number'], 'safe'],
        ];
    }


    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    public function search($params)
    {
        $recipes_ids = [];
        if(Yii::$app->user->can('admin'))
        {
            $recipes_collection = RecipesCollection::find()->where(['organization_id' => [7, Yii::$app->session['organization_id']]])->all();
        }
        elseif (Yii::$app->request->pathInfo == 'dishes/dishes-base'){
            $recipes_collection = RecipesCollection::find()->where(['organization_id' => [7, Yii::$app->user->identity->organization_id]])->all();
        }
        elseif (Yii::$app->request->pathInfo == 'dishes' || Yii::$app->request->pathInfo == 'dishes/index'){
            $recipes_collection = RecipesCollection::find()->where(['organization_id' => [Yii::$app->user->identity->organization_id]])->all();
        }

        foreach($recipes_collection as $r_collection){
            $recipes_ids[] = $r_collection->id;
        }
        //count не считает вместе с селектом пришлось все делать через join
        /*$query = Dishes::find()->select(['id', 'name', 'dishes_category_id', 'recipes_collection_id', 'description', 'culinary_processing_id', 'yield', 'number_of_dish', 'techmup_number',
        ])->where(['recipes_collection_id' => $recipes_ids])
            ->with([
            'recipes' => function($q){
                return $q->select(['id', 'name']);
            },
            'category' => function($q){
                return $q->select(['id', 'name']);
            }
        ]);*/
        $expression = "(SELECT COUNT(*) FROM dishes_products AS dp WHERE d.id = dp.dishes_id) AS products_count";
        $query = (new Query())
            ->select([
                'd.id', 'd.name', 'd.dishes_category_id', 'd.recipes_collection_id', 'd.description',
                'd.culinary_processing_id', 'd.yield', 'd.number_of_dish', 'd.techmup_number',
                'rc.name as recipes_collection_name', 'dc.name as dishes_category_name',])
            ->addSelect($expression)
            ->from(['d' => 'dishes']) //алиас "d"
            ->leftJoin('recipes_collection AS rc', 'd.recipes_collection_id = rc.id')
            ->leftJoin('dishes_category AS dc', 'd.dishes_category_id = dc.id')
            //->leftJoin('menus_dishes AS md', 'd.id = md.dishes_id')
            ->where(['recipes_collection_id' => $recipes_ids])
            ->groupBy('d.id');

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
            ]
        ]);


        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['=', 'dishes_category_id', $this->dishes_category_id])
            ->andFilterWhere(['like', 'recipes_collection_id', $this->recipes_collection_id])
            ->andFilterWhere(['like', 'techmup_number', $this->techmup_number]);
        return $dataProvider;
    }
}
