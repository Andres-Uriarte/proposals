<?php

use humhub\modules\user\models\User;
use humhub\modules\user\widgets\UserPickerField;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model u4impact\humhub\modules\proposal\models\Proposal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="panel panel-default">

    <?php $form = ActiveForm::begin();
    $options = ['Pendiente' => "btn-danger", 'Por validar' => "btn-warning", 'Validada' => "btn-info", 'Publicada' => "btn-success", 'Rechazada' => "btn-danger", "Terminada" => "btn-primary"];
    $color = $options[array_keys($options)[$model->status]];
    ?>

    <div>
        <p class="<?php echo $color ?> btn-lg"><?php echo array_keys($options)[$model->status]; ?></p>
    </div>

    <div class="panel panel-body">


        <?= $form->field($model, 'status')->dropDownList(['Pendiente', 'Por validar', 'Validada', 'Publicada', 'Rechazada', 'Terminada']) ?>

        <?= $form->field($model, 'organization')->textInput(['maxlength' => true]) ?>

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
