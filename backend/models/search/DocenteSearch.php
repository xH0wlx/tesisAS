<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\docente;

/**
 * DocenteSearch represents the model behind the search form about `backend\models\docente`.
 */
class DocenteSearch extends docente
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rut_docente', 'nombre_completo', 'email'], 'safe'],
            [['telefono'], 'integer'],
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
        $query = docente::find();

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
            'telefono' => $this->telefono,
        ]);

        $query->andFilterWhere(['like', 'rut_docente', $this->rut_docente])
            ->andFilterWhere(['like', 'nombre_completo', $this->nombre_completo])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
