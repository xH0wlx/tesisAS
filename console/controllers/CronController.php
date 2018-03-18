<?php
namespace console\controllers;
use backend\models\Implementacion;
use yii\console\Controller;
use yii\helpers\Console;
use Yii;
/**
* Console crontab actions
*/
class CronController extends Controller{
    /**
     * Regenerates timestamp
     */
    public function actionTimestamp()
    {
        file_put_contents(Yii::getAlias(
            '@app/timestamp.txt'),
            time());
        $this->stdout('Done!',
            Console::FG_GREEN, Console::BOLD);
        $this->stdout(PHP_EOL);
    }

    public function actionEnviarNotificacionSocioBeneficiario($id){
        ini_set('max_execution_time', 0);
        //SE ENVÍA UN CORREO POR GRUPO Y AL SOCIO BENEFICIARIO
        $implementacion = Implementacion::findOne($id);
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
                            $messages[] = Yii::$app->mailer->compose('viewEmailBeneficiarioAlumno', [
                                'seccion' => $seccion,
                                'grupoTrabajo' => $grupoTrabajo,
                                'numeroSeccion' => $numeroSeccion,
                                'nombreAsignatura' => $nombreAsignatura,
                            ])
                                ->setFrom([Yii::$app->params["adminEmail"] => "Programa Aprendizaje Servicio"])
                                ->setTo('lummunoz@alumnos.ubiobio.cl')
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
        return true;
    }
}