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

class FuncionesPropias extends Component{
    public function asignarRol($nombreRol, $idUsuario){
        $nombre_rol = $nombreRol;
        $auth = \Yii::$app->authManager;
        $auth->revokeAll($idUsuario);
        $role = $auth->getRole($nombre_rol);
        if($auth->assign($role, $idUsuario)){
            return true; // do some otherthings
        }else{
            return false;
        }
    }
}