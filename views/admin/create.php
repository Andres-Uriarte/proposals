<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model u4impact\humhub\modules\proposal\models\Proposal */

$this->title = 'Crear Propuesta';
$this->params['breadcrumbs'][] = ['label' => 'Proposals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="proposal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formCreate', [
        'model' => $model,
    ]) ?>

</div>
