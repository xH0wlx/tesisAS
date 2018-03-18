<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\contactoScb;

/**
 * ContactoScbSearch represents the model behind the search form about `backend\models\contactoScb`.
 */
class ContactoScbSearch extends contactoScb
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_contacto_scb', 'scb_id_scb'], 'integer'],
            [['rut_beneficiario', 'nombre_completo', 'email', 'direccion'], 'safe'],
            [['telefono_celular', 'telefono_fijo'], 'number'],
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
        $query = contactoScb::find();

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
            'id_contacto_scb' => $this->id_contacto_scb,
            'scb_id_scb' => $this->scb_id_scb,
            'telefono_celular' => $this->telefono_celular,
            'telefono_fijo' => $this->telefono_fijo,
        ]);

        $query->andFilterWhere(['like', 'rut_beneficiario', $this->rut_beneficiario])
            ->andFilterWhere(['like', 'nombre_completo', $this->nombre_completo])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'direccion', $this->direccion]);

        return $dataProvider;
    }
}
