<?php

namespace backend\models;

use Yii;

//PARA USAR LOS TAGS
//use dosamigos\taggable\Taggable;
use creocoder\taggable\TaggableBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use backend\models\RequerimientoQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "requerimiento".
 *
 * @property integer $id_requerimiento
 * @property string $titulo
 * @property string $descripcion
 * @property string $perfil_estudiante
 * @property string $apoyo_brindado
 * @property string $observacion
 * @property string $preseleccionado_match1
 * @property integer $sci_id_sci
 * @property integer $estado_ejecucion_id_estado
 * @property string $creado_en
 * @property string $modificado_en
 *
 * @property Match1[] $match1s
 * @property Asignatura[] $asignaturaCodAsignaturas
 * @property EstadoEjecucion $estadoEjecucionIdEstado
 * @property Sci $sciIdSci
 * @property RequerimientoHasEtiquetaArea[] $requerimientoHasEtiquetaAreas
 * @property EtiquetaArea[] $etiquetaAreaIdEtiquetaAreas
 * @property Tags[] $tagValues
 *
 */
class Requerimiento extends \yii\db\ActiveRecord
{
    const SCENARIO_REQUERIMIENTO = 'requerimiento';

    public function behaviors() {
        return [
            [
                'class' => TaggableBehavior::className(),
                'tagValueAttribute' => 'nombre_etiqueta_area',
                'tagFrequencyAttribute' => 'frecuencia',
                //'class' => Taggable::className(), de la extension 2 amigos
                /*'taggable' => [
                    'class' => TaggableBehavior::className(),
                    // 'tagValuesAsArray' => false,
                     //'tagRelation' => 'tags',
                    'tagValueAttribute' => 'nombre_etiqueta_area',
                    'tagFrequencyAttribute' => 'frecuencia',
                ],*/
            ],
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'isDeleted' => true
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'requerimiento';
    }



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sci_id_sci'], 'required', 'on' => self::SCENARIO_REQUERIMIENTO],
            ['tagValues', 'safe'],
            //[['tagNames'], 'safe'], de la extensión 2 amigos
            [['titulo', 'descripcion', 'tagValues'], 'required'],
            [['sci_id_sci', 'estado_ejecucion_id_estado', 'cantidad_aprox_beneficiarios'], 'integer'],
            [['creado_en', 'modificado_en', 'preseleccionado_match1'], 'safe'],
            [['titulo'], 'string', 'max' => 255],
            [['descripcion', 'perfil_estudiante', 'apoyo_brindado', 'observacion'], 'string', 'max' => 500],
            [['estado_ejecucion_id_estado'], 'exist', 'skipOnError' => true, 'targetClass' => EstadoEjecucion::className(), 'targetAttribute' => ['estado_ejecucion_id_estado' => 'id_estado']],
            [['sci_id_sci'], 'exist', 'skipOnError' => true, 'targetClass' => Sci::className(), 'targetAttribute' => ['sci_id_sci' => 'id_sci']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_requerimiento' => 'Id',
            'titulo' => 'Título',
            'descripcion' => 'Descripción',
            'perfil_estudiante' => 'Perfil Socio Beneficiario',
            'apoyo_brindado' => 'Apoyo Brindado',
            'observacion' => 'Observación',
            'preseleccionado_match1' => 'Tiene Asignaturas Asociadas',
            'cantidad_aprox_beneficiarios' => "Cantidad Aproximada de Socios Beneficiarios",
            'sci_id_sci' => 'Socio Comunitario Institucional',
            'estado_ejecucion_id_estado' => 'Estado de Ejecución',
            'creado_en' => 'Creado En',
            'modificado_en' => 'Modificado En',
            'tagValues' => 'Palabras Clave',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatch1s()
    {
        return $this->hasMany(Match1::className(), ['requerimiento_id_requerimiento' => 'id_requerimiento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsignaturaCodAsignaturas()
    {
        return $this->hasMany(Asignatura::className(), ['cod_asignatura' => 'asignatura_cod_asignatura'])->viaTable('match1', ['requerimiento_id_requerimiento' => 'id_requerimiento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstadoEjecucionIdEstado()
    {
        return $this->hasOne(EstadoEjecucion::className(), ['id_estado' => 'estado_ejecucion_id_estado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSciIdSci()
    {
        return $this->hasOne(Sci::className(), ['id_sci' => 'sci_id_sci']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequerimientoHasEtiquetaAreas()
    {
        return $this->hasMany(RequerimientoHasEtiquetaArea::className(), ['requerimiento_id_requerimiento' => 'id_requerimiento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEtiquetaAreaIdEtiquetaAreas()
    {
        return $this->hasMany(EtiquetaArea::className(), ['id_etiqueta_area' => 'etiqueta_area_id_etiqueta_area'])->viaTable('requerimiento_has_etiqueta_area', ['requerimiento_id_requerimiento' => 'id_requerimiento']);
    }

    //EXTENSION 2AMIGOS
    /*
    public function getTags()
    {
        return $this->hasMany(EtiquetaArea::className(), ['id_etiqueta_area' => 'etiqueta_area_id_etiqueta_area'])->viaTable('requerimiento_has_etiqueta_area', ['requerimiento_id_requerimiento' => 'id_requerimiento']);
    }

    public function suggestions($tags, $limit = 3)
    {
        return Requerimiento::find()
            ->active()
            ->innerJoin('tbl_tour_tag_assn', 'tbl_tour.id = tbl_tour_tag_assn.tour_id')
            ->innerJoin('tbl_tag', 'tbl_tour_tag_assn.tag_id = tbl_tag.id')
            ->innerJoin('tbl_tag_lang', 'tbl_tag.id = tbl_tag_lang.tag_id')
            ->where(['in', 'tbl_tag_lang.name', explode(',', $tags)])
            ->andWhere('tbl_tour.id <> :id', [':id' => $this->id])
            ->limit($limit)
            ->all();
    }*/

    public function getSocioILista(){
        $droptions = Sci::find()->all();
        return ArrayHelper::map($droptions, 'id_sci', 'nombreComuna');
    }

    public function getSocioIListaOriginal(){
        $droptions = Sci::find()->asArray()->all();
        return ArrayHelper::map($droptions, 'id_sci', 'nombre');
    }
    //EXTENSION CREOCODER
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new RequerimientoQuery(get_called_class());
    }

    public function getTags()
    {
        return $this->hasMany(EtiquetaArea::className(), ['id_etiqueta_area' => 'etiqueta_area_id_etiqueta_area'])->viaTable('requerimiento_has_etiqueta_area', ['requerimiento_id_requerimiento' => 'id_requerimiento']);
    }

}
