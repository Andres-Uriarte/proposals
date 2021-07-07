<?php

use humhub\modules\space\models\Space;
use u4impact\humhub\modules\proposal\models\Proposal;
use yii\base\Exception;
use yii\db\Migration;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

class uninstall extends Migration
{

    public function up()
    {
        $this->deleteSpaceAssociatedWithProposals();

        $this->dropTable('proposal_proposal');
        $this->dropTable('proposal_organization');
    }

    public function down()
    {
        echo "uninstall does not support migration down.\n";
        return false;
    }

    protected function deleteSpaceAssociatedWithProposals()
    {
        $proposals = Proposal::find()->all();

        foreach ($proposals as $proposal) {

            if (($space = Space::findOne($proposal->space_id)) !== null) {
                if ($space != null) $space->delete();
            }
        }

    }

    /**
     * @param Proposal $proposal
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws \Throwable
     */
    protected function deleteSpace(Proposal $proposal): void
    {
        $space = $this->findSpace($proposal->space_id);
        if ($space != null) {
            $space->delete();
        }
    }

}
