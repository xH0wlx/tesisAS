<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\requerimientoHasEtiquetaArea;

/**
 * RequerimientoHasEtiquetaAreaSearch represents the model behind the search form about `backend\models\requerimientoHasEtiquetaArea`.
 */
class RequerimientoHasEtiquetaAreaSearch extends requerimientoHasEtiquetaArea
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['requerimiento_id_requerimiento', 'etiqueta_area_id_etiqueta_area'], 'integer'],
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
        $query = requerimientoHasEtiquetaArea::find();

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
            'requerimiento_id_requerimiento' => $this->requerimiento_id_requerimiento,
            'etiqueta_area_id_etiqueta_area' => $this->etiqueta_area_id_etiqueta_area,
        ]);

        return $dataProvider;
    }
}
