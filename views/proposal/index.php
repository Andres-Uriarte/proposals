<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\YiiAsset;
use yii\widgets\Pjax;
use u4impact\humhub\modules\proposal\models\Organization;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $user yii\web\User */
/* @var $type string */
/* @var $organization Organization */

$this->title = 'Propuestas';
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);

$options = [ 0 => "Pendiente", 1 => "Por validar", 2 => "Validada", 3 => "Publicada", 4 => "Rechazada", 5 => "Terminada"];
$colors = ['Pendiente' => "btn-danger", 'Por validar' => "btn-warning", 'Validada' => "btn-info", 'Publicada' => "btn-success", 'Rechazada' => "btn-danger", "Terminada" => "btn-primary"];
?>

<div class="container">
    <div class="col-md-9">
        <div class="panel panel-heading">
            <div class="row">
                <div class="col-md-6" >
                    <?php if ($type == ORGANIZATION) { ?>
                    <h1><?= Html::encode($this->title . ' de ') ?>
                        <strong><?= Html::encode($user->identity->profile->organization_name) ?></strong>
                    </h1>
                </div>
                <div class="col-md-3">
                    <p>
                        <?= Html::a('Nueva Propuesta', ['create'], ['class' => 'btn btn-primary']) ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <p>
                        <?= Html::a('Organización', ['/proposal/organization/view', 'id' => $organization->id], ['class' => 'btn btn-primary']) ?>
                    </p>
                    <?php } else { ?>

                        <h1>Listado de <strong><?= Html::encode($this->title) ?></strong></h1>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="panel panel-body" id="content-formatting">
            <?php
            $columns = [
                ['class' => 'yii\grid\SerialColumn'],
                'title',
                'short_description',
                ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
            ];

            if ($type == ORGANIZATION) {
                $columns = [
                    ['class' => 'yii\grid\SerialColumn'],
                    'title',
                    'short_description',
                    [
                        'attribute' => 'status',
                        'value' => function($model) use ($options) {
                            return $options[array_keys($options)[$model->status]];
                        },
                    ],
                    ['class' => 'yii\grid\ActionColumn'],
                ];
            }
            ?>

            <?php Pjax::begin(); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => $columns,
            ]); ?>

            <?php Pjax::end(); ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Observaciones</strong></div>
            <div class="panel-body">
                <?php
                if ($type == ORGANIZATION) {
                ?>
                    <div class="btn btn-lg ">
                        <span class="glyphicon glyphicon-info-sign"></span>
                        <span> <?= Html::a('Proceso de la organización', 'https://marmotdev.atlassian.net/wiki/spaces/UP/pages/455540740/Organizaci+n') ?></span>
                    </div>
                <?php
                } else {
                ?>
                    <div class="btn btn-lg ">
                        <span class="glyphicon glyphicon-info-sign"></span>
                        <span> <?= Html::a('Proceso del alumno', 'https://marmotdev.atlassian.net/wiki/spaces/UP/pages/455606335/Estudiante') ?></span>
                    </div>
                    <?php
                }
                ?>
                <div class="btn btn-lg">
                    <span class="glyphicon glyphicon-book"></span>
                    <span> <?= Html::a('Más información', 'https://www.u4impact.org') ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
