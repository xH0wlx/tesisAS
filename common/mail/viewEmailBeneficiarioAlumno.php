<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
<h2>Sección N°<?= $numeroSeccion ?> de la Asignatura "<?= $nombreAsignatura ?>"</h2>
<br>
<table cellspacing="0" cellpadding="10" border="1">
    <tbody>
        <tr>
            <th colspan="3">Grupo N°<?= $grupoTrabajo->numero_grupo_trabajo ?></th>
        </tr>
        <tr>
            <th colspan="3">Socio Comunitario Beneficiario: <?=
                ($grupoTrabajo->ultimoSocioBeneficiario != null)?
                    $grupoTrabajo->ultimoSocioBeneficiario->nombre_negocio : "Socio aún no asignado." ?>
            </th>
        </tr>
        <tr>
            <th colspan="3">Integrantes del grupo de trabajo</th>
        </tr>
        <tr>
            <th width="60">RUT</th>
            <th width="160">Nombre</th>
            <th width="110">Email</th>
        </tr>
    <?php
    $alumnosInscritos = $grupoTrabajo->alumnoInscritoSeccionIdAlumnoInscritoSeccions;
    if($alumnosInscritos != null){
        foreach ($alumnosInscritos as $alumnoInscrito) {
            $alumno = $alumnoInscrito->alumnoRutAlumno;
            ?>
            <tr>
                <td><p><?= $alumno->rut_alumno ?></p></td>
                <td><p><?= $alumno->nombre ?></p></td>
                <td><p><?= $alumno->email ?></p></td>
            </tr>
            <?php
        }//FIN FOR ALUMNOS
    }else{
        ?>
        <tr>
            <td colspan="3"><p>No se registran datos del alumno.</p></td>
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>
