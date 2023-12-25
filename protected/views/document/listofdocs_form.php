<?php
/* @var $this SiteController */
/* @var $model ListofDocsForm */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget(
        'CActiveForm',
        array(
            'id' => 'listofdocs-form',
            'enableAjaxValidation' => false,
        )
    );
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>


    <div class="row">
        <?php echo $form->labelEx($model, 'officer_id'); ?>
        <?php echo $form->dropDownList($model, 'officer_id', Assignments::getDesignationsListIndexedByDutyID1()); ?>
        <?php echo $form->error($model, 'officer_id'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->