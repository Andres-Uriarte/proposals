<?php

namespace u4impact\humhub\modules\proposal\controllers;

use Colors\RandomColor;
use humhub\modules\admin\components\Controller;
use humhub\modules\admin\permissions\SeeAdminInformation;
use humhub\modules\post\models\Post;
use humhub\modules\space\models\Membership;
use humhub\modules\space\models\Space;
use humhub\modules\space\permissions\CreatePrivateSpace;
use humhub\modules\space\permissions\CreatePublicSpace;
use humhub\modules\user\models\Group;
use humhub\modules\user\models\User;
use Throwable;
use u4impact\humhub\modules\proposal\models\Organization;
use u4impact\humhub\modules\proposal\models\Proposal;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

define('URL_BASE_HUMHUB', Yii::$app->settings->get('baseUrl'));
define('SPACE_OWNER_DEFAULT_ALL_SPACES', 1);

/**
 * ProposalController implements the CRUD actions for Proposal model by Admins.
 */
class AdminController extends Controller
{

    public function getAccessRules(): array
    {
        return [
            ['permissions' => SeeAdminInformation::class]
        ];
    }

    /**
     * Lists all Proposal models.
     * @return mixed
     */
    public function actionProposals()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Proposal::find(),
        ]);

        return $this->render('proposals', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Organization models.
     * @return mixed
     */
    public function actionOrganizations()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Organization::find(),
        ]);

        return $this->render('organizations', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Proposal model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $proposal = $this->findProposal($id);
        $space = $this->findSpace($proposal->space_id);

        $url = URL_BASE_HUMHUB . '/s/' . $space->url;

        return $this->render('view', [
            'model' => $this->findModel($id), 'url' => $url
        ]);
    }

    /**
     * Creates a new Proposal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws Exception
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws Throwable
     */
    public function actionCreate()
    {
        $proposal = new Proposal();

        if ($proposal->load(Yii::$app->request->post()) && $proposal->save()) {

            $space = $this->spaceCreate($proposal);
            $proposal->space_id = $space->id;
            $proposal->save();

            return $this->redirect(['view', 'id' => $proposal->id]);
        }

        return $this->render('create', [
            'model' => $proposal,
        ]);
    }

    /**
     * Updates an existing Proposal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Proposal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws Exception
     * @throws NotFoundHttpException if the model cannot be found
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionDelete($id)
    {
        $user = Yii::$app->user;
        $proposal = $this->findProposal($id);

        if ($user->isAdmin() && $proposal != null) {
            $this->deleteSpace($proposal);
            $proposal->delete();

            return $this->redirect(['proposals']);
        } else {
            return $this->redirect(['/dashboard/dashboard']);
        }
    }

    /**
     * Finds the Proposal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Proposal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Proposal
    {
        if (($model = Proposal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the Proposal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Proposal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findProposal ($id): Proposal
    {

        if (($proposal = Proposal::findOne($id)) !== null) {
            return $proposal;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the Space model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Space|null the loaded model
     */
    protected function findSpace ($id): ?Space
    {
        if (($space = Space::findOne($id)) !== null) {
            return $space;
        }
        return null;
    }

    /**
     * @param Proposal $proposal
     * @return Space the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws Exception
     * @throws InvalidConfigException
     */
    protected function spaceCreate ($proposal): Space
    {
        if (!Yii::$app->user->permissionmanager->can(new CreatePublicSpace) && !Yii::$app->user->permissionmanager->can(new CreatePrivateSpace)) {
            throw new NotFoundHttpException('El usuario no tiene permisos para crear spaces');
        }

        $name = $proposal->id . " - " . $proposal->title;
        $description = $proposal->organization;

        $space = new Space();
        $space->name = $name;
        $space->description = $description;
        $space->scenario = Space::SCENARIO_CREATE;
        $space->visibility = Space::VISIBILITY_NONE;
        $space->join_policy = Space::JOIN_POLICY_NONE;
        $space->color = RandomColor::one(['luminosity' => 'dark']);

        $space->load(Yii::$app->request->getBodyParams(), '');
        $space->validate();

        if ($space->hasErrors()) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if ($space->save()) {
            $this->addMembersAdmin($space);

            $usersIds = $this->findMembersSameOrganization($proposal->organization);
            foreach ($usersIds as $userId) {
                $this->addMembersModerators($space, $userId);
            }

            $this->publishCreatePost($space, $proposal->id);

            return $space;
        }

        return $space;
    }

    /**
     * @param Space $space
     * @throws Throwable
     * @throws InvalidConfigException
     */
    protected function addMembersAdmin (Space $space)
    {
        $group = Group::findOne(['id' => 1 ]);

        if ($group !== null) {
            foreach ($group->groupUsers as $user) {
                if ($space !== null) {
                    $space->addMember($user->user_id, 0, false);
                    $this->setAdmin($space, $user->user_id );
                }
            }
        }

        $userAdmin = User::findOne(['id' => SPACE_OWNER_DEFAULT_ALL_SPACES])->id;
        $space->setSpaceOwner($userAdmin);
    }

    /**
     * Set Admin for this Space
     * @param Space $space
     * @param $userId
     */
    protected function setAdmin(Space $space, $userId)
    {
        $membership = $this->getMembership($space, $userId);
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
    protected function getMembership(Space $space, $userId): Membership
    {
        if ($userId instanceof User) {
            $userId = $userId->id;
        } elseif (!$userId || $userId == '') {
            $userId = Yii::$app->user->id;
        }

        return Membership::findOne(['user_id' => $userId, 'space_id' => $space->owner->id]);
    }

    /**
     * Returns the members of the same organization
     * @param string $organization_name
     * @return array the id users found
     */
    protected function findMembersSameOrganization(string $organization_name): array
    {
        $filteredUsers = [];
        $allUsers = User::find()->active()->all();

        foreach ($allUsers as $user) {
            if(strtolower($user->profile->organization_name) == strtolower($organization_name)) {
                array_push($filteredUsers, $user->id);
            }
        }

        return $filteredUsers;
    }

    /**
     * Set Moderator for this Space
     * @param Space $space
     * @param $userId
     * @throws Throwable
     * @throws InvalidConfigException
     */
    protected function addMembersModerators(Space $space, $userId)
    {
        $space->addMember($userId, 0, false);
        $membership = $this->getMembership($space, $userId);
        if ($membership != null) {
            $membership->group_id = Space::USERGROUP_MODERATOR;
            $membership->send_notifications = 1;
            $membership->can_cancel_membership = 0;
            $membership->save();
        }
    }

    /**
     * Publish the first Post in the Space assigned to the Proposal
     * @param Space $space
     * @param int $proposal_id
     * @throws Exception
     */
    protected function publishCreatePost(Space $space, int $proposal_id): void
    {
        $post = new Post($space, ['message' => '###### La propuesta **[' . $space->name . '](' . URL_BASE_HUMHUB . '/proposal/proposal/view?id=' . $proposal_id . ')** acaba de ser creada por la organización **' . $space->description . '**']);
        $post->content->pin();

        if ($post->validate()) {
            $post->save();
        }
    }

    /**
     * @param Proposal $proposal
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws Throwable
     */
    protected function deleteSpace(Proposal $proposal): void
    {
        $space = $this->findSpace($proposal->space_id);
        if ($space != null) {
            $space->delete();
        }
    }
}
