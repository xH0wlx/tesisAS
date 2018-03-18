<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\servicio;

/**
 * ServicioSearch represents the model behind the search form about `backend\models\servicio`.
 */
class ServicioSearch extends servicio
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_servicio', 'estado_ejecucion_id_estado', 'duracion', 'unidad_duracion_id_unidad_duracion', 'asignatura_cod_asignatura'], 'integer'],
            [['titulo', 'descripcion', 'perfil_scb', 'observacion', 'creado_en', 'modificado_en'], 'safe'],
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
        $query = servicio::find();

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
            'id_servicio' => $this->id_servicio,
            'estado_ejecucion_id_estado' => $this->estado_ejecucion_id_estado,
            'duracion' => $this->duracion,
            'unidad_duracion_id_unidad_duracion' => $this->unidad_duracion_id_unidad_duracion,
            'creado_en' => $this->creado_en,
            'modificado_en' => $this->modificado_en,
            'asignatura_cod_asignatura' => $this->asignatura_cod_asignatura,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'perfil_scb', $this->perfil_scb])
            ->andFilterWhere(['like', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
