<?php

namespace u4impact\humhub\modules\proposal\models;

/**
 * This is the ActiveQuery class for [[Organization]].
 *
 * @see Organization
 */
class OrganizationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Organization[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Organization|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
