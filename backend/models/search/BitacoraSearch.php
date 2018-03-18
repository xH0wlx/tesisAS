<?php

namespace backend\models\search;

use backend\models\GrupoTrabajo;
use backend\models\Implementacion;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\bitacora;

/**
 * BitacoraSearch represents the model behind the search form about `backend\models\bitacora`.
 */
class BitacoraSearch extends bitacora
{
    public $anio_desde;
    public $semestre_desde;
    public $anio_hasta;
    public $semestre_hasta;

    public $reporte_asignatura;
    public $reporte_sede;

    public $semestre_numero;
    public $implementacion_id;
    public $seccion_id;
    public $grupo_id;

    public $cantidadGruposAsignatura;
    public $cantidadBitacoras;

    //FILTRO DE BITÁCORAS TODAS
    public $grupo_numero;
    public $asignatura_nombre;
    public $asignatura_sede;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cantidadBitacoras', 'reporte_asignatura', 'reporte_sede'], 'safe'],
            [['anio_desde', 'semestre_desde', 'anio_hasta', 'semestre_hasta'], 'integer'],
            [['semestre_numero','implementacion_id', 'seccion_id', 'grupo_id'], 'integer'],
            [['id_bitacora', 'grupo_trabajo_id_grupo_trabajo'], 'integer'],
            [['fecha_bitacora', 'hora_inicio', 'hora_termino', 'actividad_realizada', 'resultados',
                'observaciones', 'fecha_lectura', 'creado_en', 'modificado_en', 'grupo_numero', 'asignatura_nombre',
            'asignatura_sede'], 'safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'anio_desde' => 'Año Desde',
            'anio_hasta' => 'Año Hasta',
            'semestre_desde' => 'Semestre Desde',
            'semestre_hasta' => 'Semestre Hasta',
            'reporte_asignatura' => 'Asignatura',
            'reporte_sede' => 'Sede',

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
        $query = bitacora::find();

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
            'id_bitacora' => $this->id_bitacora,
            'grupo_trabajo_id_grupo_trabajo' => $this->grupo_trabajo_id_grupo_trabajo,
            'fecha_bitacora' => $this->fecha_bitacora,
            'hora_inicio' => $this->hora_inicio,
            'hora_termino' => $this->hora_termino,
            'fecha_lectura' => $this->fecha_lectura,
            'creado_en' => $this->creado_en,
            'modificado_en' => $this->modificado_en,
        ]);

        $query->andFilterWhere(['like', 'actividad_realizada', $this->actividad_realizada])
            ->andFilterWhere(['like', 'resultados', $this->resultados])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones]);

        return $dataProvider;
    }

    public function searchFormExterno($params)
    {
        $query = bitacora::find();
        $query->joinWith('grupoTrabajoIdGrupoTrabajo.seccionIdSeccion.implementacionIdImplementacion.asignaturaCodAsignatura.carreraCodCarrera.facultadIdFacultad.sedeIdSede');

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
            'id_bitacora' => $this->id_bitacora,
            'grupo_trabajo_id_grupo_trabajo' => $this->grupo_trabajo_id_grupo_trabajo,
            'fecha_bitacora' => $this->fecha_bitacora,
            'hora_inicio' => $this->hora_inicio,
            'hora_termino' => $this->hora_termino,
            'fecha_lectura' => $this->fecha_lectura,
            'creado_en' => $this->creado_en,
            'modificado_en' => $this->modificado_en,
            'grupo_trabajo.numero_grupo_trabajo' => $this->grupo_numero,
        ]);

        $query->andFilterWhere(['like', 'actividad_realizada', $this->actividad_realizada])
            ->andFilterWhere(['like', 'resultados', $this->resultados])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones])
            ->andFilterWhere(['like', 'asignatura.nombre_asignatura', $this->asignatura_nombre])
            ->andFilterWhere(['like', 'sede.nombre_sede', $this->asignatura_sede]);

        $query->andFilterWhere([
            '>=', 'anio_implementacion', $this->anio_desde
        ]);

        $query->andFilterWhere([
            '<=', 'anio_implementacion', $this->anio_hasta
        ]);

        return $dataProvider;
    }

    public function searchReporteResumen($params)
    {
        $query = bitacora::find();
        $query->joinWith(['grupoTrabajoIdGrupoTrabajo.seccionIdSeccion.implementacionIdImplementacion.asignaturaCodAsignatura.carreraCodCarrera.facultadIdFacultad.sedeIdSede']);
        //,        'grupoTrabajoIdGrupoTrabajo.alumnoInscritoHasGrupoTrabajos.alumnoInscritoSeccionIdAlumnoInscritoSeccion.alumnoRutAlumno'


        $subQuery = bitacora::find()
            ->select('grupo_trabajo_id_grupo_trabajo, COUNT(*) as bitacora_cantidad')
            ->groupBy('grupo_trabajo_id_grupo_trabajo');

        $query->leftJoin(['bitacoraSum' => $subQuery], 'bitacoraSum.grupo_trabajo_id_grupo_trabajo = grupo_trabajo.id_grupo_trabajo');
        $query->orderBy(['anio_implementacion' => SORT_ASC,'semestre_implementacion'=> SORT_ASC, 'asignatura_cod_asignatura'=> SORT_ASC, 'numero_grupo_trabajo'=> SORT_ASC]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['anio_desde'] = [
            'asc' => ['anio_implementacion' => SORT_ASC],
            'desc' => ['anio_implementacion' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['semestre_numero'] = [
            'asc' => ['semestre_implementacion' => SORT_ASC],
            'desc' => ['semestre_implementacion' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['cantidadBitacoras'] = [
            'asc' => ['bitacoraSum.bitacora_cantidad' => SORT_ASC],
            'desc' => ['bitacoraSum.bitacora_cantidad' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['fecha_bitacora'] = [
            'asc' => ['semestre_implementacion' => SORT_ASC, 'numero_grupo_trabajo' => SORT_ASC,'fecha_bitacora' => SORT_ASC],
            'desc' => ['semestre_implementacion' => SORT_DESC,'numero_grupo_trabajo' => SORT_ASC,'fecha_bitacora' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(count($params) == 0){
            //$query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_bitacora' => $this->id_bitacora,
            'grupo_trabajo_id_grupo_trabajo' => $this->grupo_trabajo_id_grupo_trabajo,
            'fecha_bitacora' => $this->fecha_bitacora,
            'hora_inicio' => $this->hora_inicio,
            'hora_termino' => $this->hora_termino,
            'fecha_lectura' => $this->fecha_lectura,
            'creado_en' => $this->creado_en,
            'modificado_en' => $this->modificado_en,
        ]);

        $query->andFilterWhere([
            '>=', 'anio_implementacion', $this->anio_desde
        ]);

        $query->andFilterWhere([
            '<=', 'anio_implementacion', $this->anio_hasta
        ]);

        $query->andFilterWhere([
            'like',
            'nombre_asignatura', $this->reporte_asignatura,
        ]);

        $query->andFilterWhere([
            'like',
            'nombre_sede', $this->reporte_sede,
        ]);

        $query->andFilterWhere([
            'bitacoraSum.bitacora_cantidad' => $this->cantidadBitacoras,
        ]);

        $query->andFilterWhere(['like', 'actividad_realizada', $this->actividad_realizada])
            ->andFilterWhere(['like', 'resultados', $this->resultados])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones]);

        return $dataProvider;
    }

    public function searchReporteEstadistica($params)
    {
        $query = bitacora::find();
        $query->joinWith(['grupoTrabajoIdGrupoTrabajo.seccionIdSeccion.implementacionIdImplementacion.asignaturaCodAsignatura']);
        //,        'grupoTrabajoIdGrupoTrabajo.alumnoInscritoHasGrupoTrabajos.alumnoInscritoSeccionIdAlumnoInscritoSeccion.alumnoRutAlumno'


        $subQuery = bitacora::find()->joinWith(['grupoTrabajoIdGrupoTrabajo.seccionIdSeccion.implementacionIdImplementacion.asignaturaCodAsignatura'])
            ->select('asignatura_cod_asignatura, COUNT(*) as cantidad_grupos')
            ->groupBy('asignatura_cod_asignatura');

        $query->leftJoin(['bitacoraSum' => $subQuery], 'bitacoraSum.asignatura_cod_asignatura = implementacion.asignatura_cod_asignatura');
        $query->orderBy(['anio_implementacion' => SORT_ASC,'semestre_implementacion'=> SORT_ASC]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['anio_desde'] = [
            'asc' => ['anio_implementacion' => SORT_ASC],
            'desc' => ['anio_implementacion' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['semestre_numero'] = [
            'asc' => ['semestre_implementacion' => SORT_ASC],
            'desc' => ['semestre_implementacion' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['cantidadBitacoras'] = [
            'asc' => ['bitacoraSum.bitacora_cantidad' => SORT_ASC],
            'desc' => ['bitacoraSum.bitacora_cantidad' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['fecha_bitacora'] = [
            'asc' => ['semestre_implementacion' => SORT_ASC, 'numero_grupo_trabajo' => SORT_ASC,'fecha_bitacora' => SORT_ASC],
            'desc' => ['semestre_implementacion' => SORT_DESC,'numero_grupo_trabajo' => SORT_ASC,'fecha_bitacora' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_bitacora' => $this->id_bitacora,
            'grupo_trabajo_id_grupo_trabajo' => $this->grupo_trabajo_id_grupo_trabajo,
            'fecha_bitacora' => $this->fecha_bitacora,
            'hora_inicio' => $this->hora_inicio,
            'hora_termino' => $this->hora_termino,
            'fecha_lectura' => $this->fecha_lectura,
            'creado_en' => $this->creado_en,
            'modificado_en' => $this->modificado_en,
        ]);

        $query->andFilterWhere([
            '>=', 'anio_implementacion', $this->anio_desde
        ]);

        $query->andFilterWhere([
            '<=', 'anio_implementacion', $this->anio_hasta
        ]);

        $query->andFilterWhere([
            'semestre_implementacion' => $this->semestre_numero,
        ]);

        $query->andFilterWhere([
            'bitacoraSum.bitacora_cantidad' => $this->cantidadBitacoras,
        ]);

        $query->andFilterWhere(['like', 'actividad_realizada', $this->actividad_realizada])
            ->andFilterWhere(['like', 'resultados', $this->resultados])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones]);

        return $dataProvider;
    }
}
