<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\implementacion;

/**
 * ImplementacionSearch represents the model behind the search form about `backend\models\implementacion`.
 */
class ImplementacionSearch extends implementacion
{
    public $anio_desde;
    public $semestre_desde;
    public $anio_hasta;
    public $semestre_hasta;
    public $semestre_numero;

    //BUSQUEDA DE IMPLEMENTACIONES
    public $asignatura_nombre;
    public $asignatura_sede;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['anio_desde','semestre_desde','anio_hasta','semestre_hasta','id_implementacion', 'asignatura_cod_asignatura'], 'integer'],
            [['anio_implementacion', 'semestre_implementacion', 'asignatura_nombre', 'asignatura_sede'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'anio_desde' => 'Año Desde',
            'anio_hasta' => 'Año Hasta',
            'semestre_desde' => 'Semestre Desde',
            'semestre_hasta' => 'Semestre Hasta',
            'semestre_numero' => 'N° Semestre',
            'asignatura_nombre' => 'Nombre de la Asignatura',
            'asignatura_sede' => 'Sede de la Asignatura',

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
        $query = implementacion::find();

        $query->joinWith('asignaturaCodAsignatura.carreraCodCarrera.facultadIdFacultad.sedeIdSede');

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
            'id_implementacion' => $this->id_implementacion,
            'asignatura_cod_asignatura' => $this->asignatura_cod_asignatura,
        ]);

        $query->andFilterWhere(['like', 'anio_implementacion', $this->anio_implementacion])
            ->andFilterWhere(['like', 'semestre_implementacion', $this->semestre_implementacion])
            ->andFilterWhere(['like', 'semestre_implementacion', $this->semestre_implementacion])
            ->andFilterWhere(['like', 'semestre_implementacion', $this->semestre_implementacion]);

        return $dataProvider;
    }

    public function searchReporteEstadisticaBitacora($params)
    {
        $query = implementacion::find()->where(['estado' => 2]);

        $query->joinWith(['asignaturaCodAsignatura.carreraCodCarrera.facultadIdFacultad.sedeIdSede']);

        $subQuery = implementacion::find()->joinWith(['seccions.grupoTrabajos'])
            ->select('id_implementacion, COUNT(*) as cantidad_grupos')
            ->groupBy('asignatura_cod_asignatura');

        $query->leftJoin(['gruposCount' => $subQuery], 'gruposCount.id_implementacion = implementacion.id_implementacion');
        $query->orderBy(['anio_implementacion' => SORT_ASC,'semestre_implementacion'=> SORT_ASC]);

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
            'id_implementacion' => $this->id_implementacion,
            'asignatura_cod_asignatura' => $this->asignatura_cod_asignatura,
        ]);

        $query->andFilterWhere(['like', 'anio_implementacion', $this->anio_implementacion])
            ->andFilterWhere(['like', 'semestre_implementacion', $this->semestre_implementacion]);

        $query->andFilterWhere([
            '>=', 'anio_implementacion', $this->anio_desde
        ]);

        $query->andFilterWhere([
            '<=', 'anio_implementacion', $this->anio_hasta
        ]);

        $query->andFilterWhere([
            'semestre_implementacion' => $this->semestre_numero,
        ]);


        return $dataProvider;
    }

    public function searchReporteEstadistica($params)
    {
        $query = implementacion::find()
            ->select([
                '{{implementacion}}.*', // select all customer fields
                'COUNT(DISTINCT {{implementacion}}.id_implementacion) AS cantidad_implementaciones',
                'COUNT(DISTINCT {{seccion}}.docente_rut_docente) AS cantidad_docentes_reporte',
                'COUNT(DISTINCT {{sci}}.id_sci) AS cantidad_sci_reporte',
                'COUNT(DISTINCT {{grupo_trabajo_has_scb}}.scb_id_scb) AS cantidad_scb_reporte',
                'COUNT(DISTINCT {{alumno_inscrito_seccion}}.id_alumno_inscrito_seccion) AS cantidad_alumnos_reporte',
                '{{sede}}.id_sede AS id_sede_reporte_estadistica', // select all customer fields
                '{{sede}}.nombre_sede AS sede_reporte_estadistica', // select all customer fields
            ])
            ->joinWith('asignaturaCodAsignatura.carreraCodCarrera.facultadIdFacultad.sedeIdSede') // ensure table junction
            ->joinWith('seccions.grupoTrabajos.grupoTrabajoHasScbsNoCambiados') // ensure table junction
            ->joinWith('seccions.alumnoInscritoSeccions') // ensure table junction
            ->joinWith('match1Requerimientos.requerimientoIdRequerimiento.sciIdSci') // ensure table junction
            ->where(['{{implementacion}}.estado' => 2])
            ->groupBy(['{{sede}}.id_sede', '{{implementacion}}.anio_implementacion', '{{implementacion}}.semestre_implementacion']) // group the result to ensure aggregation function works
            ->orderBy(['{{implementacion}}.anio_implementacion' => SORT_ASC,
                '{{implementacion}}.semestre_implementacion'=> SORT_ASC]);


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
            'id_implementacion' => $this->id_implementacion,
            'asignatura_cod_asignatura' => $this->asignatura_cod_asignatura,
        ]);

        $query->andFilterWhere(['like', 'anio_implementacion', $this->anio_implementacion])
            ->andFilterWhere(['like', 'semestre_implementacion', $this->semestre_implementacion]);

        $query->andFilterWhere([
            '>=', 'anio_implementacion', $this->anio_desde
        ]);

        $query->andFilterWhere([
            '<=', 'anio_implementacion', $this->anio_hasta
        ]);

        $query->andFilterWhere([
            'semestre_implementacion' => $this->semestre_numero,
        ]);


        return $dataProvider;
    }

    public function searchReporteResumen($params)
    {
        $query = implementacion::find()->where(['{{implementacion}}.estado' => 2]);

        $query->joinWith(['asignaturaCodAsignatura.carreraCodCarrera.facultadIdFacultad.sedeIdSede']);

        $subQuery = implementacion::find()->joinWith(['seccions.grupoTrabajos'])
            ->select('id_implementacion, COUNT(*) as cantidad_grupos')
            ->groupBy('asignatura_cod_asignatura');

        $query->leftJoin(['gruposCount' => $subQuery], 'gruposCount.id_implementacion = implementacion.id_implementacion');
        $query->orderBy(['anio_implementacion' => SORT_ASC,'semestre_implementacion'=> SORT_ASC]);

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
            'id_implementacion' => $this->id_implementacion,
            'asignatura_cod_asignatura' => $this->asignatura_cod_asignatura,
        ]);

        $query->andFilterWhere(['like', 'anio_implementacion', $this->anio_implementacion])
            ->andFilterWhere(['like', 'semestre_implementacion', $this->semestre_implementacion]);

        $query->andFilterWhere([
            '>=', 'anio_implementacion', $this->anio_desde
        ]);

        $query->andFilterWhere([
            '<=', 'anio_implementacion', $this->anio_hasta
        ]);

        $query->andFilterWhere([
            'semestre_implementacion' => $this->semestre_numero,
        ]);


        return $dataProvider;
    }

}
