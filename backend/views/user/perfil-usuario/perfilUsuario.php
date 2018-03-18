<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Perfil de Usuario';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="user-index">
    <div class="panel panel-primary">
        <div class="panel-heading"><i class="glyphicon glyphicon-pencil"></i> Datos de usuario</div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'username')->textInput(['readonly'=> true, 'data-rut' => 'true','maxlength' => true])->label("RUT (Username)") ?>
            <?= $form->field($model, 'nombre_completo')->textInput(['readonly'=> true,'maxlength' => true]) ?>
            <?= $form->field($model, 'email')->textInput(['readonly'=> true,'maxlength' => true]) ?>

            <?php // $form->field($model, 'password_hash')->hiddenInput(['maxlength' => true])->label(false) ?>

            <?= $form->field($model, 'password_antigua')->passwordInput(['maxlength' => true])->label("Contraseña Antigua") ?>
            <?= $form->field($model, 'password_nueva')->passwordInput(['maxlength' => true])->label("Nueva Contraseña") ?>
            <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true])->label("Repetir nueva Contraseña") ?>

            <?= $form->field($model, 'tipo_usuario_id')->hiddenInput()->label(false);?>



            <?php if (!Yii::$app->request->isAjax){ ?>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn-lg btn btn-primary']) ?>
                </div>
            <?php } ?>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>
