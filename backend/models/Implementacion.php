<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "implementacion".
 *
 * @property integer $id_implementacion
 * @property integer $asignatura_cod_asignatura
 * @property string $anio_implementacion
 * @property string $semestre_implementacion
 *
 * @property Asignatura $asignaturaCodAsignatura
 * @property Seccion[] $seccions
 */
class Implementacion extends \yii\db\ActiveRecord
{
    public $cantidad_implementaciones;
    public $cantidad_docentes_reporte;
    public $cantidad_sci_reporte;
    public $cantidad_scb_reporte;
    public $cantidad_alumnos_reporte;
    public $id_sede_reporte_estadistica;
    public $sede_reporte_estadistica;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'implementacion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['asignatura_cod_asignatura', 'anio_implementacion', 'semestre_implementacion'], 'required'],
            [['asignatura_cod_asignatura'], 'integer'],
            [['anio_implementacion', 'semestre_implementacion'], 'string', 'max' => 45],
            [['asignatura_cod_asignatura', 'anio_implementacion', 'semestre_implementacion'], 'unique', 'targetAttribute' => ['asignatura_cod_asignatura', 'anio_implementacion', 'semestre_implementacion'], 'message' => 'La combinción de Asignatura, Año y Semestre está en uso.'],
            [['asignatura_cod_asignatura'], 'exist', 'skipOnError' => true, 'targetClass' => Asignatura::className(), 'targetAttribute' => ['asignatura_cod_asignatura' => 'cod_asignatura']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_implementacion' => 'Id Implementacion',
            'asignatura_cod_asignatura' => 'Código Asignatura',
            'anio_implementacion' => 'Año Implementación',
            'semestre_implementacion' => 'Semestre Implementación',
            'codigonombre' => 'Asignatura',
            'cantidadGruposAsignatura' => 'N° Grupos por Asignatura',
            'cantidadBitacorasAsignatura' => 'N° Bitácoras por Asignatura',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsignaturaCodAsignatura()
    {
        return $this->hasOne(Asignatura::className(), ['cod_asignatura' => 'asignatura_cod_asignatura']);
    }

    public function getFilasMatch()
    {
        return $this->hasMany(Match1::className(), ['implementacion_id_implementacion' => 'id_implementacion']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    //NO USAR
    public function getMatch1Requerimientos()
    {
        return $this->hasMany(Match1::className(), ['implementacion_id_implementacion' => 'id_implementacion']);
    }

    //NO USAR A MENOS QUE SE LE AGREGUE EL AÑO Y SEMESTRE
    public function getMatch1ServicioOne()
    {
        return $this->hasOne(Match1::className(), ['implementacion_id_implementacion' => 'id_implementacion']);
    }

    public function getSeccions()
    {
        return $this->hasMany(Seccion::className(), ['implementacion_id_implementacion' => 'id_implementacion']);
    }

    public function getCantidadGruposAsignatura()
    {
        return $this->hasMany(GrupoTrabajo::className(), ['seccion_id_seccion' => 'id_seccion'])->viaTable('seccion', ['implementacion_id_implementacion' => 'id_implementacion'])->count();
    }

    public function getCantidadBitacorasAsignatura()
    {
        $subQuery = Bitacora::find()->joinWith('grupoTrabajoIdGrupoTrabajo.seccionIdSeccion.implementacionIdImplementacion')
            ->where(['implementacion.id_implementacion'=> $this->id_implementacion,'estado' => 2])->count();
        return $subQuery;
    }

    public static function getTotal($provider, $campo)
    {
        $total = 0;

        foreach ($provider as $item) {
            $total += $item[$campo];
        }

        return $total;
    }

    public function getCodigoNombre(){

        return $this->asignatura_cod_asignatura.' '.$this->asignaturaCodAsignatura->nombre_asignatura
            .' ('.$this->asignaturaCodAsignatura->carreraCodCarrera->facultadIdFacultad->sedeIdSede->nombre_sede.')';
    }

    public function getSociosParticipantesImplementacion($idImplementacion){
        $matchImplementacion = Match1::find()->where(['implementacion_id_implementacion' => $idImplementacion])->all();
        if($matchImplementacion != null){
            return $data = \yii\helpers\ArrayHelper::map($matchImplementacion, 'requerimientoIdRequerimiento.sciIdSci.id_sci', 'requerimientoIdRequerimiento.sciIdSci.nombre');
        }else{
            return $data = '';
        }
    }

    public function getEstadoLista(){
        $droptions = EstadoImplementacion::find()->all();
        return ArrayHelper::map($droptions, 'id_estado_implementacion', 'nombre_estado');
    }

    public static function getTieneTodosLosDatos($implementacion){
        //PARAMETRO MODELO DE IMPLEMENTACION
        $secciones = $implementacion->seccions;
        if($secciones != null){
            $bandera = false;
            foreach ($secciones as $seccion){
                $gruposTrabajoDetalle = $seccion->grupoTrabajos;
                if($gruposTrabajoDetalle != null){
                    $cantidadGrupos = count($gruposTrabajoDetalle);

                    $contLider = 0;
                    foreach ($gruposTrabajoDetalle as $grupoTrabajoDetalle){
                        $liderDetalle = $grupoTrabajoDetalle->alumnoInscritoLider;
                        if($liderDetalle != null){
                            $contLider = $contLider + 1;
                        }
                    }

                    $contSociosB = 0;
                    foreach ($gruposTrabajoDetalle as $grupoTrabajoDetalle){
                        $socioBDetalle = $grupoTrabajoDetalle->getGrupoTrabajoHasScbs()->orderBy(['creado_en' => SORT_DESC])->one();

                        if($socioBDetalle != null){
                            $contSociosB = $contSociosB + 1;
                        }
                    }

                    if(($contLider == $cantidadGrupos) && ($contSociosB == $cantidadGrupos)){
                        $bandera = true;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }
            return $bandera;
        }else{
            return false;
        }

        //count($seccionCreada->grupoTrabajos)

    }//FIN COMPROBACIÓN

    //GET SOCIOS SIN IDES AGRUPADAS
    public function getSocios(){
        $stringARetornar = "";
        $aux = [];
        $filasMatch1 = Match1::find()->where(['implementacion_id_implementacion' => $this->id_implementacion,
        'anio_match1' => $this->anio_implementacion,
        'semestre_match1' => $this->semestre_implementacion])->all();
        if($filasMatch1 != null){
            foreach ($filasMatch1 as $filaMatch1){
                $idSocio = $filaMatch1->requerimientoIdRequerimiento->sci_id_sci;
                if(!in_array($idSocio,$aux)) {
                    $stringARetornar = $stringARetornar . $filaMatch1->requerimientoIdRequerimiento->sciIdSci->nombre . "<br>";
                    $aux[] = $idSocio;
                }
            }
        }else{
            $stringARetornar = "No tiene";
        }
        return $stringARetornar;
    }
}
