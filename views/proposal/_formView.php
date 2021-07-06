<?php

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

        <?= ($form->field($model, 'organization'))->textInput(['disabled' => 'disabled']) ?>

        <?= $form->field($model, 'consent')->textarea(['disabled' => 'disabled']) ?>

        <?= $form->field($model, 'rrss_consent')->textarea(['disabled' => 'disabled']) ?>

        <?= $form->field($model, 'organization_area')->textarea(['disabled' => 'disabled']) ?>

        <?= $form->field($model, 'title')->textarea(['disabled' => 'disabled']) ?>

        <?= $form->field($model, 'objectives')->textarea(['disabled' => 'disabled']) ?>

        <?= $form->field($model, 'short_description')->textarea(['disabled' => 'disabled']) ?>

        <?= $form->field($model, 'expected_progress')->textarea(['disabled' => 'disabled']) ?>

        <?= $form->field($model, 'expected_progress_indicators')->textarea(['disabled' => 'disabled']) ?>

        <?= $form->field($model, 'impact')->textarea(['disabled' => 'disabled']) ?>

        <?= $form->field($model, 'related_links')->textarea(['disabled' => 'disabled']) ?>

        <?= $form->field($model, 'sdg')->textarea(['disabled' => 'disabled']) ?>

        <?= $form->field($model, 'student_academic_profile')->textarea(['disabled' => 'disabled']) ?>

        <?= $form->field($model, 'student_profile')->textarea(['disabled' => 'disabled']) ?>

        <?= $form->field($model, 'requirements')->textarea(['disabled' => 'disabled']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>
