<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model u4impact\humhub\modules\proposal\models\Proposal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="panel panel-default">
    <div class="panel panel-body">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'organization_area')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'objectives')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'short_description')->textarea(['maxlength' => true]) ?>

        <?= $form->field($model, 'expected_progress')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'expected_progress_indicators')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'impact')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'related_links')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'sdg')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'student_academic_profile')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'student_profile')->textarea(['maxlength' => true]) ?>

        <?= $form->field($model, 'requirements')->textarea(['maxlength' => true]) ?>

        <?= $form->field($model, 'consent')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'rrss_consent')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
