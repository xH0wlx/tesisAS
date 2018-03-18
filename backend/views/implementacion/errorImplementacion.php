<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ImplementacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Error';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
    <div class="panel panel-primary">
    <div class="panel-heading">Selección de Periodo Implementación</div>
        <div class="panel-body">
            <h1><?=$msg?></h1>
    </div>