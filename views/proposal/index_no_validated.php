<?php

/* @var $this yii\web\View */

use u4impact\humhub\modules\proposal\assets\Assets;
use yii\web\YiiAsset;

$this->title = 'Propuestas';
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);

// register module asset in view and store it to a variable
$myAssetBundle = Assets::register($this);
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <h1 class="panel-heading">
                    Esta a sólo un paso de poder ser miembro de <strong>U4Impact</strong>
                </h1>
                <div class="panel-heading">
                    Pero antes, debemos validar su cuenta. Por favor contacte con nosotros en:
                    <strong><a href="mailto:admin@u4impact.com</strong>">admin@u4impact.com</a></strong>
                </div>
                <br>
            </div>
        </div>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <img src="<?= $myAssetBundle->baseUrl . '/img/module_image.png'; ?>" alt="Proceso a seguir por una organización" width="100%" height="100%">
                </div>
            </div>
        </div>
    </div>
</div>
