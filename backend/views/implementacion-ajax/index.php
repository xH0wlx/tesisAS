<?php
/* @var $this yii\web\View */
?>
<p>
    You may change the content of this page by modifying
    the file <code><?= __FILE__; ?></code>.
</p>

<table class="table">
    <thead>
    <tr>
        <th>Código</th>
        <th>Nombre</th>
        <th>Id Carrera</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><?=$asignatura->cod_asignatura?></td>
        <td><?=$asignatura->nombre?></td>
        <td><?=$asignatura->id_carrera?></td>
    </tr>
    </tbody>
</table>
