<?php

namespace u4impact\humhub\modules\proposal\controllers;

use humhub\components\Controller;
use Throwable;
use u4impact\humhub\modules\proposal\models\Organization;
use Yii;
use yii\db\StaleObjectException;

define('ORGANIZATION', 'organization');

class OrganizationController extends Controller
{
    /**
     * Renders the View view for the module
     *
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        $user = Yii::$app->user;
        $profile = $user->identity->profile;

        if ($user->isAdmin() || ($profile->profile == ORGANIZATION && $profile->profile_validation == "true")) {

            $organization = Organization::findOne(['id' => $id]);

            if ($organization == null) {
                if ($user->isAdmin()) {
                    return $this->redirect(['/proposal/proposal']);
                }
            }

            return $this->render('view', [
                'model' => $organization, 'admin' => $user->isAdmin()
            ]);

        } else {
            return $this->redirect(['/dashboard/dashboard']);
        }
    }

    /**
     * Renders the create view for the module
     * @return string
     */
    public function actionCreate()
    {
        $user = Yii::$app->user;
        $profile = $user->identity->profile;

        if ($user->isAdmin() || ($profile->profile == ORGANIZATION && $profile->profile_validation == "true")) {
            $organization = new Organization();

            if ($user->isAdmin()) {
                if ($organization->load(Yii::$app->request->post()) && $organization->save()) {
                    return $this->redirect(['/proposal/admin/organizations']);
                }
            } else if ($organization->load(Yii::$app->request->post())) {
                $organization->name = $profile->organization_name;
                $organization->save();
                return $this->redirect(['/proposal/proposal/index']);
            }

            $organization = Organization::findOne(['name' => $profile->organization_name]);

            if ($organization == null) {

                $organization = new Organization();
                $organization->name = $profile->organization_name;

                return $this->render('create', [
                    'model' => $organization,
                ]);
            } else {
                return $this->redirect(['/proposal/proposal']);
            }

        } else {
            return $this->redirect(['/dashboard/dashboard']);
        }
    }

    /**
     * Renders the update view for the module
     *
     * @param $id
     * @return string
     */
    public function actionUpdate($id)
    {
        $user = Yii::$app->user;
        $profile = $user->identity->profile;

        if ($user->isAdmin() || ($profile->profile == ORGANIZATION && $profile->profile_validation == "true")) {

            $organization = Organization::findOne(['id' => $id]);

            if ($organization == null) {
                if ($user->isAdmin()) {
                    return $this->redirect(['/proposal/admin/organizations']);
                }
            }
            if ($user->isAdmin()) {
                if ($organization->load(Yii::$app->request->post()) && $organization->save()) {
                    return $this->redirect(['/proposal/admin/organizations']);
                }
            }

            if ($organization->load(Yii::$app->request->post()) && $organization->save()) {
                return $this->redirect(['view', 'id' => $organization->id]);
            }


            return $this->render('update', [
                'model' => $organization
            ]);

        } else {
            return $this->redirect(['/dashboard/dashboard']);
        }
    }

    /**
     * Renders the delete view for the module
     *
     * @param $id
     * @return string
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $user = Yii::$app->user;

        if ($user->isAdmin()) {

            $organization = Organization::findOne(['id' => $id]);

            if ($organization != null) {
                $organization->delete();
            }

            return $this->redirect(['/proposal/admin/organizations']);

        } else {
            return $this->redirect(['/dashboard/dashboard']);
        }
    }

}

