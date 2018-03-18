<?php
namespace console\controllers;
use yii\console\Controller;
/**
 * This command echoes the first argument that you have entered.
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message =
                                'hello world')
    {
        echo $message . "\n";
    }
}