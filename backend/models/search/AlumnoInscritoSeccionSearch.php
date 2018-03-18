<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\alumnoInscritoSeccion;

/**
 * AlumnoInscritoSeccionSearch represents the model behind the search form about `backend\models\alumnoInscritoSeccion`.
 */
class AlumnoInscritoSeccionSearch extends alumnoInscritoSeccion
{
    public $alumno_nombre;
    public $alumno_email;

    //vista general
    public $grupo_numero;
    public $socio_beneficiario_nombre;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_alumno_inscrito_seccion', 'seccion_id_seccion'], 'integer'],
            [['alumno_rut_alumno', 'alumno_nombre', 'alumno_email', 'grupo_numero', 'socio_beneficiario_nombre'], 'safe'],
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
        $query = alumnoInscritoSeccion::find();
        $query->joinWith('alumnoRutAlumno');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['alumno_nombre'] = [
            'asc' => ['nombre' => SORT_ASC],
            'desc' => ['nombre' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['alumno_email'] = [
            'asc' => ['email' => SORT_ASC],
            'desc' => ['email' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_alumno_inscrito_seccion' => $this->id_alumno_inscrito_seccion,
            'seccion_id_seccion' => $this->seccion_id_seccion,
        ]);

        $query->andFilterWhere(['like', 'alumno_rut_alumno', $this->alumno_rut_alumno]);
        $query->andFilterWhere(['like', 'nombre', $this->alumno_nombre]);
        $query->andFilterWhere(['like', 'email', $this->alumno_email]);

        return $dataProvider;
    }

    public function searchVistaGeneral($params)
    {
        $query = alumnoInscritoSeccion::find();
        $query->joinWith('alumnoRutAlumno');
        $query->joinWith('grupoTrabajoIdGrupoTrabajo.grupoTrabajoHasScbs.scbIdScb')->orderBy('grupo_trabajo.numero_grupo_trabajo');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['alumno_nombre'] = [
            'asc' => ['nombre' => SORT_ASC],
            'desc' => ['nombre' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['alumno_email'] = [
            'asc' => ['email' => SORT_ASC],
            'desc' => ['email' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_alumno_inscrito_seccion' => $this->id_alumno_inscrito_seccion,
            'seccion_id_seccion' => $this->seccion_id_seccion,
            'grupo_trabajo.numero_grupo_trabajo' => $this->grupo_numero,
        ]);

        $query->andFilterWhere(['like', 'alumno_rut_alumno', $this->alumno_rut_alumno]);
        $query->andFilterWhere(['like', 'nombre', $this->alumno_nombre]);
        $query->andFilterWhere(['like', 'email', $this->alumno_email]);
        $query->andFilterWhere(['like', 'scb.nombre_negocio', $this->socio_beneficiario_nombre]);

        return $dataProvider;
    }
}
