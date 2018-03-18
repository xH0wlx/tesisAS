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

$this->title = 'Selección de Sección Asignatura';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="seleccion-asignatura-index">
    <div class="box box-primary">
        <div class="box-body">
            <?php if(count($modelos2) != 0){?>

           <?php $form = ActiveForm::begin(); ?>
            <?php
                $hasta = count($modelos2);
                for ($i = 0; $i< $hasta; $i++){
            ?>
                    <div class="radio">
                        <label><input type="radio" name="id_seccion" value=<?=$modelos2[$i]["id_seccion"]?>>
                            <?= $modelos2[$i]["cod_asignatura"] ?>-<?= $modelos2[$i]["nombre_asignatura"] ?> /
                         Sección N°<?=$modelos2[$i]["numero_seccion"] ?>
                        </label>
                    </div>
            <?php
                }
            ?>

            <div class="form-group">
                <?= Html::submitButton('Siguiente', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php
            ActiveForm::end();
            }//FIN IF
            else{
                echo "<h1>Actualmente no está participando de implementaciones activas.</h1>";
            }
            ?>
        </div>
    </div>
</div>
