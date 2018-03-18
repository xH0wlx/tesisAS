<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\oferta;

/**
 * OfertaSearch represents the model behind the search form about `backend\models\oferta`.
 */
class OfertaSearch extends oferta
{
    public $asignatura_nombre;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_oferta', 'asignatura_cod_asignatura', 'periodo_id_periodo'], 'integer'],
            [['creado_en', 'modificado_en', 'asignatura_nombre'], 'safe'],
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
        $query = oferta::find();

        $query->joinWith('asignaturaCodAsignatura');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['asignatura_nombre'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['nombre_asignatura' => SORT_ASC],
            'desc' => ['nombre_asignatura' => SORT_DESC],
        ];

        //$this->load($params);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_oferta' => $this->id_oferta,
            'asignatura_cod_asignatura' => $this->asignatura_cod_asignatura,
            'periodo_id_periodo' => $this->periodo_id_periodo,
            'creado_en' => $this->creado_en,
            'modificado_en' => $this->modificado_en,
        ]);

        $query->andFilterWhere(['like', 'nombre_asignatura', $this->asignatura_nombre]);
        //$query->andFilterWhere(['like', 'periodo_id_periodo', $this->asignatura_nombre]);

        return $dataProvider;
    }
}
