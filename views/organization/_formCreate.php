<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model u4impact\humhub\modules\proposal\models\Organization */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="panel panel-default">
    <div class="panel panel-body">

        <?php $form = ActiveForm::begin();

        if (Yii::$app->user->isAdmin()) { ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true])?>
        <?php } else { ?>
            <?= $form->field($model, 'name')->textInput(['disabled' => 'disabled'])?>
        <?php } ?>

        <?= $form->field($model, 'legal_form')->dropDownList(['SA', 'SL', 'Fundación', 'Asociación', 'Autónomo', 'Emprendedor (sin registrar)']) ?>

        <?= $form->field($model, 'web_page')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'big')->dropDownList(['Sí', 'No']) ?>

        <div class="form-group">
            <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
