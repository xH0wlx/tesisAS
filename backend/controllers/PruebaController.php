<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\rbac\DbManager;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/*$r=new DbManager;
$r->init();
$test = $r->createRole('test');
$r->add($test);

$r->assign($test, 3);*/



class PruebaController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['coordinador general'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    

    public function actionIndex()
    {
        return $this->render('index');
    }

}
