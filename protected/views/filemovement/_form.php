<?php
/* @var $this FilemovementController */
/* @var $model Filemovement */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'filemovement-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'document_id'); ?>
		<?php echo $form->dropDownList($model,'document_id',$this->getListofDocuments()); ?>
		<?php echo $form->error($model,'document_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'marked_to'); ?>
		<?php echo $form->dropDownList($model,'marked_to',  OfficerDesigDutyAssignment::getDesignationsListIndexedByDutyID()); ?>
		<?php echo $form->error($model,'marked_to'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'marked_by'); ?>
		<?php echo $form->dropDownList($model,'marked_by', User::getListOfDesigByDutyID()); ?>
		<?php echo $form->error($model,'marked_by'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'file_id'); ?>
		<?php echo $form->textField($model,'file_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'file_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'marked_date'); ?>
		<?php echo $form->textField($model,'marked_date'); ?>
		<?php echo $form->error($model,'marked_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'received_date'); ?>
		<?php echo $form->textField($model,'received_date'); ?>
		<?php echo $form->error($model,'received_date'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->