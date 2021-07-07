<?php

namespace  u4impact\humhub\modules\proposal;

use humhub\modules\space\models\Membership;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\Group;
use humhub\modules\user\models\User;
use u4impact\humhub\modules\proposal\models\Proposal;
use Yii;
use yii\helpers\Url;

class Events
{
    /**
     * Defines what to do when the top menu is initialized.
     * @param $event
     */
    public static function onTopMenuInit($event)
    {
        $event->sender->addItem([
            'label' => 'Propuestas',
            'icon' => '<i class="fa fa-file"></i>',
            'url' => Url::to(['/proposal/proposal']),
            'sortOrder' => 99999,
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'proposal' && Yii::$app->controller->id == 'proposal'),
        ]);
    }

    /**
     * Defines what to do if organizations admin menu is initialized.
     * @param $event
     */
    public static function onAdminMenuInit($event)
    {
        $event->sender->addItem([
            'label' => 'Organizaciones',
            'url' => Url::to(['/proposal/admin/organizations']),
            'group' => 'manage',
            'icon' => '<i class="fa fa-users"></i>',
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'proposal' && Yii::$app->controller->id == 'admin/organizations'),
            'sortOrder' => 201,
        ]);
    }

    /**
     * Defines what to do if proposals admin menu is initialized.
     * @param $event
     */
    public static function onAdminMenuInit2($event)
    {
        $event->sender->addItem([
            'label' => 'Propuestas',
            'url' => Url::to(['/proposal/admin/proposals']),
            'group' => 'manage',
            'icon' => '<i class="fa fa-file"></i>',
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'proposal' && Yii::$app->controller->id == 'admin/proposals'),
            'sortOrder' =>202 ,
        ]);
    }

    /**
     * Defines what to do if a admin user sing up in humhub.
     * @param $event
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     */
    public static function onHourlyCron($event)
    {
        $proposals = Proposal::find()->all();

        $group = Group::findOne(['id' => 1 ]);

        if ($group !== null && $proposals !== null) {
            foreach ($group->groupUsers as $user) {
                $profile = $user->user->profile;
                if ($profile->spaces_loaded != "true") {

                    foreach ($proposals as $proposal) {
                        $space = Events::findSpace($proposal->space_id);
                        if ($space !== null) {
                            $space->addMember($user->user_id, 0, false);
                            Events::setAdmin($space, $user->user_id );
                        }
                    }
                    $profile->spaces_loaded = "true";
                    $profile->save();
                }
            }
        }
    }

    protected static function findSpace ($id): ?Space
    {
        if (($space = Space::findOne($id)) !== null) {
            return $space;
        }
        return null;
    }

    /**
     * Set Admin for this Space
     * @param Space $space
     * @param $userId
     */
    protected static function setAdmin(Space $space, $userId)
    {
        $membership = Events::getMembership($space, $userId);
        if ($membership != null) {
            $membership->group_id = Space::USERGROUP_ADMIN;
            $membership->send_notifications = 1;
            $membership->save();
        }
    }

    /**
     * Returns the SpaceMembership Record for this Space
     * @param Space $space
     * @param $userId
     * @return Membership the membership
     */
    protected static function getMembership(Space $space, $userId): Membership
    {
        if ($userId instanceof User) {
            $userId = $userId->id;
        } elseif (!$userId || $userId == '') {
            $userId = Yii::$app->user->id;
        }

        return Membership::findOne(['user_id' => $userId, 'space_id' => $space->owner->id]);
    }

}
