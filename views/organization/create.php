<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model u4impact\humhub\modules\proposal\models\Organization */

$this->title = 'Crear Organización';
$this->params['breadcrumbs'][] = ['label' => 'Organizations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel container">
    <div class="panel-heading">
        <h1><strong> <?= Html::encode($this->title) ?></strong></h1>
    </div>
    <?= $this->render('_formCreate', [
        'model' => $model,
    ]) ?>

</div>
