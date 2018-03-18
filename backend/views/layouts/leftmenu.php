<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 11-09-2016
 * Time: 13:43
 */
use yii\helpers\Html;
use yii\helpers\Url;

$todos = Yii::$app->db->createCommand('SELECT COUNT(*) FROM requerimiento WHERE isDeleted= false')->queryScalar();
$noAsignados = Yii::$app->db->createCommand('SELECT COUNT(*) FROM requerimiento WHERE isDeleted= false AND estado_ejecucion_id_estado = 1')->queryScalar();
$enDesarrollo =  Yii::$app->db->createCommand('SELECT COUNT(*) FROM requerimiento WHERE isDeleted= false AND estado_ejecucion_id_estado = 2')->queryScalar();
$enDesarrolloBueno =  Yii::$app->db->createCommand('SELECT COUNT(*) FROM requerimiento WHERE isDeleted= false AND estado_ejecucion_id_estado = 4')->queryScalar();
$finalizados =  Yii::$app->db->createCommand('SELECT COUNT(*) FROM requerimiento WHERE isDeleted= false AND estado_ejecucion_id_estado = 3')->queryScalar();
$enPapelera =  Yii::$app->db->createCommand('SELECT COUNT(*) FROM requerimiento WHERE isDeleted = true')->queryScalar();


?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar" >
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <?php
        if (Yii::$app->user->isGuest) {
        ?>
            <!--<div class="user-panel">
                <div class="pull-left image">
                    <img src="<?/*= $baseUrl */?>/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>Invitado</p>
                    <a href="#"><i class="fa fa-circle text-danger"></i> Offline</a>
                </div>
            </div>-->
        <?php
        } else {
            ?>
            <!-- Sidebar user panel -->
            <!--<div class="user-panel">
                <div class="pull-left image">
                    <img src="<?/*= $baseUrl */?>/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p><?/*= Html::encode(Yii::$app->user->identity->username)*/?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>-->
            <?php
        }
        ?>
        <!-- search form -->
        <!--<form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Buscar...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>-->
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">SOCIOS</li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i> <span>Socios Comunitarios</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= Html::encode(Url::toRoute('/sci/index', true))?>"><i class="fa fa-circle-o"></i> Socio Comunitario Institucional</a></li>
                    <li><a href="<?= Html::encode(Url::toRoute('/contacto-sci/index', true))?>"><i class="fa fa-circle-o"></i> Contacto Institución</a></li>
                    <li><a href="<?= Html::encode(Url::toRoute('/scb/index', true))?>"><i class="fa fa-circle-o"></i> Socio Comunitario Beneficiario</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-list-ol"></i> <span>Requerimientos</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= Html::encode(Url::toRoute(['/requerimiento/index'], true))?>"><i class="fa fa-circle-o"></i> Todos
                            <span class="pull-right-container">
                                <span class="label label-primary pull-right"><?=$todos?></span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= Html::encode(Url::toRoute(['/requerimiento/index','estado'=>1], true))?>"><i class="fa fa-circle-o"></i> No Asignados
                            <span class="pull-right-container">
                                <span class="label label-danger pull-right"><?=$noAsignados?></span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= Html::encode(Url::toRoute(['/requerimiento/index','estado'=>2], true))?>"><i class="fa fa-circle-o"></i> Asignados
                            <span class="pull-right-container">
                                <span class="label label-warning pull-right"><?=$enDesarrollo?></span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= Html::encode(Url::toRoute(['/requerimiento/index','estado'=>4], true))?>"><i class="fa fa-circle-o"></i> En Desarrollo
                            <span class="pull-right-container">
                                <span class="label label-primary pull-right"><?=$enDesarrolloBueno?></span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= Html::encode(Url::toRoute(['/requerimiento/index','estado'=>3], true))?>"><i class="fa fa-circle-o"></i> Finalizados
                            <span class="pull-right-container">
                                <span class="label label-success pull-right"><?=$finalizados?></span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= Html::encode(Url::toRoute(['/requerimiento/index-papelera'], true))?>"><i class="fa fa-circle-o"></i> En Papelera
                            <span class="pull-right-container">
                                <span class="label label-info pull-right"><?=$enPapelera?></span>
                            </span>
                        </a>
                    </li>
                </ul>
            </li>

            <!--<li><a href="<?/*= Html::encode(Url::toRoute('/sci/principal', true))*/?>"><i class="fa fa-chain"></i> Socio Comunitario Inst.</a></li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-list"></i> <span>Requerimientos</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?/*= Html::encode(Url::toRoute(['/requerimiento/index','RequerimientoSearch[estado_ejecucion_id_estado]'=>1], true))*/?>"><i class="fa fa-circle-o"></i> No Asignados
                            <span class="pull-right-container">
                                <span class="label label-danger pull-right"><?/*=$noAsignados*/?></span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?/*= Html::encode(Url::toRoute(['/requerimiento/index','RequerimientoSearch[estado_ejecucion_id_estado]'=>2], true))*/?>"><i class="fa fa-circle-o"></i> En Desarrollo
                            <span class="pull-right-container">
                                <span class="label label-warning pull-right"><?/*=$enDesarrollo*/?></span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?/*= Html::encode(Url::toRoute(['/requerimiento/index','RequerimientoSearch[estado_ejecucion_id_estado]'=>3], true))*/?>"><i class="fa fa-circle-o"></i> Finalizados
                            <span class="pull-right-container">
                                <span class="label label-success pull-right"><?/*=$finalizados*/?></span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?/*= Html::encode(Url::toRoute(['/requerimiento/index-papelera'], true))*/?>"><i class="fa fa-circle-o"></i> En Papelera
                            <span class="pull-right-container">
                                <span class="label label-info pull-right"><?/*=$enPapelera*/?></span>
                            </span>
                        </a>
                    </li>
                </ul>
            </li>-->

            <li class="header">PLANIFICACIÓN</li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-handshake-o"></i> <span>Match 1</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
