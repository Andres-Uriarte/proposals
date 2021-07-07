<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$legalFormOptions = [0 => 'SA', 1 => 'SL', 2 => 'Fundación', 3 => 'Asociación', 4 => 'Autónomo', 5 => 'Emprendedor (sin registrar)'];
$bigOptions = [ 0 => 'No', 1 => 'Sí'];

$this->title = 'Organizaciones';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-heading">
                <div class="row">
                    <div class="col-md-9">
                        <h1>Listado de <strong>Organizaciones</strong></h1>
                    </div>
                    <div class="col-md-3">
                        <p>
                            <?= Html::a('Nueva Organización', Url::to(['/proposal/organization/create']), ['class' => 'btn btn-primary']) ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="panel panel-body" id="content-formatting">

                <?php
                $columns = [
                    ['class' => 'yii\grid\SerialColumn'],
                    'name',
                    ['attribute' => 'legal_form',
                        'value' => function($model) use ($legalFormOptions) {
                            return $legalFormOptions[array_keys($legalFormOptions)[$model->legal_form]];
                        }],
                    'web_page',
                    ['class' => 'yii\grid\ActionColumn',
                     'buttons' => [
                            'view' => function ($url, $model) {
                             return Html::a(
                                 '<span class="glyphicon glyphicon-eye-open"></span>',
                                 Url::to(['/proposal/organization/view?id=' . $model->id]));
                            },
                            'update' => function ($url, $model) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-pencil"></span>',
                                    Url::to(['/proposal/organization/update?id=' . $model->id]));
                            },
                            'delete' => function ($url, $model) {
                             return Html::a(
                                 '<span class="glyphicon glyphicon-trash"></span>',
                                 Url::to(['/proposal/organization/delete?id=' . $model->id]), [
                                 'data' => [
                                     'confirm' => '¿Está seguro de eliminar este elemento?',
                                     'method' => 'post',
                                 ],
                             ]);
                            },
                        ],
                    ],
                ];

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
                    <div class="btn btn-lg ">
                        <span class="glyphicon glyphicon-info-sign"></span>
                        <span> <?= Html::a('Proceso de la organización', 'https://marmotdev.atlassian.net/wiki/spaces/UP/pages/455540740/Organizaci+n') ?></span>
                    </div>
                    <div class="btn btn-lg ">
                        <span class="glyphicon glyphicon-info-sign"></span>
                        <span> <?= Html::a('Proceso del alumno', 'https://marmotdev.atlassian.net/wiki/spaces/UP/pages/455606335/Estudiante') ?></span>
                    </div>
                    <div class="btn btn-lg">
                        <span class="glyphicon glyphicon-book"></span>
                        <span> <?= Html::a('Más información', 'https://www.u4impact.org') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

