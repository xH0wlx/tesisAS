<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 11-09-2016
 * Time: 13:43
 */
use yii\helpers\Html;
use yii\helpers\Url;
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">Alumno</li>
            <li><a href="<?= Html::encode(Url::toRoute('/bitacora/seleccion-asignatura', true))?>"><i class="fa fa-plus"></i> Crear Bitácora</a></li>
            <li><a href="<?= Html::encode(Url::toRoute('/bitacora/seleccion-asignatura-i', true))?>"><i class="fa fa-edit"></i> Ver / Modificar Bitácoras</a></li>
            <li><a href="<?= Html::encode(Url::toRoute('/grupo-trabajo/seleccion-asignatura', true))?>"><i class="fa fa-eye"></i> Ver Grupo de Trabajo</a></li>
            <li><a href="<?= Html::encode(Url::toRoute('/user/perfil-usuario', true))?>"><i class="fa fa-gear"></i> Cambiar contraseña</a></li>
            <br>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>