<?php

namespace u4impact\humhub\modules\proposal\controllers;

use Colors\RandomColor;
use humhub\modules\post\models\Post;
use humhub\modules\space\models\Membership;
use humhub\modules\space\models\Space;
use humhub\modules\space\permissions\CreatePrivateSpace;
use humhub\modules\space\permissions\CreatePublicSpace;
use humhub\modules\user\models\Group;
use humhub\modules\user\models\User;
use phpDocumentor\Reflection\Types\Integer;
use Throwable;
use u4impact\humhub\modules\proposal\models\Organization;
use Yii;
use u4impact\humhub\modules\proposal\models\Proposal;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

define('ORGANIZATION', 'organization');
define('STUDENT', 'student');
define('SPACE_OWNER_DEFAULT_ALL_SPACES', 1);
define('HUMHUB_ADMIN_GROUP_ID', 1);
define('URL_BASE_HUMHUB', Yii::$app->settings->get('baseUrl'));

/**
 * ProposalController implements the CRUD actions for Proposal model.
 */
class ProposalController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors ()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Proposal models.
     * @return mixed
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws Throwable
     */
    public function actionIndex ()
    {
        $user = Yii::$app->user;
        $profile = $user->identity->profile;
        $organization = new Organization();

        if ($user->isAdmin()) {
            return $this->redirect(['/proposal/admin/proposals']);
        } else if ($profile->profile == ORGANIZATION && $profile->profile_validation == "true") {
            $type = 'organization';

            if ($profile->spaces_loaded != "true" ) {
                $spaces = $this->findSpacesSameOrganization($profile->organization_name);

                foreach ($spaces as $space) {
                    $this->addMembersModerators($space, $user->id);
                }
                $profile->spaces_loaded = "true";
                $profile->save();
            }

            $organization = Organization::findOne(['name' => $profile->organization_name]);
            if ($organization == null) {
                return $this->redirect(['/proposal/organization/create']);
            }

            $dataProvider = new ActiveDataProvider([
                'query' => Proposal::find()->proposalOrganization($profile->organization_name),
            ]);
        } else if ($profile->profile == STUDENT) {
            $type = 'student';
            $dataProvider = new ActiveDataProvider([
                'query' => Proposal::find()->proposalPublish(),
            ]);
        } else {
            return $this->render('index_no_validated');
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider, 'user' => $user, 'type' => $type, 'organization' => $organization
        ]);
    }

    /**
     * Displays a single Proposal model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws InvalidConfigException|Throwable
     */
    public function actionView ($id)
    {
        $user = Yii::$app->user;
        $profile = $user->identity->profile;

        if ($user->isAdmin()) {
            return $this->redirect(['/proposal/admin/view', 'id' => $id]);
        } else if ($profile->profile == ORGANIZATION && $profile->profile_validation == "true") {
            $type = 'organization';
        } else if ($profile->profile == STUDENT) {
            $type = 'student';
        } else {
            return $this->render('index_no_validated');
        }

        $proposal = $this->findProposal($id);
        $space = $this->findSpace($proposal->space_id);

        $url = URL_BASE_HUMHUB . '/s/' . $space->url;

        return $this->render('view', [
            'model' => $proposal, 'user' => $user, 'type' => $type, 'url' => $url
        ]);
    }

    /**
     * Creates a new Proposal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function actionCreate ()
    {
        $proposal = new Proposal();
        $user = Yii::$app->user;
        $profile = $user->identity->profile;

        if ($user->isAdmin()) {
            return $this->redirect(['/proposal/admin/create']);
        } else if ($profile->profile == ORGANIZATION && $profile->profile_validation == "true") {
            $organization = Organization::findOne(['name' => $profile->organization_name]);
            $proposal->organization = $profile->organization_name;
            $proposal->status = '0';
            $proposal->organization_id = $organization->id;
        } else {
            return $this->redirect(['/dashboard/dashboard']);
        }

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
    public function actionUpdate ($id)
    {
        $model = $this->findProposal($id);
        $user = Yii::$app->user;
        $profile = $user->identity->profile;

        if ($user->isAdmin()) {
            return $this->redirect(['/proposal/admin/update', 'id' => $id]);
        } else if ($profile->profile == ORGANIZATION && $profile->profile_validation == "true") {
            $type = 'organization';
        } else {
            return $this->redirect(['/dashboard/dashboard']);
        }

        if ($type == ORGANIZATION && $model->load(Yii::$app->request->post()) && $model->save()) {
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
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete ($id)
    {
        $user = Yii::$app->user;
        $profile = $user->identity->profile;
        $proposal = $this->findProposal($id);

        if ($user->isAdmin()) {
            return $this->redirect(['/proposal/admin/delete', 'id' => $id]);
        } else if ($profile->profile == ORGANIZATION && $profile->profile_validation == "true" && $proposal != null && $proposal->organization == $profile->organization_name) {
            $this->deleteSpace($proposal);
            $proposal->delete();
        } else {
            return $this->redirect(['/dashboard/dashboard']);
        }

        return $this->redirect(['index']);
    }

    /**
     * Apply an existing Proposal.
     * @param integer $id
     * @return mixed
     * @throws Exception
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     */
    public function actionApply ($id)
    {
        $user = Yii::$app->user;
        $profile = $user->identity->profile;
        $proposal = $this->findProposal($id);
        $space = $this->findSpace($proposal->space_id);

        if ($profile->profile == STUDENT) {
            $this->publishApplyPost($space);
            return $this->redirect(['/proposal/proposal']);

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
            $usersIds = $this->findMembersSameOrganization(Yii::$app->user->identity->profile->organization_name);
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
        $group = Group::findOne(['id' => HUMHUB_ADMIN_GROUP_ID ]);

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
     * Returns the Spaces of the same organization
     * @param string $organization_name
     * @return array spaces found
     */
    protected function findSpacesSameOrganization(string $organization_name): array
    {
        $spaces = [];
        $proposals = Proposal::findAll(['organization' => $organization_name]);

        foreach ($proposals as $proposal) {
            $space = $this->findSpace($proposal->space_id);
            array_push($spaces, $space);
        }

        return $spaces;
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
     * Publish the first Post in the Space assigned to the Proposal
     * @param Space $space
     * @throws Exception
     * @throws Throwable
     */
    protected function publishApplyPost(Space $space): void
    {
        $user = Yii::$app->user;
        $url = URL_BASE_HUMHUB . '/u/' . $user->identity->username;

        $post = new Post($space, ['message' => '###### El estudiante **[' . $user->getIdentity()->displayName . '](' . $url . ')** acaba de aplicar a la propuesta. Por favor, pongase en contacto en la mayor brevedad posible.']);

        if ($post->validate()) {
            $post->save();
        }
    }

    /**
     * @param Proposal $proposal
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
