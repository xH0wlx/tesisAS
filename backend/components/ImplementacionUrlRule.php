<?php
/**
 * Created by PhpStorm.
 * User: Howl
 * Date: 30-08-2017
 * Time: 17:00
 */

namespace backend\components;

use yii\web\UrlRuleInterface;
use yii\base\Object;

class ImplementacionUrlRule extends Object implements UrlRuleInterface
{
    //ESTA FUNCIÓN SE UTILIZA PARA EL URL::TO
    public function createUrl($manager, $route, $params)
    {
        // TODO: Implement createUrl() method.
        if($route === 'bitacora/index'){
            return 'donpepe/y-sus-globos';
        }

        return false;
    }

    public function parseRequest($manager, $request)
    {
        //ESTA FUNCIÓN RECIBIRÄ LA URL Y REDIGIRÁ
        $pathInfo = $request->getPathInfo();
        if ($pathInfo === 'donpepe/y-sus-globos') {
            // check $matches[1] and $matches[3] to see
            // if they match a manufacturer and a model in the database.
            // If so, set $params['manufacturer'] and/or $params['model']
            // and return ['car/index', $params]
            $params = [];
            $params['prueba'] = 'gorila';
            return ['bitacora/index', $params];
        }
        return false;
    }

}