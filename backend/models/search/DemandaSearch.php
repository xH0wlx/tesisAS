<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\demanda;

/**
 * DemandaSearch represents the model behind the search form about `backend\models\demanda`.
 */
class DemandaSearch extends demanda
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_demanda', 'sci_id_sci'], 'integer'],
            [['perfil_estudiante', 'apoyos_brindados', 'observaciones', 'fecha_creacion'], 'safe'],
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
        $query = demanda::find();

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
            'id_demanda' => $this->id_demanda,
            'fecha_creacion' => $this->fecha_creacion,
            'sci_id_sci' => $this->sci_id_sci,
        ]);

        $query->andFilterWhere(['like', 'perfil_estudiante', $this->perfil_estudiante])
            ->andFilterWhere(['like', 'apoyos_brindados', $this->apoyos_brindados])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones]);

        return $dataProvider;
    }
}
