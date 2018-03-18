<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\user;

/**
 * UserSearch represents the model behind the search form about `backend\models\user`.
 */
class UserSearch extends user
{
    public $tipo_usuario_nombre;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'estado_id', /*'rol_id',*/], 'integer'],
            [['username','nombre_completo', 'auth_key', 'password_hash',
                'password_reset_token', 'email', 'created_at', 'updated_at', 'verification_code', 'tipo_usuario_nombre'], 'safe'],
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
        $query = user::find();
        $query->joinWith('rolAsignado');


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['tipo_usuario_nombre'] = [
            'asc' => ['item_name' => SORT_ASC],
            'desc' => ['item_name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'estado_id' => $this->estado_id,
            //'rol_id' => $this->rol_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'nombre_completo', $this->nombre_completo])
            ->andFilterWhere(['like', 'verification_code', $this->verification_code])
            ->andFilterWhere(['like', 'item_name', $this->tipo_usuario_nombre]);


        return $dataProvider;
    }
}