<!--                    <li><a href="<?/*= Html::encode(Url::toRoute('/match1/principal', true))*/?>"><i class="fa fa-handshake-o"></i> Requerimiento -> Asignatura</a></li>
-->                    <li><a href="<?= Html::encode(Url::toRoute('/match1/seleccion', true))?>"><i class="fa fa-plus-square-o"></i> Asignar/Modificar/Eliminar<br>&emsp;&ensp;&nbsp;Asignaturas</a></li>
                    <li><a href="<?= Html::encode(Url::toRoute('/match1/ver-resultado', true))?>"><i class="fa fa-eye" aria-hidden="true"></i> Ver/Modificar Match1 </a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-exchange"></i> <span>Servicios</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= Html::encode(Url::toRoute('/servicio/match-asociado', true))?>"><i class="fa fa-plus-square-o"></i> Asignar Servicio (con Match)</a></li>
                    <li><a href="<?= Html::encode(Url::toRoute('/servicio/ver-servicios-asignados', true))?>"><i class="fa fa-eye"></i> Ver/Eliminar Servicios<br>&emsp;&ensp;&nbsp;Asignados</a></li>
                    <li><a href="<?= Html::encode(Url::toRoute('/servicio/create', true))?>"><i class="fa fa-plus"></i> Ingresar Servicio (sin Match)</a></li>
                    <li><a href="<?= Html::encode(Url::toRoute('/servicio/ver-servicios-no-asignados', true))?>"><i class="fa fa-eye"></i> Ver/Eliminar Servicios<br>&emsp;&ensp;&nbsp;NO Asignados</a></li>
<!--                    <li><a href="<?/*= Html::encode(Url::toRoute('/servicio/index', true))*/?>"><i class="fa fa-list-ol"></i> Todos los Servicios Ingresados</a></li>
-->                </ul>
            </li>
            <li class="header">IMPLEMENTACIÓN</li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-university"></i> <span>Implementación</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= Html::encode(Url::toRoute('/implementacion/seleccion-asignatura', true))?>"><i class="fa fa-plus-square-o"></i> Crear Nueva Implementación</a></li>
