<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\asignatura;

/**
 * AsignaturaSearch represents the model behind the search form about `backend\models\asignatura`.
 */
class AsignaturaSearch extends asignatura
{
    public $asignatura_carrera;
    public $asignatura_sede;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod_asignatura'], 'integer'],
            [['nombre_asignatura', 'semestre_dicta', 'semestre_malla', 'resultado_aprendizaje', 'carrera_cod_carrera', 'asignatura_carrera', 'asignatura_sede'], 'safe'],
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
        $query = asignatura::find();

        $query->joinWith('carreraCodCarrera.facultadIdFacultad.sedeIdSede');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        // Important: here is how we set up the sorting
        // The key is the attribute name on our "TourSearch" instance
        $dataProvider->sort->attributes['asignatura_carrera'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['alias_carrera' => SORT_ASC],
            'desc' => ['alias_carrera' => SORT_DESC],
        ];
        // Lets do the same with country now
        $dataProvider->sort->attributes['asignatura_sede'] = [
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
            'cod_asignatura' => $this->cod_asignatura,
            //'carrera_cod_carrera' => $this->carrera_cod_carrera,
        ]);

        $query->andFilterWhere(['like', 'nombre_asignatura', $this->nombre_asignatura])
            ->andFilterWhere(['like', 'semestre_dicta', $this->semestre_dicta])
            ->andFilterWhere(['like', 'semestre_malla', $this->semestre_malla])
            ->andFilterWhere(['like', 'resultado_aprendizaje', $this->resultado_aprendizaje])
            ->andFilterWhere(['like', 'alias_carrera', $this->asignatura_carrera])
            ->andFilterWhere(['like', 'nombre_sede', $this->asignatura_sede]);


        return $dataProvider;
    }
}
