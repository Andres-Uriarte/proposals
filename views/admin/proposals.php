<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$options = [ 0 => "Pendiente", 1 => "Por validar", 2 => "Validada", 3 => "Publicada", 4 => "Rechazada", 5 => "Terminada"];

$this->title = 'Propuestas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-heading">
                <div class="row">
                    <div class="col-md-9">
                        <h1>Listado de <strong>Propuestas</strong></h1>
                    </div>
                    <div class="col-md-3">
                        <p>
                            <?= Html::a('Nueva Propuesta', ['create'], ['class' => 'btn btn-primary']) ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="panel panel-body" id="content-formatting">
                <?php

                $columns = [
                    ['class' => 'yii\grid\SerialColumn'],
                    'title',
                    'organization',
                    [
                        'attribute' => 'status',
                        'value' => function($model) use ($options) {
                            return $options[array_keys($options)[$model->status]];
                        },
                    ],
                    ['class' => 'yii\grid\ActionColumn'],
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

