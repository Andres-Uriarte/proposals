<?php

use yii\helpers\Html;
use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $model u4impact\humhub\modules\proposal\models\Organization */
/* @var $admin boolean */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Organization', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>

<div class="panel container">
    <div class="panel-heading">
    <h1>Organización: <strong> <?= Html::encode($this->title) ?></strong></h1>
    </div>
    <div class="panel-heading">
        <p>
            <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

            <?php if ($admin) { ?>
                <?= Html::a('Todas las organizaciones', ['/proposal/admin/organizations'], ['class' => 'btn btn-success']) ?>
            <?php } else { ?>
                <?= Html::a('Todas mis propuestas', ['/proposal/proposal/'], ['class' => 'btn btn-success']) ?>
            <?php } ?>
        </p>
    </div>
    <?= $this->render('_formView', [
        'model' => $model,
    ]) ?>

</div>
