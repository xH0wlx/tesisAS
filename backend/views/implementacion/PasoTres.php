<?php
/**
 * Created by PhpStorm.
 * User: Howl
 * Date: 06-06-2017
 * Time: 17:02
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;


$js = '
$(\'#boton-modal-alumnos-seccion\').click(function(){
        $(\'#header-modal-alumnos-seccion\').text($(this).attr(\'title\'));
        $(\'#modal-alumnos-seccion\').modal(\'show\')
        .find(\'#contenido-modal-alumnos-seccion\')
        .load(\'#grid-aceptar\');
        //$(this).attr(\'value\')
});
';

$this->registerJs($js);


$this->title = "CARGAR DATOS DE ALUMNOS PARA SECCION";
if(isset($datosPost)){
    //var_dump($datosPost);
}
?>

<?php
Modal::begin([
    'header' => '<h4 id="header-modal-alumnos-seccion"></h4>',
    'id' => 'modal-alumnos-seccion',
    'size' => 'modal-lg',
]);
echo '<div id="contenido-modal-alumnos-seccion"></div>';
Modal::end();
?>
<?= Html::Button('Modal', ['id' => 'boton-modal-alumnos-seccion', 'name'=>'guardarYSalir', 'value'=>'index','class' =>'btn btn-primary']) ?>
<div class="panel panel-primary">
    <div class="panel-heading">Datos de Implementación</div>
    <div class="panel-body">
        <div class="seccion-form">
            <?php $form = ActiveForm::begin([
                    'options'=>['enctype'=>'multipart/form-data']
            ]);?>
            <?= $form->field($model, 'archivoExcel')->widget(FileInput::classname(), [
                //'options' => ['accept' => 'image/*'],
                'pluginOptions'=>['allowedFileExtensions'=>['xls','xlsx'],'showUpload' => false,],
            ]);   ?>

            <?= Html::submitButton('Guardar y Salir', ['name'=>'guardarYSalir', 'value'=>'index','class' =>'btn btn-primary']) ?>
            <?= Html::submitButton('Procesar Excel', ['name'=>'procesar','value'=> 'true','class' => 'btn btn-primary']) ?>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

<div id="grid-aceptar">
<?php
    if(isset($resultado) && !is_null($resultado)){
        $form = ActiveForm::begin([]);
        echo Html::submitButton('Aceptar Datos', ['name'=>'aceptarDatos', 'value'=>'true','class' =>'btn btn-primary']);
        ActiveForm::end();
        Pjax::begin();

        echo GridView::widget([
            'dataProvider' => $resultado,

            'columns' => [
                [
                    'attribute' => 'rut',
                    'value' => 'rut',
                ],
                [
                    'attribute' => 'nombre',
                    'value' => 'nombre',
                ],
                [
                    "attribute" => "mail",
                    'value' => 'mail',
                ]

            ]
        ]);
        Pjax::end();
    }
?>
</div>
