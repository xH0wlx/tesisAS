<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>

<b><?= $alumno ?>, ha creado/modificado una bitácora.</b>
<br><br>
<table cellspacing="0" cellpadding="10" border="1">
    <tbody>
    <tr>
        <th colspan="2"> Datos de la Bitácora </th>
    </tr>
    <tr>
        <td width="80" >Fecha</td>
        <td width="280"><p><?= $model->fecha_bitacora ?></p></td>
    </tr>
    <tr>
        <td>Hora Inicio</td>
        <td><p><?= $model->hora_inicio ?></p></td>
    </tr>
    <tr>
        <td>Hora Finalización</td>
        <td><p><?= $model->hora_termino ?></p></td>
    </tr>
    <tr>
        <td>Actividad Planificada</td>
        <td><p><?= $model->actividad_realizada ?></p></td>
    </tr>
    <tr>
        <td>Resultados</td>
        <td><p><?= $model->resultados ?></p></td>
    </tr>
    <tr>
        <td>Observaciones</td>
        <td><p><?= $model->observaciones ?></p></td>
    </tr>
    </tbody>
</table>
<br>
<b>Grupo de Trabajo</b>
<br>
<?= $grupoTrabajo ?><br>