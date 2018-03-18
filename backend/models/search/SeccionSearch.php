<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\seccion;

/**
 * SeccionSearch represents the model behind the search form about `backend\models\seccion`.
 */
class SeccionSearch extends seccion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_seccion', 'numero_seccion', 'implementacion_id_implementacion'], 'integer'],
            [['docente_rut_docente'], 'safe'],
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
        $query = seccion::find();

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
            'id_seccion' => $this->id_seccion,
            'numero_seccion' => $this->numero_seccion,
            'implementacion_id_implementacion' => $this->implementacion_id_implementacion,
        ]);

        $query->andFilterWhere(['like', 'docente_rut_docente', $this->docente_rut_docente]);

        return $dataProvider;
    }
}
