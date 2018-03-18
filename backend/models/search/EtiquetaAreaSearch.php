<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\etiquetaArea;

/**
 * EtiquetaAreaSearch represents the model behind the search form about `backend\models\etiquetaArea`.
 */
class EtiquetaAreaSearch extends etiquetaArea
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_etiqueta_area', 'frecuencia'], 'integer'],
            [['nombre_etiqueta_area', 'descripcion_etiqueta'], 'safe'],
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
        $query = etiquetaArea::find();

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
            'id_etiqueta_area' => $this->id_etiqueta_area,
            'frecuencia' => $this->frecuencia,
        ]);

        $query->andFilterWhere(['like', 'nombre_etiqueta_area', $this->nombre_etiqueta_area])
            ->andFilterWhere(['like', 'descripcion_etiqueta', $this->descripcion_etiqueta]);

        return $dataProvider;
    }
}
