<?php

namespace u4impact\humhub\modules\proposal\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for Proposal.
 * @see Proposal
 */
class ProposalQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return Proposal[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Proposal|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function proposalPublish(): ProposalQuery
    {
        return $this->andWhere('[[status]]=3');
    }

    public function proposalOrganization($organization_name): ProposalQuery
    {
        return $this->andWhere('[[organization]]="'.$organization_name.'"');
    }

}
