<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\facultad;

/**
 * FacultadSearch represents the model behind the search form about `backend\models\facultad`.
 */
class FacultadSearch extends facultad
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_facultad', 'sede_id_sede'], 'integer'],
            [['nombre_facultad'], 'safe'],
            //[['nombreSede'], 'safe']
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
        $query = facultad::find();

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
            'id_facultad' => $this->id_facultad,
            'sede_id_sede' => $this->sede_id_sede,
        ]);

        $query->andFilterWhere(['like', 'nombre_facultad', $this->nombre_facultad]);

        return $dataProvider;
    }
}
