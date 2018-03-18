<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\match1;

/**
 * Match1Search represents the model behind the search form about `backend\models\match1`.
 */
class Match1Search extends match1
{

    public $anio_desde;
    public $semestre_desde;
    public $anio_hasta;
    public $semestre_hasta;
    public $semestre_numero;

    //UTILIZADOS EN MATCH1 RESULTADO PERIODO (DETALLE DEL MATCH)
    public $socio_institucional_nombre_comuna;
    public $requerimiento_titulo;
    public $requerimiento_descripcion;
    public $asignatura_nombre;
    public $asignatura_semestre_dicta;
    public $asignatura_sede;

    //BUSQUEDAS EN ASIGNACION DE SERVICIOS
    public $servicio_titulo;
    public $requerimiento_cantidad_beneficiarios;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_match1', 'requerimiento_id_requerimiento', 'asignatura_cod_asignatura', 'anio_match1', 'semestre_match1', 'servicio_id_servicio', 'aprobacion_implementacion'], 'integer'],
            [['creado_en', 'modificado_en'], 'safe'],
            [['socio_institucional_nombre_comuna', 'requerimiento_titulo', 'requerimiento_descripcion',
                'asignatura_nombre', 'asignatura_semestre_dicta', 'asignatura_sede', 'servicio_titulo', 'requerimiento_cantidad_beneficiarios'], 'safe'],
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
            //UTILIZADOS EN MATCH1 RESULTADO PERIODO (DETALLE DEL MATCH)
            'socio_institucional_nombre_comuna' => 'Socio Comunitario Institucional',
            'requerimiento_titulo' => 'Título del Requerimiento',
            'requerimiento_descripcion' => 'Descripción del Requerimiento',
            'asignatura_nombre' => 'Nombre Asignatura',
            'asignatura_semestre_dicta' => 'Asignatura Semestre Dicta',
            'asignatura_sede' => 'Sede Asignatura',
            'servicio_titulo' => 'Título del Servicio',
            'requerimiento_cantidad_beneficiarios' => 'Cantidad Aprox. Socios Beneficiarios',

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
        $query = match1::find()->orderBy('asignatura_cod_asignatura');

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
            'id_match1' => $this->id_match1,
            'requerimiento_id_requerimiento' => $this->requerimiento_id_requerimiento,
            'asignatura_cod_asignatura' => $this->asignatura_cod_asignatura,
            'anio_match1' => $this->anio_match1,
            'semestre_match1' => $this->semestre_match1,
            'servicio_id_servicio' => $this->servicio_id_servicio,
            'aprobacion_implementacion' => $this->aprobacion_implementacion,
            'creado_en' => $this->creado_en,
            'modificado_en' => $this->modificado_en,
        ]);

        return $dataProvider;
    }

    public function searchResultadoAsignacionServicios($params)
    {
        $query = match1::find()->joinWith('requerimientoIdRequerimiento.sciIdSci.comunaComuna');

        $query->joinWith('asignaturaCodAsignatura.carreraCodCarrera.facultadIdFacultad.sedeIdSede')
            ->orderBy(['asignatura.nombre_asignatura'=>SORT_ASC]);

        $query->joinWith('servicioIdServicio');

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
            'asignatura_cod_asignatura' => $this->asignatura_cod_asignatura,
        ]);

        $query->andFilterWhere(['like', 'requerimiento.titulo', $this->requerimiento_titulo])
            ->andFilterWhere(['like', 'servicio.titulo', $this->servicio_titulo])
            ->andFilterWhere(['like', 'asignatura.nombre_asignatura', $this->asignatura_nombre])
            ->andFilterWhere(['like', 'sede.nombre_sede', $this->asignatura_sede]);

        return $dataProvider;
    }

    public function searchResultadoMatch1($params)
    {
        $query = match1::find()->joinWith('requerimientoIdRequerimiento.sciIdSci.comunaComuna')
            ->orderBy(['sci.nombre'=>SORT_ASC,'requerimiento.titulo'=>SORT_ASC]);

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
            'asignatura_cod_asignatura' => $this->asignatura_cod_asignatura,
            'anio_match1' => $this->anio_match1,
            'semestre_match1' => $this->semestre_match1,
            //UTILIZADOS EN RESULTADO MATCH1
            'asignatura.semestre_dicta' => $this->asignatura_semestre_dicta,
            'requerimiento.cantidad_aprox_beneficiarios' => $this->requerimiento_cantidad_beneficiarios,
        ]);
        //UTILIZADOS EN RESULTADO MATCH1
        $query->andFilterWhere(['or',
            ['like','sci.nombre', $this->socio_institucional_nombre_comuna],
            ['like','comuna.comuna_nombre', $this->socio_institucional_nombre_comuna]]);

        $query->andFilterWhere(['like', 'requerimiento.titulo', $this->requerimiento_titulo])
            ->andFilterWhere(['like', 'requerimiento.descripcion', $this->requerimiento_descripcion])
            ->andFilterWhere(['like', 'asignatura.nombre_asignatura', $this->asignatura_nombre])
            ->andFilterWhere(['like', 'sede.nombre_sede', $this->asignatura_sede]);

        return $dataProvider;
    }

    public function searchReporteEstadistica($params)
    {
        $query = match1::find()
            ->select([
                '{{match1}}.*', // select all customer fields
                //'COUNT(DISTINCT {{implementacion}}.id_implementacion) AS cantidad_implementaciones',
                //'COUNT(DISTINCT {{seccion}}.docente_rut_docente) AS cantidad_docentes_reporte',
                'COUNT(DISTINCT {{sci}}.id_sci) AS cantidad_sci_reporte',
                'COUNT(DISTINCT {{match1}}.requerimiento_id_requerimiento) AS cantidad_requerimientos_reporte',
                //'COUNT(DISTINCT {{match1}}.requerimiento_id_requerimiento) AS cantidad_requerimientos_na_reporte',
                //'COUNT(DISTINCT {{grupo_trabajo_has_scb}}.scb_id_scb) AS cantidad_scb_reporte',
                //'COUNT(DISTINCT {{alumno_inscrito_seccion}}.id_alumno_inscrito_seccion) AS cantidad_alumnos_reporte',
                '{{sede}}.id_sede AS id_sede_reporte_estadistica', // select all customer fields
                '{{sede}}.nombre_sede AS sede_reporte_estadistica', // select all customer fields
            ])
            ->joinWith('implementacionIdImplementacion.asignaturaCodAsignatura.carreraCodCarrera.facultadIdFacultad.sedeIdSede') // ensure table junction
            //->joinWith('seccions.grupoTrabajos.grupoTrabajoHasScbsNoCambiados') // ensure table junction
            //->joinWith('seccions.alumnoInscritoSeccions') // ensure table junction
            ->joinWith('requerimientoIdRequerimiento.sciIdSci') // ensure table junction
            ->where(['or',['{{implementacion}}.estado' => 2],['{{implementacion}}.estado' => 1]]) //HACE QUE SOLO SE PUEDAN SACAR EN DESARROLLO Y FINALIZADO
            //->andWhere(['not', ['{{match1}}.implementacion_id_implementacion' => null]])
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


        $query->andFilterWhere(['like', 'anio_match1', $this->anio_match1])
            ->andFilterWhere(['like', 'semestre_match1', $this->semestre_match1]);

        $query->andFilterWhere([
            '>=', 'anio_match1', $this->anio_desde
        ]);

        $query->andFilterWhere([
            '<=', 'anio_match1', $this->anio_hasta
        ]);

        $query->andFilterWhere([
            'id_match1' => $this->id_match1,
            'requerimiento_id_requerimiento' => $this->requerimiento_id_requerimiento,
            'asignatura_cod_asignatura' => $this->asignatura_cod_asignatura,
            'anio_match1' => $this->anio_match1,
            'semestre_match1' => $this->semestre_match1,
            'servicio_id_servicio' => $this->servicio_id_servicio,
            'aprobacion_implementacion' => $this->aprobacion_implementacion,
            'creado_en' => $this->creado_en,
            'modificado_en' => $this->modificado_en,
        ]);

        return $dataProvider;
    }

    public function searchReporteResumen($params)
    {
        $query = match1::find()
            ->select([
                '{{match1}}.*', // select all customer fields
                //'COUNT(DISTINCT {{implementacion}}.id_implementacion) AS cantidad_implementaciones',
                //'COUNT(DISTINCT {{seccion}}.docente_rut_docente) AS cantidad_docentes_reporte',
                'COUNT(DISTINCT {{sci}}.id_sci) AS cantidad_sci_reporte',
                'COUNT(DISTINCT {{match1}}.requerimiento_id_requerimiento) AS cantidad_requerimientos_reporte',
                //'COUNT(DISTINCT {{match1}}.requerimiento_id_requerimiento) AS cantidad_requerimientos_na_reporte',
                //'COUNT(DISTINCT {{grupo_trabajo_has_scb}}.scb_id_scb) AS cantidad_scb_reporte',
                //'COUNT(DISTINCT {{alumno_inscrito_seccion}}.id_alumno_inscrito_seccion) AS cantidad_alumnos_reporte',
                '{{sede}}.id_sede AS id_sede_reporte_estadistica', // select all customer fields
                '{{sede}}.nombre_sede AS sede_reporte_estadistica', // select all customer fields
            ])
            ->joinWith('implementacionIdImplementacion.asignaturaCodAsignatura.carreraCodCarrera.facultadIdFacultad.sedeIdSede') // ensure table junction
            //->joinWith('seccions.grupoTrabajos.grupoTrabajoHasScbsNoCambiados') // ensure table junction
            //->joinWith('seccions.alumnoInscritoSeccions') // ensure table junction
            ->joinWith('requerimientoIdRequerimiento.sciIdSci') // ensure table junction
            ->where(['or',['{{implementacion}}.estado' => 2],['{{implementacion}}.estado' => 1]]) //HACE QUE SOLO SE PUEDAN SACAR EN DESARROLLO Y FINALIZADO
            //->andWhere(['not', ['{{match1}}.implementacion_id_implementacion' => null]])
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


        $query->andFilterWhere(['like', 'anio_match1', $this->anio_match1])
            ->andFilterWhere(['like', 'semestre_match1', $this->semestre_match1]);

        $query->andFilterWhere([
            '>=', 'anio_match1', $this->anio_desde
        ]);

        $query->andFilterWhere([
            '<=', 'anio_match1', $this->anio_hasta
        ]);

        $query->andFilterWhere([
            'id_match1' => $this->id_match1,
            'requerimiento_id_requerimiento' => $this->requerimiento_id_requerimiento,
            'asignatura_cod_asignatura' => $this->asignatura_cod_asignatura,
            'anio_match1' => $this->anio_match1,
            'semestre_match1' => $this->semestre_match1,
            'servicio_id_servicio' => $this->servicio_id_servicio,
            'aprobacion_implementacion' => $this->aprobacion_implementacion,
            'creado_en' => $this->creado_en,
            'modificado_en' => $this->modificado_en,
        ]);

        return $dataProvider;
    }
}
