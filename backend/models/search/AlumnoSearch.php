<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\alumno;

/**
 * AlumnoSearch represents the model behind the search form about `backend\models\alumno`.
 */
class AlumnoSearch extends alumno
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rut_alumno', 'nombre', 'telefono', 'email'], 'safe'],
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
        $query = alumno::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'rut_alumno', $this->rut_alumno])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
