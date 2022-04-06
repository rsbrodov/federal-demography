<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AnketTeacher;

/**
 * AnketTeacherSearch represents the model behind the search form of `common\models\AnketTeacher`.
 */
class AnketTeacherSearch extends AnketTeacher
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'federal_district_id', 'region_id', 'class', 'percentage_children', 'delicious_food', 'food_always_warm', 'time_eat', 'menu_varied', 'choice_dishes', 'always_clean_dishes', 'enough_space', 'feed_whole_class', 'not_delicious_food', 'not_food_always_warm', 'not_time_eat', 'not_menu_varied', 'not_choice_dishes', 'not_always_clean_dishes', 'not_enough_space', 'rate_overall_satisfaction'], 'integer'],
            [['place_residence', 'school', 'offers', 'created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = AnketTeacher::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'federal_district_id' => $this->federal_district_id,
            'region_id' => $this->region_id,
            'class' => $this->class,
            'percentage_children' => $this->percentage_children,
            'delicious_food' => $this->delicious_food,
            'food_always_warm' => $this->food_always_warm,
            'time_eat' => $this->time_eat,
            'menu_varied' => $this->menu_varied,
            'choice_dishes' => $this->choice_dishes,
            'always_clean_dishes' => $this->always_clean_dishes,
            'enough_space' => $this->enough_space,
            'feed_whole_class' => $this->feed_whole_class,
            'not_delicious_food' => $this->not_delicious_food,
            'not_food_always_warm' => $this->not_food_always_warm,
            'not_time_eat' => $this->not_time_eat,
            'not_menu_varied' => $this->not_menu_varied,
            'not_choice_dishes' => $this->not_choice_dishes,
            'not_always_clean_dishes' => $this->not_always_clean_dishes,
            'not_enough_space' => $this->not_enough_space,
            'rate_overall_satisfaction' => $this->rate_overall_satisfaction,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'place_residence', $this->place_residence])
            ->andFilterWhere(['like', 'school', $this->school])
            ->andFilterWhere(['like', 'offers', $this->offers]);

        return $dataProvider;
    }
}
