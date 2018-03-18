<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\carrera;

/**
 * CarreraSearch represents the model behind the search form about `backend\models\carrera`.
 */
class CarreraSearch extends carrera
{
    public $carrera_sede;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod_carrera', 'facultad_id_facultad'], 'integer'],
            [['cod_carrera', 'alias_carrera', 'nombre_carrera', 'plan_carrera', 'carrera_sede'], 'safe'],
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
        $query = carrera::find();

        $query->joinWith('facultadIdFacultad.sedeIdSede');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['carrera_sede'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['nombre_sede' => SORT_ASC],
            'desc' => ['nombre_sede' => SORT_DESC],
        ];

        //$this->load($params);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'cod_carrera' => $this->cod_carrera,
            'facultad_id_facultad' => $this->facultad_id_facultad,
        ]);

        $query->andFilterWhere(['like', 'cod_carrera', $this->cod_carrera])
            ->andFilterWhere(['like', 'nombre_carrera', $this->nombre_carrera])
            ->andFilterWhere(['like', 'alias_carrera', $this->alias_carrera])
            ->andFilterWhere(['like', 'plan_carrera', $this->plan_carrera])
            ->andFilterWhere(['like', 'nombre_sede', $this->carrera_sede]);


        return $dataProvider;
    }
}
