<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model u4impact\humhub\modules\proposal\models\Organization */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Organization', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="panel container">
    <div class="panel-heading">
        <h1>Editar: <strong> <?= Html::encode($this->title) ?></strong></h1>
    </div>
    <?= $this->render('_formUpdate', [
        'model' => $model,
    ]) ?>

</div>
