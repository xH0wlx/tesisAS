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
            <li class="header">Docente</li>
            <li><a href="<?= Html::encode(Url::toRoute('/bitacora/ver-bitacoras-docente', true))?>"><i class="fa fa-eye"></i> Ver Bitácoras</a></li>
            <li><a href="<?= Html::encode(Url::toRoute('/user/perfil-usuario', true))?>"><i class="fa fa-gear"></i> Cambiar Contraseña</a></li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>