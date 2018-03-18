<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use kartik\dialog\Dialog;

echo Dialog::widget([
]);

$js = '
    $(\'.modalButton\').click(function(){
        $(\'#modal\').modal(\'show\')
        .find(\'#modalContent\')
        .load($(this).attr(\'value\'));
    });
        
//});
   
//FUNCIONES
function limpiarSeleccionados(){
    if (confirm("Está seguro de borrar todos los registros de la lista de seleccionados?") == true) {
            $.ajax({
               url: \''. Yii::$app->request->baseUrl.'/match1/limpiar-seleccion'.'\',
               type: \'post\',
               data: {
                         _csrf : \''.  Yii::$app->request->getCsrfToken().'\'
                     },
               success: function (data) {
                    //alert("Limpieza exitosa");
                    location.reload();
               },
               error: function (data){
                   alert("Error al limpiar los datos");
               }
          });//FIN AJAX  
    } else {
        txt = "You pressed Cancel!";
    }
            
}//FIN FUNCION DE LIMPIEZA
             
$(\'.seccion\').on("click", function(event){
    $(\'#informacion-seccion\').hide();
    /*$.ajax({
       url: \''. Yii::$app->request->baseUrl.'/implementacion-ajax/cargar-alumnos-inscritos'.'\',
       type: \'post\',
       data: {
                 //requerimientoPadre: event.target.value,
                 _csrf : \''.  Yii::$app->request->getCsrfToken().'\'
             },
       success: function (data) {
            $(\'#alumnos-inscritos\').replaceWith(data);
       },
       error: function (data){
           alert("Error al cargar alumnos inscritos.");
       }
    });//FIN AJAX*/
});

$(\'#correoDocente\').on("click", function(event){
    krajeeDialog.confirm("Se enviará un correo al docente de cada sección con el resumen de la implementación (grupos de trabajo conformados y socios comunitarios beneficiarios asignados).<br><b>Atención: Este proceso puede tardar unos minutos.</b>", function (result) {
        if (result) {
            $.ajax({
               url: \''. Yii::$app->request->baseUrl.'/implementacion/correo-docente'.'\',
               type: \'post\',
               data: {
                         id: \''.  Yii::$app->request->get('idImplementacion') .'\',
                         _csrf : \''.  Yii::$app->request->getCsrfToken().'\'
                     },
               beforeSend: function(){
                   $(\'#textoBotonDocente\').hide();
                   $(\'#cargaBotonDocente\').show();
               },      
               success: function (data) {
                    if(data.codigo == 1){
                        //1 es exito, 0 es error
                        krajeeDialog.alert(data.motivo);
                    }else if(data.codigo == 0){
                        krajeeDialog.alert(data.motivo);
                    }
               },
               error: function (data){
                   krajeeDialog.alert("Hubo un error al enviar los correos.");
               },
               complete: function(){
                   $(\'#cargaBotonDocente\').hide();
                   $(\'#textoBotonDocente\').show();
               }
            });
        } else { 

        }
    });
});

$(\'#correoBeneficiarioAlumno\').on("click", function(event){
    krajeeDialog.confirm("Se enviará un correo a cada alumno de la/las sección/es informando su grupo de trabajo y socio comunitario beneficiario asignado, lo mismo para cada socio comunitario beneficiario.<br><b>Atención: Este proceso puede tardar unos minutos.</b>", function (result) {
        if (result) { // ok button was pressed
            $.ajax({
               url: \''. Yii::$app->request->baseUrl.'/implementacion/correo-beneficiario-alumno'.'\',
               type: \'post\',
               data: {
                         id: \''.  Yii::$app->request->get('idImplementacion') .'\',
                         _csrf : \''.  Yii::$app->request->getCsrfToken().'\'
                     },
               beforeSend: function(){
                   $(\'#textoBotonBeneficiarioAlumno\').hide();
                   $(\'#cargaBotonBeneficiarioAlumno\').show();
               },      
               success: function (data) {
                    if(data.codigo == 1){
                        //1 es exito, 0 es error
                        krajeeDialog.alert(data.motivo);
                    }else if(data.codigo == 0){
                        krajeeDialog.alert(data.motivo);
                    }
               },
               error: function (data){
                   krajeeDialog.alert("Hubo un error al enviar los correos.");
               },
               complete: function(){
                   $(\'#cargaBotonBeneficiarioAlumno\').hide();
                   $(\'#textoBotonBeneficiarioAlumno\').show();
               }
            });
        } else { // confirmation was cancelled
            // execute your code for cancellation
        }
    });
});
';
$this->registerJs($js);

$requestPrevio = Yii::$app->request->referrer;

$this->title = 'Panel Implementación';
$this->params['breadcrumbs'][] = ['label' => 'Implementaciones', 'url' => ['index']];
if($requestPrevio != null){
    if(strpos($requestPrevio, 'vista-general') !== false){

        $this->params['breadcrumbs'][] = ['label' => 'Vista General', 'url' => ['/implementacion/vista-general', 'id'=>Yii::$app->request->get('idImplementacion')]];
    }
}
$this->params['breadcrumbs'][] = $this->title;

switch($implementacion->estado){
    case 0:
        $estadoImp = "No Publicada";
        break;
    case 1;
        $estadoImp = "Publicada";
        break;
    case 2;
        $estadoImp = "Finalizada";
        break;
}


$cantidadRequerimientosMatch1 = count($coincidenciasMatch1);
if($coincidenciasMatch1 != null){

?>
<div class="box box-solid box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><span class="glyphicon glyphicon-eye-open"></span> Detalle General <span class="badge"><?= $estadoImp ?></span></h3>
        <div class="pull-right">
            <?= Html::a('<i class="fa fa-chain"></i> Ir a vista general de la implementación', ['/implementacion/vista-general', 'id' => $implementacion->id_implementacion],
                ['style' => 'color: white;','class' => 'btn btn-md btn-primary']) ?>
        </div>

    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="box box-primary collapsed-box">
            <div class="box-header with-border"><h3 class="box-title">Periodo: &nbsp;<b><?=$implementacion->anio_implementacion?> -
                        <?=$implementacion->semestre_implementacion?></b></h3>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">Asignatura</h3>
                <div class="box-tools pull-right">
                    (Ver Detalle)<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body" style="display: block;">
                <table class="table table-bordered">
                    <tbody><tr>
                        <th>Código Asignatura</th>
                        <th>Nombre Asignatura</th>
                        <th>Carrera</th>
                        <th>Sede</th>
                    </tr>
                    <tr>
                        <td><?=$coincidenciasMatch1[0]->asignaturaCodAsignatura->cod_asignatura?></td>
                        <td><?=$coincidenciasMatch1[0]->asignaturaCodAsignatura->nombre_asignatura?></td>
                        <td><?=$coincidenciasMatch1[0]->asignaturaCodAsignatura->carreraCodCarrera->nombre_carrera?></td>
                        <td><?=$coincidenciasMatch1[0]->asignaturaCodAsignatura->carreraCodCarrera->facultadIdFacultad->sedeIdSede->nombre_sede?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box box-primary collapsed-box">
            <div class="box-header with-border"><h3 class="box-title">Servicio</h3>
                <div class="box-tools pull-right">
                    (Ver Detalle)<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body" style="display: none;">
                <table class="table table-bordered">
                    <tbody><tr>
                        <th>Título</th>
                        <th>Descripción</th>
                    </tr>
                    <tr>
                        <td><?=$coincidenciasMatch1[0]->servicioIdServicio->titulo?></td>
                        <td><?=$coincidenciasMatch1[0]->servicioIdServicio->descripcion?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box box-primary collapsed-box">
            <div class="box-header with-border"><h3 class="box-title">Requerimientos</h3>
                <div class="box-tools pull-right">
                    (Ver Detalle)<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body" style="display: none;">
                <table class="table table-bordered">
                    <tbody><tr>
                        <th style="width: 10px">#</th>
                        <th>Título</th>
                        <th>Descripción</th>
                    </tr>
                    <?php
                    for($i=0; $i < $cantidadRequerimientosMatch1; $i++){
                        ?>
                        <tr>
                            <td><?=($i+1)?>.</td>
                            <td><?=$coincidenciasMatch1[$i]->requerimientoIdRequerimiento->titulo?></td>
                            <td><?=$coincidenciasMatch1[$i]->requerimientoIdRequerimiento->descripcion?></td>
                        </tr>
                        <?php
                    };
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
</div>
<?php }else{
    ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-info"></i> Atención</h4>
        Esta implementación no posee los datos requeridos para ser valida.</b>
    </div>
<?php
}//FIN IF COINIDENCIAS MATCH ?>

<!-- ESTADO IMPLEMENTACIÓN-->

<?php
$seccionesCreadas = $secciones;//$implementacion->seccions;
//$cantidadSecciones != 0
if(true){
?>

<div class="box box-solid box-default">
    <div class="box-header with-border">
        <h4 class="box-title">
            <span class="glyphicon glyphicon-pencil"></span> Ingreso de Datos Implementación
        </h4>
        <div class="pull-right">
            <?php
                if($tieneDatosCompletos){
                    echo Html::button('
                        <div id="cargaBotonDocente" class="progress progress-striped active" style="display: none; margin-bottom:0;"><div class="progress-bar" style="width: 100%">&nbsp;&nbsp;&nbsp;Enviando Correos&nbsp;&nbsp;&nbsp;</div></div>
                        <div id="textoBotonDocente"><i class="fa fa-envelope"></i> Enviar resumen de implementación al docente</div>',
                        ['style' => 'color: white;', 'id'=> 'correoDocente','class' => 'btn btn-md btn-success']);
                    echo "&nbsp;";
                    echo Html::button('
                        <div id="cargaBotonBeneficiarioAlumno" class="progress progress-striped active" style="display: none; margin-bottom:0;"><div class="progress-bar" style="width: 100%">&nbsp;&nbsp;&nbsp;Enviando Correos&nbsp;&nbsp;&nbsp;</div></div>
                        <div id="textoBotonBeneficiarioAlumno"><i class="fa fa-envelope"></i> Enviar asignación a Socio Beneficiario y Alumnos</div>',
                        ['style' => 'color: white;','id'=> 'correoBeneficiarioAlumno','class' => 'btn btn-md btn-success']);
                }
            ?>
        </div>
    </div>
    <div class="box-body">
        <?php if($implementacion->estado == 0){ ?>
        <div class="table-responsive">
            <table class="table tabla-panel">
                <tbody>
                <tr>
                    <th colspan="3"><i class="fa fa-sitemap" aria-hidden="true"></i> Gestión de Secciones</th>
                </tr>
                <tr>
                    <td colspan="3">
                        <?php
                        if($cantidadSecciones == 0){
                            echo  Html::a('<span class="glyphicon glyphicon-pencil"></span> Crear sección/es', Url::toRoute('/implementacion/crear-seccion?idImplementacion='.$implementacion->id_implementacion, true), ['class'=>'btn btn-success btn-block']);
                        }else{
                            //echo  Html::button('Modificar sección/es', ['class' => 'btn btn-primary']);
                            echo  Html::a('<span class="glyphicon glyphicon-pencil"></span> Modificar sección/es', Url::toRoute('/implementacion/modificar-seccion?idImplementacion='.$implementacion->id_implementacion, true), ['class' => 'btn btn-primary btn-block']);
                        }

                        ?>
                    </td>
                </tr>
                <tr>
                    <th class="col-lg-4"><i class="fa fa-list-ol" aria-hidden="true"></i> Sección</th>
                    <th class="col-lg-4"><span class="glyphicon glyphicon-education"></span> Alumnos Inscritos</th>
                    <th class="col-lg-4 col-md-2"><i class="fa fa-group" aria-hidden="true"></i> Grupos De Trabajo / Líderes / Socios B.</th>
                </tr>
                <?php
                    if(count($seccionesCreadas) == 0){
                ?>

                    <tr>
                        <td>
                            <ul class="list-group">
                                <li class="list-group-item list-group-item-info">
                                    Sección
                                </li>
                                <li class="list-group-item">
                                    <?php echo "<i class=\"fa fa-warning text-yellow\"></i> Debe <b>Crear Sección/es</b>";
                                    ?>
                                </li>
                            </ul>
                        </td>
                        <td>
                            <ul class="list-group">
                                <li class="list-group-item list-group-item-info">
                                    Alumnos Inscritos
                                </li>
                                <li class="list-group-item">
                                    <?php echo "<i class=\"fa fa-warning text-yellow\"></i> Debe <b>Crear Sección/es</b>";
                                    ?>
                                </li>
                            </ul>
                        </td>
                        <td>
                            <ul class="list-group">
                                <li class="list-group-item list-group-item-info">
                                    Grupos Creados
                                </li>
                                <li class="list-group-item">
                                    <?php echo "<i class=\"fa fa-warning text-yellow\"></i> Debe <b>Crear Sección/es</b>";
                                    ?>
                                </li>
                            </ul>
                        </td>
                    </tr>
                <?php
                    }//FIN IF SI NO HAY SECCIONES
                ?>


                <?php
                foreach($seccionesCreadas as $i => $seccionCreada){
                    ?>
                    <tr>
                        <!-- NUMERO DE SECCIÓN -->
                        <td>
                            <ul class="list-group">
                                <li class="list-group-item list-group-item-info">
                                    Sección <b>N°<?= ($i+1) ?></b>: Docente
                                </li>
                                <li class="list-group-item">
                                    <?= $seccionCreada->docenteRutDocente->nombre_completo ?>
                                </li>
                            </ul>
                        </td>

                        <!-- ALUMNOS INSCRITOS -->
                        <td>
                            <ul class="list-group">
                                <li class="list-group-item list-group-item-info">
                                    <span class="badge"><?=count($seccionCreada->alumnoInscritoSeccions)?></span>
                                    Alumnos Inscritos
                                </li>
                                <li class="list-group-item">
                                    <?php
                                    if(count($seccionCreada->alumnoInscritoSeccions) != 0){
                                        echo  Html::a('<span class="glyphicon glyphicon-pencil"></span> Modificar Alumnos',
                                            Url::toRoute(['/implementacion/modificar-inscripcion',
                                                'idImplementacion'=>$implementacion->id_implementacion,
                                                'idSeccion'=>$seccionCreada->id_seccion]),
                                            ['class'=>'btn btn-primary btn-block']);
                                    }else {
                                        echo Html::a('<span class="glyphicon glyphicon-pencil"></span> Inscribir Alumnos',
                                            Url::toRoute(['/implementacion/modificar-inscripcion',
                                                'idImplementacion' => $implementacion->id_implementacion,
                                                'idSeccion' => $seccionCreada->id_seccion]),
                                            ['class' => 'btn btn-success btn-block']);
                                    }//FIN ELSE
                                    ?>
                                </li>
                            </ul>
                        </td>

                        <!-- GRUPOS DE TRABAJO -->
                        <?php
                        $gruposTrabajoDetalle = $seccionCreada->grupoTrabajos;
                        $contLider = 0;
                        foreach ($gruposTrabajoDetalle as $grupoTrabajoDetalle){
                            $liderDetalle = $grupoTrabajoDetalle->alumnoInscritoLider;
                            if($liderDetalle != null){
                                $contLider = $contLider + 1;
                            }
                        }
                        ?>
                        <?php
                        $contSociosB = 0;
                        foreach ($gruposTrabajoDetalle as $grupoTrabajoDetalle){
                            $socioBDetalle = $grupoTrabajoDetalle->getGrupoTrabajoHasScbs()->orderBy(['creado_en' => SORT_DESC])->one();

                            if($socioBDetalle != null){
                                $contSociosB = $contSociosB + 1;
                            }
                        }
                        ?>
                        <td>
                            <ul class="list-group">
                                <li class="list-group-item list-group-item-info">
                                    <span class="badge"><?=count($seccionCreada->grupoTrabajos)?></span>
                                    Grupos Creados
                                </li>
                                <li class="list-group-item">
                                    <?php
                                    if (count($seccionCreada->grupoTrabajos) != 0) {
                                        echo  Html::a('<span class="glyphicon glyphicon-pencil"></span> Modificar Grupos',
                                            Url::toRoute(['/implementacion/modificar-grupos-trabajo',
                                                'idImplementacion'=>$implementacion->id_implementacion,
                                                'idSeccion'=>$seccionCreada->id_seccion]),
                                            ['class'=>'btn btn-primary btn-block']);
                                    } else {
                                        if(count($seccionCreada->alumnoInscritoSeccions) != 0){
                                            echo  Html::a('<span class="glyphicon glyphicon-pencil"></span> Crear Grupos',
                                                Url::toRoute(['/implementacion/crear-grupos-trabajo',
                                                    'idImplementacion'=>$implementacion->id_implementacion,
                                                    'idSeccion'=>$seccionCreada->id_seccion]),
                                                ['class'=>'btn btn-success btn-block']);
                                        }else{
                                            echo "<i class=\"fa fa-warning text-yellow\"></i> Debe <b>Inscribir Alumnos</b> en esta sección";
                                        }
                                    }
                                    ?>
                                </li>
                                <li class="list-group-item list-group-item-info">
                                    <span class="badge"><?= $contLider ?> de <?=count($seccionCreada->grupoTrabajos)?></span>
                                    Líderes Asignados
                                </li>
                                <li class="list-group-item list-group-item-info">
                                    <span class="badge"><?= $contSociosB ?> de <?=count($seccionCreada->grupoTrabajos)?></span>
                                    Socios Benef. Asignados
                                </li>
                                <li class="list-group-item">
                                    <?php
                                    if (count($seccionCreada->grupoTrabajos) != 0) {
                                        echo  Html::a('<span class="glyphicon glyphicon-pencil"></span> Asignar Líderes y/o Socios C. Beneficiarios',
                                            Url::toRoute(['/implementacion/asignar-lider',
                                                'idImplementacion'=>$implementacion->id_implementacion,
                                                'idSeccion'=>$seccionCreada->id_seccion]),
                                            ['class'=>'btn btn-primary btn-block']);
                                    } else {
                                        if(count($seccionCreada->alumnoInscritoSeccions) == 0){
                                            echo "<i class=\"fa fa-warning text-yellow\"></i> Debe <b>Inscribir Alumnos</b> en esta sección";
                                        }else if(count($seccionCreada->grupoTrabajos) == 0){
                                            echo "<i class=\"fa fa-warning text-yellow\"></i> Debe <b>Crear Grupos</b> en esta sección";
                                        }
                                    }
                                    ?>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    <?php
                }//FIN FOR DE SECCIONES;
                ?>
                </tbody>
            </table>
        </div>

        <?php }else{
            echo "<h3>Implementación publicada (dar de baja para modificar).</h3>";
        }//FIN IF SI EL ESTADO ES PUBLICADO ?>

    </div>
</div>
<?php }//FIN IF
?>

<!--
<div class="row">
    <div class="col-md-6">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Secciones</h3>
                <div class="box-body">
                    <?php
                        if($cantidadSecciones == 0){
                            echo "<p>Sección/es no creadas</p>";
                            echo  Html::a('Crear sección/es', Url::toRoute('/implementacion/crear-seccion?idImplementacion='.$implementacion->id_implementacion, true), ['class'=>'btn btn-success']);
                        }else{
                            //echo  Html::button('Modificar sección/es', ['class' => 'btn btn-primary']);
                            echo  Html::a('Modificar sección/es', Url::toRoute('/implementacion/modificar-seccion?idImplementacion='.$implementacion->id_implementacion, true), ['class' => 'btn btn-primary']);
                        }

                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Selección de Sección</h3>
                        <div id="alumnos-inscritos" class="box-body">
                            <?php
                            if($cantidadSecciones == 0){
                                echo "<p>Sección/es no creadas</p>";
                            }else{
                                echo "<p>Seleccionar sección</p>";
                                /*for($i=0; $i < $cantidadSecciones;$i++){
                                    echo  Html::button('Sección N°'.($i+1), ['value'=>$arregloIdesSecciones[$i]["id_seccion"], 'class' => 'seccion btn btn-primary']);
                                    echo "<br><br>";
                                };*/
                                foreach ($secciones as $seccion){
                                    echo  Html::a('Sección N°'.($seccion->numero_seccion)." / ".$seccion->docenteRutDocente->nombre_completo, Url::toRoute(['/implementacion/panel-implementacion', 'idImplementacion'=>$implementacion->id_implementacion, 'idSeccion'=>$seccion->id_seccion]), ['class'=>'btn btn-primary']);
                                    //echo  Html::button('Sección N°'.($seccion->numero_seccion)." / ".$seccion->docenteRutDocente->nombre_completo, ['value'=>$seccion->id_seccion, 'class' => 'seccion btn btn-primary']);
                                    echo "<br><br>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Alumnos Inscritos</h3>
                <div id="alumnos-inscritos" class="box-body">
                    <?php
                    if(count($alumnosInscritos)!=0){
                        //echo "Hay Alumnos Inscritos";
                    }else{
                        echo "No hay Alumnos Inscritos";
                    }
                    echo "<br>";
                    if($idSeccionSeleccionada != null){
                        if(count($alumnosInscritos) != 0){
                            echo  Html::a('Modificar Alumnos',
                                Url::toRoute(['/implementacion/modificar-inscripcion',
                                    'idImplementacion'=>$implementacion->id_implementacion,
                                    'idSeccion'=>$idSeccionSeleccionada]),
                                ['class'=>'btn btn-primary']);
                        }else{
                            echo  Html::a('Inscribir Alumnos',
                                Url::toRoute(['/implementacion/modificar-inscripcion',
                                    'idImplementacion'=>$implementacion->id_implementacion,
                                    'idSeccion'=>$idSeccionSeleccionada]),
                                ['class'=>'btn btn-success']);
                        }
                    }else{
                        echo "Ninguna sección seleccionada";
                    }
                    echo "<br>";
                    if($secciones != null){
                        //echo "PUEDE INSCRIBIR ALUMNOS";
                    }else{
                        echo "NO PUEDE INSCRIBIR ALUMNOS";
                    }

                    ?>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="box box-solid">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Grupos de Trabajo</h3>
                    <div id="alumnos-inscritos" class="box-body">
                        <?php
                        if($idSeccionSeleccionada != null) {
                            if (count($gruposTrabajo) != 0) {
                                echo  Html::a('Modificar Grupos',
                                    Url::toRoute(['/implementacion/modificar-grupos-trabajo',
                                        'idImplementacion'=>$implementacion->id_implementacion,
                                        'idSeccion'=>$idSeccionSeleccionada]),
                                    ['class'=>'btn btn-primary']);
                                echo "<br><br>";
                                echo  Html::a('Asignar Líderes y Soci@s C. Beneficiari@s',
                                    Url::toRoute(['/implementacion/asignar-lider',
                                        'idImplementacion'=>$implementacion->id_implementacion,
                                        'idSeccion'=>$idSeccionSeleccionada]),
                                    ['class'=>'btn btn-primary']);
                            } else {
                                if($alumnosInscritos != null){
                                    echo  Html::a('Crear Grupos',
                                        Url::toRoute(['/implementacion/crear-grupos-trabajo',
                                            'idImplementacion'=>$implementacion->id_implementacion,
                                            'idSeccion'=>$idSeccionSeleccionada]),
                                        ['class'=>'btn btn-success']);
                                }else{
                                    echo "Debe inscribir alumnos en esta sección";
                                }
                            }
                        }else{
                            echo "Ninguna sección seleccionada";
                        }
                        ?>
                        <br><br><br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
-->

<div class="row">
    <div class="col-md-12">
        <?php
        $form = ActiveForm::begin([
            //'action' => Url::to(['implementacion/paso-uno']),
        ]);
        if($tieneDatosCompletos){
            if($implementacion->estado == 0){
                echo Html::submitButton('Publicar Implementación (Dar de Alta - visible a los alumnos y docentes)', ['name'=>'estadoImplementacion', 'value'=>'1','class' =>'btn btn-block btn-success']);
            }else if($implementacion->estado == 1 || $implementacion->estado == 2){
                echo Html::submitButton('Modificar Implementación (Dar de Baja - no visible a los alumnos y docentes)', ['name'=>'estadoImplementacion', 'value'=>'0','class' =>'btn btn-block btn-primary']);
                echo Html::submitButton('Finalizar Implementación (Dar por Finalizada - no visible a los alumnos y docentes)', ['name'=>'estadoImplementacion', 'value'=>'2','class' =>'btn btn-block btn-danger']);
            }
        }
        ActiveForm::end();
        ?>
    </div>
</div>

<!--<div id="informacion-seccion" class="alert alert-info alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-info"></i> Información</h4>
    Para modificar los alumnos inscritos o grupos de trabajo, debe seleccionar una sección.
</div>-->