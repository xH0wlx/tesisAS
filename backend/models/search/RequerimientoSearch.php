<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\requerimiento;

/**
 * RequerimientoSearch represents the model behind the search form about `backend\models\requerimiento`.
 */
class RequerimientoSearch extends requerimiento
{
    public $requerimiento_socio_institucional;
    public $comuna_socio_institucional;
    public $estado_requerimiento;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_requerimiento', 'sci_id_sci', 'estado_ejecucion_id_estado'], 'integer'],
            [['titulo', 'descripcion', 'perfil_estudiante', 'apoyo_brindado', 'observacion', 'creado_en',
                'modificado_en', 'requerimiento_socio_institucional', 'comuna_socio_institucional', 'estado_requerimiento'], 'safe'],
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
        $query = requerimiento::find();

        $query->joinWith('sciIdSci.comunaComuna');
        $query->joinWith('estadoEjecucionIdEstado');

        $query->orderBy(['nombre' => SORT_ASC, 'comuna_nombre' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['requerimiento_socio_institucional'] = [
            'asc' => ['nombre' => SORT_ASC],
            'desc' => ['nombre' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['comuna_socio_institucional'] = [
            'asc' => ['comuna_nombre' => SORT_ASC],
            'desc' => ['comuna_nombre' => SORT_DESC],
        ];

        //REVISAR (UNO HACE QUE EL EXPAND ROW DEJE DE FUNCIONAR
        $this->load($params);
       /* if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }*/

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_requerimiento' => $this->id_requerimiento,
            'sci_id_sci' => $this->sci_id_sci,
            //'estado_ejecucion_id_estado' => $this->estado_ejecucion_id_estado,
            'creado_en' => $this->creado_en,
            'modificado_en' => $this->modificado_en,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'perfil_estudiante', $this->perfil_estudiante])
            ->andFilterWhere(['like', 'apoyo_brindado', $this->apoyo_brindado])
            ->andFilterWhere(['like', 'observacion', $this->observacion])
            ->andFilterWhere(['like', 'nombre', $this->requerimiento_socio_institucional])
            ->andFilterWhere(['like', 'comuna_nombre', $this->comuna_socio_institucional])
            ->andFilterWhere(['like', 'nombre_estado', $this->estado_requerimiento]);



        return $dataProvider;
    }

    public function searchNoEliminados($params)
    {
        $query = requerimiento::find();

        //SOLO LOS QUE NO ESTAN ELIMINADOS
        $query->where(['isDeleted' => false]);

        $query->joinWith('sciIdSci.comunaComuna');
        $query->joinWith('estadoEjecucionIdEstado');

        $query->orderBy(['nombre' => SORT_ASC, 'comuna_nombre' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['requerimiento_socio_institucional'] = [
            'asc' => ['nombre' => SORT_ASC],
            'desc' => ['nombre' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['comuna_socio_institucional'] = [
            'asc' => ['comuna_nombre' => SORT_ASC],
            'desc' => ['comuna_nombre' => SORT_DESC],
        ];


        //REVISAR (UNO HACE QUE EL EXPAND ROW DEJE DE FUNCIONAR
        $this->load($params);
        /* if (!($this->load($params) && $this->validate())) {
             return $dataProvider;
         }*/

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_requerimiento' => $this->id_requerimiento,
            'sci_id_sci' => $this->sci_id_sci,
            //'estado_ejecucion_id_estado' => $this->estado_ejecucion_id_estado,
            'creado_en' => $this->creado_en,
            'modificado_en' => $this->modificado_en,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'perfil_estudiante', $this->perfil_estudiante])
            ->andFilterWhere(['like', 'apoyo_brindado', $this->apoyo_brindado])
            ->andFilterWhere(['like', 'observacion', $this->observacion])
            ->andFilterWhere(['like', 'nombre', $this->requerimiento_socio_institucional])
            ->andFilterWhere(['like', 'comuna_nombre', $this->comuna_socio_institucional])
            ->andFilterWhere(['like', 'nombre_estado', $this->estado_requerimiento]);




        return $dataProvider;
    }

    public function searchEliminados($params)
    {
        $query = requerimiento::find();

        //SOLO LOS QUE NO ESTAN ELIMINADOS
        $query->where(['isDeleted' => true]);

        $query->joinWith('sciIdSci.comunaComuna');
        $query->joinWith('estadoEjecucionIdEstado');

        $query->orderBy(['nombre' => SORT_ASC, 'comuna_nombre' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['requerimiento_socio_institucional'] = [
            'asc' => ['nombre' => SORT_ASC],
            'desc' => ['nombre' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['comuna_socio_institucional'] = [
            'asc' => ['comuna_nombre' => SORT_ASC],
            'desc' => ['comuna_nombre' => SORT_DESC],
        ];

        //REVISAR (UNO HACE QUE EL EXPAND ROW DEJE DE FUNCIONAR
        $this->load($params);
        /* if (!($this->load($params) && $this->validate())) {
             return $dataProvider;
         }*/

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_requerimiento' => $this->id_requerimiento,
            'sci_id_sci' => $this->sci_id_sci,
            //'estado_ejecucion_id_estado' => $this->estado_ejecucion_id_estado,
            'creado_en' => $this->creado_en,
            'modificado_en' => $this->modificado_en,
        ]);

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'perfil_estudiante', $this->perfil_estudiante])
            ->andFilterWhere(['like', 'apoyo_brindado', $this->apoyo_brindado])
            ->andFilterWhere(['like', 'observacion', $this->observacion])
            ->andFilterWhere(['like', 'nombre', $this->requerimiento_socio_institucional])
            ->andFilterWhere(['like', 'comuna_nombre', $this->comuna_socio_institucional])
            ->andFilterWhere(['like', 'nombre_estado', $this->estado_requerimiento]);


        return $dataProvider;
    }
}
