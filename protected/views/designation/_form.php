<?php
/* @var $this DesignationController */
/* @var $model Designation */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'designations-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'short_form'); ?>
		<?php echo $form->textField($model,'short_form',array('size'=>60,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'short_form'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bps'); ?>
		<?php echo $form->textField($model,'bps',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'bps'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'level'); ?>
		<?php echo $form->textField($model,'level',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'level'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->