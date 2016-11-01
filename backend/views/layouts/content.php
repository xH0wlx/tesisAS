<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 11-09-2016
 * Time: 13:43
 */
use yii\widgets\Breadcrumbs;
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $this->title?>
            <!--<small>Optional description</small>-->
        </h1>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrums']) ? $this->params['breadcrumbs'] : []
        ])?>
        <!--<ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>-->
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Your Page Content Here -->
        <?= $content ?>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->