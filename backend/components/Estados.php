<?php
/**
 * Created by PhpStorm.
 * User: Howl
 * Date: 11-09-2017
 * Time: 2:30
 */

namespace backend\components;

use backend\models\EstadoEjecucion;
use backend\models\Implementacion;
use backend\models\Requerimiento;
use backend\models\Servicio;
use yii\base\Component;

class Estados extends Component
{
    //ESTADOS DEL REQUERIMIENTOS
    //1.- ESTADO PARA SABER SI TIENE ASIGNATURAS CANDIDATAS ASIGNADAS
    public function asignarEstadoMatch1($model, $boolean)
    {
        //MODEL, EL MODELO A CAMBIAR EL ESTADO
        //SI BOOLEAN = TRUE entonces tiene asignaturas candidatas asignadas
        //SI BOOLEAN = FALSE entonces NO tiene asignaturas candidatas asignadas
    }

    public function obtenerIdPorNombre($nombreEstado){
        //ESTADOS: No Asignado, Asignado, En Desarrollo, Finalizado (PRIMERAS EN MAYUSCULA)
        //DEVUELVE LA ID DEL ESTADO, FALSE EN CASO DE NO ENCONTRARLO
        $estados = array("No Asignado", "Asignado", "En Desarrollo", "Finalizado");
        if (in_array($nombreEstado, $estados)) {
            $estadoModel = EstadoEjecucion::find()->where(['nombre_estado' => $nombreEstado])->one();
            if($estadoModel != null){
                return $estadoModel->id_estado;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function actualizarEstadoRequerimiento($id_requerimiento, $nombreEstado)
    {
        $id_estado_asignado = $this->obtenerIdPorNombre($nombreEstado);
        if(is_numeric($id_estado_asignado)){
            $requerimientoModel = Requerimiento::findOne($id_requerimiento);
            if($requerimientoModel != false){
                $requerimientoModel->estado_ejecucion_id_estado = intval($id_estado_asignado);
                return $requerimientoModel->save(false);
            }else{
                return false; //NO ENCONTRÓ EL MODELO DEL REQUERIMIENTO
            }
        }else{
            return false; //NO ENCONTRÓ EL ID DEL ESTADO
        }
    }

    public function actualizarEstadoServicio($id_servicio, $nombreEstado)
    {
        $id_estado_asignado = $this->obtenerIdPorNombre($nombreEstado);
        if(is_numeric($id_estado_asignado)){
            $servicioModel = Servicio::findOne($id_servicio);
            if($servicioModel != false){
                $servicioModel->estado_ejecucion_id_estado = intval($id_estado_asignado);
                return $servicioModel->save(false);
            }else{
                return false; //NO ENCONTRÓ EL MODELO DEL REQUERIMIENTO
            }
        }else{
            return false; //NO ENCONTRÓ EL ID DEL ESTADO
        }
    }

    public function tieneDatosCompletos($idImplementacion){
        //DEVUELVE TRUE SI TIENE TODOS LOS DATOS NECESARIOS
        $implementacion = Implementacion::findOne($idImplementacion);
        if($implementacion != null){
            $secciones = $implementacion->seccions;
            if($secciones != null){
                foreach ($secciones as $seccion){
                    $alumnosInscritos = $seccion->alumnoInscritoSeccions;
                    if($alumnosInscritos != null){
                        $gruposTrabajo = $seccion->grupoTrabajos;
                        if($gruposTrabajo != null){
                            foreach ($gruposTrabajo as $grupoTrabajo){
                                $socioBeneficiario = $grupoTrabajo->ultimoSocioBeneficiario;
                                $lider = $grupoTrabajo->alumnoInscritoLider;
                                if($socioBeneficiario == null || $lider == null){
                                    return false;
                                }
                            }
                        }else{
                            return false; //FALTAN GRUPOS DE TRABAJO
                        }
                    }else{
                        return false; //FALTA INSCRIBIR ALUMNOS
                    }
                }//FIN FOR SECCIONES
                return true;//PASO TODAS LAS VALIDACIONES :D!!!!!!!
            }else{
                return false; //AUN NO SE CREAN SECCIONES
            }
        }else{
            return false; //IMPLEMENTACION NO ENCONTRADA
        }
    }

    public function actualizarEstadoImplementacion($model, $boolean)
    {

    }
}