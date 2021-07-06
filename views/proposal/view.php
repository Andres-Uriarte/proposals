<?php

use yii\helpers\Html;
use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $model u4impact\humhub\modules\proposal\models\Proposal */
/* @var $user yii\web\User */
/* @var $type string */
/* @var $url string */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Proposals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>

<div class="container">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="panel panel-heading">
        <p>
            <?php if ($type == ORGANIZATION) { ?>
                <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Borrar', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => '¿Está usted seguro que quiere borrar esta propuesta? ' . PHP_EOL . PHP_EOL . $model->title,
                        'method' => 'post',
                    ],
                ]) ?>

                <?= Html::a('Espacio', $url, ['class' => 'btn btn-default']) ?>
                <?= Html::a('Todas mis propuestas', ['index'], ['class' => 'btn btn-success']) ?>

            <?php } else if ($type == STUDENT) { ?>

                <?= Html::a('Aplicar', ['apply', 'id' => $model->id], [
                    'class' => 'btn btn-info',
                    'data' => [
                        'confirm' => '¿Estás seguro que quiere aplicar a esta propuesta?' . PHP_EOL . PHP_EOL . $model->title . PHP_EOL . PHP_EOL . 'En caso afirmativo los responsables de la propuesta se pondrán en contacto contigo en la mayor brevedad posible.' . PHP_EOL .' ¡Gracias!',
                        'method' => 'post',
                    ]
                ]) ?>

                <?= Html::a('Todas las propuestas', ['index'], ['class' => 'btn btn-primary']) ?>

            <?php } ?>
        </p>
    </div>

    <?= $this->render('_formView', [
        'model' => $model,
    ]) ?>

</div>
