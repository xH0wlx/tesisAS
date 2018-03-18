<?php

namespace backend\controllers;

use Yii;
use backend\models\carrera;
use backend\models\asignatura;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;


use yii\base\Exception;
use yii\db\IntegrityException;
use yii\web\NotFoundHttpException;

/**
 * CarreraController implements the CRUD actions for carrera model.
 */
class ExcelController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['coordinador general'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all carrera models.
     * @return mixed
     */
    public function actionIndex()
    {
        //$searchModel = new CarreraSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('/site/index', [
            //'searchModel' => $searchModel,
            //'dataProvider' => $dataProvider,
        ]);
    }

    public function actionImport()
    {
        $inputFile = "uploads/carreraExcel/Asignaturas Carreras (2).xlsx";

        try{
            $inputFileType = \PHPExcel_IOFactory::identify($inputFile);
            $objectReader = \PHPExcel_IOFactory::createReader($inputFileType);
            $objectPhpExcel = $objectReader->load($inputFile);
        }catch (Exception $e){
            die("Error");
        }

        $sheet = $objectPhpExcel->getSheet(2);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        for ($row = 1; $row <= $highestRow; $row++){
            $rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row, NULL, TRUE, FALSE);

            if($row == 1){
                continue;
            }

            $carrera = new Carrera();
            $carrera_codigo = $rowData[0][2];
            $nombre_carrera = $rowData[0][1];
            $plan_carrera = $rowData[0][3];
            $sede_carrera = $rowData[0][0];

            //ESTO DEBERÍA DAR ERROR EN 0
            $id_facultad = 0;
            if(strcmp($sede_carrera, "Chillán") == 0){
                $id_facultad = 1;
            }else{
                if(strcmp($sede_carrera, "Concepción") == 0){
                    $id_facultad = 2;
                }
            }

            $carrera->cod_carrera = $carrera_codigo;
            $carrera->nombre_carrera = $nombre_carrera;
            $carrera->plan_carrera = $plan_carrera;
            $carrera->facultad_id_facultad = $id_facultad;

            //$connection = \Yii::$app->db;
            //$transaction = $connection->beginTransaction();

            try {
                if ($flag = $carrera->save()) {
                    //$transaction->commit();
                    echo "exito</br>";
                }
            } catch (Exception $e) {
                //$transaction->rollBack();
                echo "fracaso</br>";
            }


           /* try {
                $carrera->save();
                $transaction->commit();
            }catch (IntegrityException $e) {
                $transaction->rollBack();
                throw new \yii\web\HttpException(500,"YOUR MESSAGE.", 405);
            }catch (Exception $e) {
                $transaction->rollBack();
                throw new \yii\web\HttpException(500,"YOUR MESSAGE", 405);
            }*/
            print_r($carrera->getErrors());
        }
        die("okay");

        //return $this->render('/site/index', [//'searchModel' => $searchModel, //'dataProvider' => $dataProvider,   ]);
    }

    public function actionImportAsignatura()
    {
        $inputFile = "uploads/carreraExcel/Asignaturas Carreras (2).xlsx";

        try{
            $inputFileType = \PHPExcel_IOFactory::identify($inputFile);
            $objectReader = \PHPExcel_IOFactory::createReader($inputFileType);
            $objectPhpExcel = $objectReader->load($inputFile);
        }catch (Exception $e){
            die("Error");
        }

        $sheet = $objectPhpExcel->getSheet(2);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        for ($row = 1; $row <= $highestRow; $row++){
            $rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row, NULL, TRUE, FALSE);

            if($row == 1){
                continue;
            }

            $asignatura = new Asignatura();
            $carrera_codigo = $rowData[0][2];
            $semestre_malla = $rowData[0][4];
            $semestre_dicta = $rowData[0][5];
            $codigo_asignatura = $rowData[0][7];
            $nombre_asignatura = $rowData[0][8];
            $resultado_aprendizaje = $rowData[0][9];

/*            var_dump("Codido Carrera: ".$carrera_codigo."\n");
            var_dump("Semestre Malla: ".$semestre_malla."\n");
            var_dump("Semestre dicta: ".$semestre_dicta."\n");
            var_dump("codigo asignatura: ".$codigo_asignatura."\n");
            var_dump("nomnre asignatura: ".$nombre_asignatura."\n");
            var_dump("resultado_aprendizaj: ".$resultado_aprendizaje."\n");*/


            $asignatura->cod_asignatura = $codigo_asignatura;
            $asignatura->nombre_asignatura = $nombre_asignatura;
            $asignatura->semestre_dicta = $semestre_dicta;
            $asignatura->semestre_malla = $semestre_malla;
            $asignatura->resultado_aprendizaje = $resultado_aprendizaje;
            $asignatura->carrera_cod_carrera = $carrera_codigo;

            try {
                if ($asignatura->save()) {
                    echo "exito</br>";
                }
            } catch (Exception $e) {
                echo "fracaso</br>";
            }

            print_r($asignatura->getErrors());
        }
        die("okay");

        //return $this->render('/site/index', [//'searchModel' => $searchModel, //'dataProvider' => $dataProvider,   ]);
    }


}