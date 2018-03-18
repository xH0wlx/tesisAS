<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\scb;

/**
 * ScbSearch represents the model behind the search form about `backend\models\scb`.
 */
class ScbSearch extends scb
{
    public $scb_nombre_socio;
    public $scb_comuna_sci;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_scb', 'sci_id_sci', 'contabilidad', 'patente'], 'integer'],
            [['nombre_negocio', 'actividad_rubro_giro', 'tiempo_en_la_actividad', 'productos_yo_servicios',
                'descripcion_clientes', 'descripcion_proveedores', 'direccion_comercial', 'sitio_web',
                'red_social', 'scb_nombre_socio', 'scb_comuna_sci'], 'safe'],
            [['numero_trabajadores'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = scb::find();
        $query->joinWith('sciIdSci.comunaComuna');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_scb' => $this->id_scb,
            'sci_id_sci' => $this->sci_id_sci,
            'numero_trabajadores' => $this->numero_trabajadores,
            'contabilidad' => $this->contabilidad,
            'patente' => $this->patente,
        ]);

        $query->andFilterWhere(['like', 'nombre_negocio', $this->nombre_negocio])
            ->andFilterWhere(['like', 'actividad_rubro_giro', $this->actividad_rubro_giro])
            ->andFilterWhere(['like', 'tiempo_en_la_actividad', $this->tiempo_en_la_actividad])
            ->andFilterWhere(['like', 'productos_yo_servicios', $this->productos_yo_servicios])
            ->andFilterWhere(['like', 'descripcion_clientes', $this->descripcion_clientes])
            ->andFilterWhere(['like', 'descripcion_proveedores', $this->descripcion_proveedores])
            ->andFilterWhere(['like', 'direccion_comercial', $this->direccion_comercial])
            ->andFilterWhere(['like', 'sitio_web', $this->sitio_web])
            ->andFilterWhere(['like', 'red_social', $this->red_social])
            ->andFilterWhere(['like', 'sci.nombre', $this->scb_nombre_socio])
            ->andFilterWhere(['like', 'comuna_nombre', $this->scb_comuna_sci]);

        return $dataProvider;
    }
}
