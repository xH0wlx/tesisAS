<?php
/**
 * Created by PhpStorm.
 * User: Howl
 * Date: 11-09-2017
 * Time: 2:30
 */

namespace backend\components;

use backend\models\Alumno;
use backend\models\GrupoTrabajo;
use backend\models\Implementacion;
use yii\base\Component;
use yii;

class Correos extends Component
{
    public function enviarNotificacionBitacoraX($model , $username, $idGrupoTrabajo)
    {
        //$model posee los datos de la bitácora
        //$username es el alumno que envío la bitácora, equivale a su RUT
        //$idGrupoTrabajo es la id del grupo de trabajo obtenida por el formulario seleccionado por el usuario logueado
        //DEBE SER ENVIADA A TODOS LOS INTEGRANTES DEL GRUPO DE TRABAJO DEL ALUMNO
        // $this->correosGrupoAlumnosFormateado($idGrupoTrabajo);

        $alumnoEnviador = Alumno::findOne($username);
        $grupoTrabajo = GrupoTrabajo::findOne($idGrupoTrabajo);
        if($alumnoEnviador != null && $grupoTrabajo != null){
            $alumnosInscritos = $grupoTrabajo->alumnoInscritoSeccionIdAlumnoInscritoSeccions; //OBTIENE LOS ALUMNOS DE LA TABLA INSCRITOS
            if($alumnosInscritos != null){
                Yii::$app->mailer->compose('viewEmailBitacoraAlumno', [
                    'alumno' => $alumnoEnviador->nombre,
                    'model' => $model,
                    'grupoTrabajo' => $this->grupoTrabajoAsString($alumnosInscritos),
                ])
                    ->setFrom([Yii::$app->params["adminEmail"] => "Programa Aprendizaje Servicio"])
                    ->setTo($this->correosGrupoAlumnosFormateado($grupoTrabajo))
                    ->setSubject('Bitácora del '.date("d-m-y"))
                    ->send();
                return true;
            }
        }
        return false;
    }

    public function grupoTrabajoAsStringX($alumnosInscritos)
    {
        $encabezadoTabla = '<table cellspacing="0" cellpadding="10" border="1">
                    <tbody>
                        <tr>
                            <th width="60">RUT</th>
                            <th width="160">Nombre</th>
                            <th width="110">Email</th>
                        </tr>';

        foreach ($alumnosInscritos as $alumnoInscrito){
            $alumno = $alumnoInscrito->alumnoRutAlumno;
            $filaAlumno = '<tr>
                            <td><p>'.$alumno->rut_alumno.'</p></td>
                            <td><p>'.$alumno->nombre.'</p></td>
                            <td><p>'.$alumno->email.'</p></td>
                        </tr>';
            $encabezadoTabla = $encabezadoTabla.$filaAlumno;
        }
        $finalTabla = "</tbody>
                </table>";

        return $encabezadoTabla.$finalTabla;

    }

    public function correosGrupoAlumnosFormateadoX($grupoTrabajo){
        //GRUPO DE TRABAJO ES EL MODELO DEL GRUPO DE TRABAJO
            $alumnosInscritos = $grupoTrabajo->alumnoInscritoSeccionIdAlumnoInscritoSeccions;
        $aRetornar = [];
        if($alumnosInscritos != null){
            foreach ($alumnosInscritos as $alumnoInscrito){
                $alumno = $alumnoInscrito->alumnoRutAlumno;
                $filaAlumno = $alumno->email;
                $aRetornar [] = $filaAlumno;
            }
        }
        return $aRetornar;
    }

    public function enviarNotificacionSocioBeneficiarioX($idImplementacion){
        ignore_user_abort(true);
        set_time_limit(0);
        //ini_set('max_execution_time', 0);
        //SE ENVÍA UN CORREO POR GRUPO Y AL SOCIO BENEFICIARIO
        $implementacion = Implementacion::findOne($idImplementacion);
        if($implementacion != null){
            $secciones = $implementacion->seccions;
            if($secciones != null){
                foreach ($secciones as $seccion){
                    $gruposTrabajo = $seccion->grupoTrabajos;
                    $numeroSeccion = $seccion->numero_seccion;
                    $nombreAsignatura = $seccion->implementacionIdImplementacion->asignaturaCodAsignatura->nombre_asignatura;
                    if($gruposTrabajo != null){
                        $messages = [];
                        foreach ($gruposTrabajo as $grupoTrabajo){
                            $arregloSetTo = $this->correosGrupoAlumnosFormateado($grupoTrabajo);
                            $contactos = $grupoTrabajo->ultimoSocioBeneficiario->contactoScbs;

                            if($contactos != null){
                                foreach ($contactos as $contacto){
                                    $arregloSetTo[] = $contacto->email;
                                }
                            }

                            $messages[] = Yii::$app->mailer->compose('viewEmailBeneficiarioAlumno', [
                                'seccion' => $seccion,
                                'grupoTrabajo' => $grupoTrabajo,
                                'numeroSeccion' => $numeroSeccion,
                                'nombreAsignatura' => $nombreAsignatura,
                            ])
                            ->setFrom([Yii::$app->params["adminEmail"] => "Programa Aprendizaje Servicio"])
                            ->setTo($arregloSetTo)
                            ->setSubject('Resumen de Asignación Socio Beneficiario -> Grupo de Trabajo');
                        }//FIN FOREACH GRUPOS
                        if(!empty($messages)){
                            Yii::$app->mailer->sendMultiple($messages);
                        }
                    }
                }//FOR SECCIONES
                return true;
            }
        }
        return false;
    }

    public function enviarNotificacionDocenteX($idImplementacion){
        ignore_user_abort(true);
        set_time_limit(0);
        //CUMPLE EL OBJETIVO DE INFORMAR TODOS LOS GRUPOS CON SU SOCIO DE LA SECCIÓN RESPECTIVA
        $implementacion = Implementacion::findOne($idImplementacion);
        if($implementacion != null){
            $secciones = $implementacion->seccions;
            if($secciones != null){
                $messages = [];
                foreach ($secciones as $seccion){
                    $gruposTrabajo = $seccion->grupoTrabajos;
                    if($gruposTrabajo != null){
                        $messages[] = Yii::$app->mailer->compose('viewEmailDocente', [
                            'seccion' => $seccion,
                            'gruposTrabajo' => $gruposTrabajo,
                        ])
                        ->setFrom([Yii::$app->params["adminEmail"] => "Programa Aprendizaje Servicio"])
                        ->setTo($seccion->docenteRutDocente->email)
                        ->setSubject('Resumen de Asignaciones Socio Beneficiario -> Grupo de Trabajo');
                    }
                }//FOR SECCIONES
                if(!empty($messages)){
                    Yii::$app->mailer->sendMultiple($messages);
                }
                return true;
            }
        }
        return false;
    }


}