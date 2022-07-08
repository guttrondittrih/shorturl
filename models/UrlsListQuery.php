<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UrlsList]].
 *
 * @see UrlsList
 */
class UrlsListQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return UrlsList[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UrlsList|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
