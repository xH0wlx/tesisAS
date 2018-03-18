<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ContactoSci;

/**
 * ContactoSciSearch represents the model behind the search form about `backend\models\ContactoSci`.
 */
class ContactoSciSearch extends ContactoSci
{
    public $contacto_socio_institucional;
    public $contacto_comuna;
    public $contacto_sede;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_contacto_sci', 'sci_id_sci'], 'integer'],
            [['nombres', 'apellidos', 'telefono', 'cargo', 'email', 'observacion', 'creado_en', 'modificado_en',
                'contacto_socio_institucional', 'contacto_comuna', 'contacto_sede'], 'safe'],
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
        $query = ContactoSci::find();

        $query->joinWith('sciIdSci.comunaComuna');
        $query->joinWith('sciIdSci.sedeIdSede');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['contacto_socio_institucional'] = [
            'asc' => ['nombre' => SORT_ASC],
            'desc' => ['nombre' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['contacto_comuna'] = [
            'asc' => ['comuna_nombre' => SORT_ASC],
            'desc' => ['comuna_nombre' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['contacto_sede'] = [
            'asc' => ['nombre_sede' => SORT_ASC],
            'desc' => ['nombre_sede' => SORT_DESC],
        ];

        //$dataProvider->sort->defaultOrder = [ 'contacto_comuna' => 'desc'];  //???????

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_contacto_sci' => $this->id_contacto_sci,
            'telefono' => $this->telefono,
            'sci_id_sci' => $this->sci_id_sci,
            'creado_en' => $this->creado_en,
            'modificado_en' => $this->modificado_en,
        ]);

        $query->andFilterWhere(['like', 'nombres', $this->nombres])
            ->andFilterWhere(['like', 'apellidos', $this->apellidos])
            //->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'cargo', $this->cargo])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'observacion', $this->observacion])
            ->andFilterWhere(['like', 'nombre', $this->contacto_socio_institucional])
            ->andFilterWhere(['like', 'comuna_nombre', $this->contacto_comuna])
            ->andFilterWhere(['like', 'nombre_sede', $this->contacto_sede]);

        return $dataProvider;
    }
}
