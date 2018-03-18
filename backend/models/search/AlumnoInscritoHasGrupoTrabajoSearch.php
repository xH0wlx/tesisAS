<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\alumnoInscritoHasGrupoTrabajo;

/**
 * AlumnoInscritoHasGrupoTrabajoSearch represents the model behind the search form about `backend\models\alumnoInscritoHasGrupoTrabajo`.
 */
class AlumnoInscritoHasGrupoTrabajoSearch extends alumnoInscritoHasGrupoTrabajo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alumno_inscrito_seccion_id_alumno_inscrito_seccion', 'grupo_trabajo_id_grupo_trabajo'], 'integer'],
            [['fecha_creacion', 'observacion'], 'safe'],
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
        $query = alumnoInscritoHasGrupoTrabajo::find();

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
            'alumno_inscrito_seccion_id_alumno_inscrito_seccion' => $this->alumno_inscrito_seccion_id_alumno_inscrito_seccion,
            'grupo_trabajo_id_grupo_trabajo' => $this->grupo_trabajo_id_grupo_trabajo,
            'fecha_creacion' => $this->fecha_creacion,
        ]);

        $query->andFilterWhere(['like', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
