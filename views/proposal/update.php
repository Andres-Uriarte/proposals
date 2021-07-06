<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model u4impact\humhub\modules\proposal\models\Proposal */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Organization', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="container">

    <div>
        <h1>Editar <strong><?= Html::encode($this->title) ?></strong></h1>
    </div>

    <?= $this->render('_formUpdate', [
        'model' => $model
    ]) ?>

</div>
