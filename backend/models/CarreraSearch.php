<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Carrera;

/**
 * CarreraSearch represents the model behind the search form about `app\models\Carrera`.
 */
class CarreraSearch extends Carrera
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_carrera', 'cod_carrera', 'plan', 'id_sede'], 'integer'],
            [['nombre'], 'safe'],
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
        $query = Carrera::find();

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
            'id_carrera' => $this->id_carrera,
            'cod_carrera' => $this->cod_carrera,
            'plan' => $this->plan,
            'id_sede' => $this->id_sede,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre]);

        return $dataProvider;
    }
}
