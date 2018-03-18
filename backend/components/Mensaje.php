<?php
/**
 * Created by PhpStorm.
 * User: Howl
 * Date: 15-07-2017
 * Time: 3:02
 */
namespace backend\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class Mensaje extends Component{

    public function mensajeGrowl($type, $message, $duration = 5000){
        $title = "";
        switch($type){
            case "success":
                $title = "Éxito";
                break;
            case "error":
                $title = "Error";
                break;
            default:
                $title = "Información";
        }


        Yii::$app->getSession()->setFlash('success', [
            'type' => $type,
            'duration' => $duration,
            //'icon' => 'fa fa-users',
            'message' => $message,
            'title' => $title,
            'positonY' => 'top',
            //'positonX' => 'left'
        ]);
    }
}