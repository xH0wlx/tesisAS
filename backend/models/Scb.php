<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "scb".
 *
 * @property integer $id_scb
 * @property integer $sci_id_sci
 * @property string $nombre_negocio
 * @property string $actividad_rubro_giro
 * @property double $numero_trabajadores
 * @property string $tiempo_en_la_actividad
 * @property string $productos_yo_servicios
 * @property string $descripcion_clientes
 * @property string $descripcion_proveedores
 * @property string $direccion_comercial
 * @property integer $contabilidad
 * @property integer $patente
 * @property string $sitio_web
 * @property string $red_social
 *
 * @property ContactoScb[] $contactoScbs
 * @property GrupoTrabajoHasScb[] $grupoTrabajoHasScbs
 * @property Sci $sciIdSci
 */
class Scb extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'scb';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sci_id_sci', 'nombre_negocio'], 'required'],
            [['sci_id_sci', 'contabilidad', 'patente'], 'integer'],
            [['numero_trabajadores'], 'number'],
            [['nombre_negocio', 'actividad_rubro_giro', 'direccion_comercial'], 'string', 'max' => 255],
            [['tiempo_en_la_actividad'], 'string', 'max' => 45],
            [['sitio_web'], 'url', 'defaultScheme' => 'https'],
            [['red_social'], 'url', 'defaultScheme' => 'https'],
            [['productos_yo_servicios', 'descripcion_clientes', 'descripcion_proveedores'], 'string', 'max' => 500],
            //[['red_social'], 'string', 'max' => 100],
            [['sci_id_sci'], 'exist', 'skipOnError' => true, 'targetClass' => Sci::className(), 'targetAttribute' => ['sci_id_sci' => 'id_sci']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_scb' => 'Id Scb',
            'sci_id_sci' => 'Sci Id Sci',
            'nombre_negocio' => 'Nombre del Negocio',
            'actividad_rubro_giro' => 'Actividad/Rubro/Giro',
            'numero_trabajadores' => 'Número de Trabajadores',
            'tiempo_en_la_actividad' => 'Tiempo en la Actividad',
            'productos_yo_servicios' => 'Productos y/o Servicios',
            'descripcion_clientes' => 'Descripción de Clientes',
            'descripcion_proveedores' => 'Descripción de Proveedores',
            'direccion_comercial' => 'Dirección Comercial',
            'contabilidad' => 'Contabilidad',
            'patente' => 'Patente',
            'sitio_web' => 'Sitio Web',
            'red_social' => 'Red Social',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactoScbs()
    {
        return $this->hasMany(ContactoScb::className(), ['scb_id_scb' => 'id_scb']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupoTrabajoHasScbs()
    {
        return $this->hasMany(GrupoTrabajoHasScb::className(), ['scb_id_scb' => 'id_scb']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSciIdSci()
    {
        return $this->hasOne(Sci::className(), ['id_sci' => 'sci_id_sci']);
    }

    public function getSciLista(){
        $droptions = Sci::find()->all();
        return ArrayHelper::map($droptions, 'id_sci', 'nombreComuna');
    }
}
