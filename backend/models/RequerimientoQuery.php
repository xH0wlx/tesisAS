<?php

namespace backend\models;
use creocoder\taggable\TaggableQueryBehavior;
/**
 * This is the ActiveQuery class for [[Requerimiento]].
 *
 * @see Requerimiento
 */
class RequerimientoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function behaviors()
    {
        return [
            TaggableQueryBehavior::className(),
        ];
    }
/*
    /**
     * @inheritdoc
     * @return Requerimiento[]|array

    public function all($db = null)
    {
        return parent::all($db);
    }*/

    /*/**
     * @inheritdoc
     * @return Requerimiento|array|null
     */
   /* public function one($db = null)
    {
        return parent::one($db);
    }*/
}
