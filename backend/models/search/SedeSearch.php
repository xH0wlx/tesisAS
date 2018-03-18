<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\sede;

/**
 * SedeSearch represents the model behind the search form about `backend\models\sede`.
 */
class SedeSearch extends sede
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_sede'], 'integer'],
            [['nombre_sede'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = sede::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_sede' => $this->id_sede,
        ]);

        $query->andFilterWhere(['like', 'nombre_sede', $this->nombre_sede]);

        return $dataProvider;
    }
}
