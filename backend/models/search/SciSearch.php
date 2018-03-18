<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\sci;

/**
 * SciSearch represents the model behind the search form about `backend\models\sci`.
 */
class SciSearch extends sci
{
    public $sci_sede;
    public $sci_comuna;
    public $general;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_sci', 'comuna_comuna_id'], 'integer'],
            [['nombre', 'direccion', 'observacion', 'departamento_programa', 'creado_en', 'modificado_en', 'sci_sede', 'sci_comuna', 'general'], 'safe'],
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
        $query = sci::find();

        $query->joinWith('sedeIdSede');
        $query->joinWith('comunaComuna');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['sci_comuna'] = [
            'asc' => ['comuna_nombre' => SORT_ASC],
            'desc' => ['comuna_nombre' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['sci_sede'] = [
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id_sci' => $this->id_sci,
            'comuna_comuna_id' => $this->comuna_comuna_id,
            'creado_en' => $this->creado_en,
            'modificado_en' => $this->modificado_en,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'observacion', $this->observacion])
            ->andFilterWhere(['like', 'departamento_programa', $this->departamento_programa])
            ->andFilterWhere(['like', 'comuna_nombre', $this->sci_comuna])
            ->andFilterWhere(['like', 'nombre_sede', $this->sci_sede]);

        return $dataProvider;
    }

    public function searchNoAsignado($params)
    {
        $query = sci::find();

        $query->joinWith('sedeIdSede');
        $query->joinWith('comunaComuna');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['sci_comuna'] = [
            'asc' => ['comuna_nombre' => SORT_ASC],
            'desc' => ['comuna_nombre' => SORT_DESC],
        ];


        $dataProvider->sort->attributes['sci_sede'] = [
            'asc' => ['nombre_sede' => SORT_ASC],
            'desc' => ['nombre_sede' => SORT_DESC],
        ];

        $query->where('(SELECT COUNT(*) FROM requerimiento WHERE sci_id_sci = sci.id_sci
            AND estado_ejecucion_id_estado = 1) <> 0');


        //$this->load($params);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_sci' => $this->id_sci,
            'comuna_comuna_id' => $this->comuna_comuna_id,
            'creado_en' => $this->creado_en,
            'modificado_en' => $this->modificado_en,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'observacion', $this->observacion])
            ->andFilterWhere(['like', 'departamento_programa', $this->departamento_programa])
            ->andFilterWhere(['like', 'nombre_sede', $this->sci_sede])
            ->andFilterWhere(['like', 'comuna_nombre', $this->sci_comuna]);


        $dataProvider->setPagination(['pageSize' => 3]);
        return $dataProvider;
    }

    public function searchNoAsignadoNoAsignatura($params)
    {
        $query = sci::find();

        $query->joinWith('sedeIdSede');
        //$query->joinWith('requerimientos');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['sci_sede'] = [
            'asc' => ['nombre_sede' => SORT_ASC],
            'desc' => ['nombre_sede' => SORT_DESC],
        ];

        $query->where('(SELECT COUNT(*) FROM requerimiento WHERE sci_id_sci = sci.id_sci
            AND estado_ejecucion_id_estado = 1) <> 0');

        //$query->andWhere(['preseleccionado_match1' => 1]);


        //$this->load($params);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_sci' => $this->id_sci,
            'comuna_comuna_id' => $this->comuna_comuna_id,
            'creado_en' => $this->creado_en,
            'modificado_en' => $this->modificado_en,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'observacion', $this->observacion])
            ->andFilterWhere(['like', 'departamento_programa', $this->departamento_programa])
            ->andFilterWhere(['like', 'nombre_sede', $this->sci_sede]);

        $dataProvider->setPagination(['pageSize' => 3]);
        return $dataProvider;
    }
}
