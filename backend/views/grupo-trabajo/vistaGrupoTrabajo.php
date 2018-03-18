<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\BitacoraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vista Grupo de Trabajo';
$this->params['breadcrumbs'][] = ['label' => 'Selección Asignatura', 'url' => ['/grupo-trabajo/seleccion-asignatura']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="seleccion-asignatura-index">
    <div class="box box-primary">
        <div class="box-body">
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <th>RUT Alumno</th>
                    <th>Nombre Alumno</th>
                    <th>Email</th>
                </tr>
            <?php if($alumnos != null){
                foreach ($alumnos as $alumno){
            ?>

                    <tr>
                        <td><?= Yii::$app->formatter->asRut($alumno->alumnoRutAlumno->rut_alumno) ?></td>
                        <td><?= $alumno->alumnoRutAlumno->nombre ?></td>
                        <td><?= $alumno->alumnoRutAlumno->email ?></td>
                    </tr>

            <?php
                }//FIN FOREACH
            ?>
                </tbody>
            </table>
            <?php
            }//FIN IF ?>
        </div>
    </div>
</div>
