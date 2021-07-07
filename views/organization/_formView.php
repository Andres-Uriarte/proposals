<?php

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model u4impact\humhub\modules\proposal\models\Organization */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="panel panel-default">
    <div class="panel panel-body">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'disabled' => 'disabled']) ?>

        <?= $form->field($model, 'legal_form')->dropDownList(['SA', 'SL', 'Fundación', 'Asociación', 'Autónomo', 'Emprendedor (sin registrar)'], ['disabled' => 'disabled']) ?>

        <?= $form->field($model, 'web_page')->textInput(['maxlength' => true, 'disabled' => 'disabled']) ?>

        <?= $form->field($model, 'big')->dropDownList(['Sí', 'No'], ['disabled' => 'disabled']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>

