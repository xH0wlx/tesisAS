<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\user */
?>
<div class="user-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'email:email',
            'estado_id',
            'rolAsignado.item_name',
        ],
    ]) ?>

</div>