<!--                    <li><a href="<?/*= Html::encode(Url::toRoute('/implementacion/seleccion-implementacion', true))*/?>"><i class="fa fa-pencil-square-o"></i> Modificar Implementación</a></li>
-->                    <li><a href="<?= Html::encode(Url::toRoute('/implementacion/index', true))?>"><i class="fa fa-eye"></i> Ver/Modificar Implementación</a></li>

                </ul>
            </li>
            <li class="header">SEGUIMIENTO</li>
            <li class="treeview">
                <a href="">
                    <i class="fa fa-book"></i> <span>Bitácoras</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= Html::encode(Url::toRoute('/bitacora/index', true))?>"><i class="fa fa-plus-square-o"></i> Todas las Bitácoras Ingresadas</a></li>
                </ul>
            </li>


            <li class="header">ADMINISTRACIÓN</li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-database"></i> <span>Gestión de Datos</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= Html::encode(Url::toRoute('/asignatura/index', true))?>"><i class="fa fa-database"></i> Asignaturas</a></li>
                    <li><a href="<?= Html::encode(Url::toRoute('/carrera/index', true))?>"><i class="fa fa-database"></i> Carreras</a></li>
                    <li><a href="<?= Html::encode(Url::toRoute('/facultad/index', true))?>"><i class="fa fa-database"></i> Facultades</a></li>
                    <li><a href="<?= Html::encode(Url::toRoute('/sede/index', true))?>"><i class="fa fa-database"></i> Sedes</a></li>
                    <li><a href="<?= Html::encode(Url::toRoute('/docente/index', true))?>"><i class="fa fa-database"></i> Docentes</a></li>
                    <li><a href="<?= Html::encode(Url::toRoute('/alumno/index', true))?>"><i class="fa fa-database"></i> Alumnos</a></li>
                    <li><a href="<?= Html::encode(Url::toRoute('/user/index', true))?>"><i class="fa fa-database"></i> Usuarios del Sistema</a></li>
                </ul>
            </li>


            <li class="header">REPORTES</li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-line-chart"></i> <span>Reportes</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= Html::encode(Url::toRoute('/bitacora/reporte-resumen', true))?>"><i class="fa fa-file-text-o"></i> Resumen Bitácora</a></li>
                    <li><a href="<?= Html::encode(Url::toRoute('/bitacora/reporte-estadistica', true))?>"><i class="fa fa-file-excel-o"></i> Estadística Bitácora</a></li>
                    <li><a href="<?= Html::encode(Url::toRoute('/implementacion/reporte-resumen', true))?>"><i class="fa fa-file-text-o"></i> Resumen Implementación</a></li>
                    <li><a href="<?= Html::encode(Url::toRoute('/implementacion/reporte-estadistica', true))?>"><i class="fa fa-file-excel-o"></i> Estadística Implementación.</a></li>
                    <li><a href="<?= Html::encode(Url::toRoute('/match1/reporte-resumen', true))?>"><i class="fa fa-file-text-o"></i> Resumen Req/Servicio</a></li>
                    <li><a href="<?= Html::encode(Url::toRoute('/match1/reporte-estadistica', true))?>"><i class="fa fa-file-excel-o"></i> Estadística Req/Servicio</a></li>
                </ul>
            </li>
            <li class="header">PERFIL DE USUARIO</li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-cogs"></i> <span>Usuario</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= Html::encode(Url::toRoute('/user/perfil-usuario', true))?>"><i class="fa fa-user"></i> Cambiar contraseña</a></li>
                </ul>
            </li>
            <br>
            <!--            <li class="treeview">
                            <a href="#">
                                <i class="fa fa-dashboard"></i> <span>Principal</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
                                <li class="active"><a href="index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
                            </ul>
                        </li>-->

<!--            <li class="active treeview">
                <a href="#">
                    <i class="fa fa-th"></i>
                    <span>Nueva implementación&nbsp;</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?/*= Html::encode(Url::toRoute('/implementacion/nueva', true))*/?>"><i class="fa fa-circle-o"></i> Nueva Asignatura</a></li>
                </ul>
            </li>-->

<!--            <li>
                <a href="pages/mailbox/mailbox.html">
                    <i class="fa fa-envelope"></i> <span>Correo</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-yellow">12</small>
              <small class="label pull-right bg-green">16</small>
              <small class="label pull-right bg-red">5</small>
            </span>
                </a>
            </li>-->

            <!--<li class="treeview">
                <a href="#">
                    <i class="fa fa-database"></i> <span>Gestión de Datos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?/*= Html::encode(Url::toRoute('/asignatura/index', true))*/?>"><i class="fa fa-book"></i> Asignaturas</a></li>
                    <li><a href="<?/*= Html::encode(Url::toRoute('/oferta/index', true))*/?>"><i class="fa fa-book"></i> Ofertas</a></li>

                    <li>
                        <a href="#"><i class="fa fa-users"></i> Socios Comunitarios
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <a href="<?/*= Html::encode(Url::toRoute('/sci/principal', true))*/?>"><i class="fa fa-user"></i> Institucional
                                </a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-user"></i> Institucional
                                    <span class="pull-right-container">
                                      <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="<?/*= Html::encode(Url::toRoute('/sci/index', true))*/?>"><i class="fa fa-circle-o"></i> Socios</a></li>
                                    <li><a href="<?/*= Html::encode(Url::toRoute('/contacto-sci/index', true))*/?>"><i class="fa fa-circle-o"></i> Contactos</a></li>
                                    <li><a href="<?/*= Html::encode(Url::toRoute('/requerimiento/index', true))*/?>"><i class="fa fa-circle-o"></i> Requerimientos</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                </ul>
            </li>-->


            <!--
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-file"></i> <span>Reportes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> De PLANIFICACIÓN</a></li>
                    <li>
                        <a href="#"><i class="fa fa-circle-o"></i> Level One
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                            <li>
                                <a href="#"><i class="fa fa-circle-o"></i> Level Two
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
                </ul>
            </li>


            <li class="header">Etiquetas</li>
            <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
            <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
            <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>-->
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>