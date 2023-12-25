<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	// 'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'fullname'); ?>
		<?php echo $form->textField($model,'fullname',array('size'=>100,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'fullname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'designation'); ?>
		<?php echo $form->textField($model,'designation',array('size'=>100,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'designation'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'passwd'); ?>
		<?php echo $form->passwordField($model,'passwd',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'passwd'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'passwd_repeat'); ?>
		<?php echo $form->passwordField($model,'passwd_repeat',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'passwd_repeat'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_type'); ?>
		<?php echo $form->dropDownList($model,'user_type',array(
			'user'=>'User',
			'dispatcher'=>'Dispatcher'
		)); ?>
		<?php echo $form->error($model,'user_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date_format'); ?>
		<?php echo $form->dropDownList($model,'date_format',array(
			'd.m.Y'=>'dd.mm.yyyy', 
			'd/m/Y'=>'dd/mm/yyyy', 
			'd-m-Y'=>'dd-mm-yyyy',
		)); ?>
		<?php echo $form->error($model,'date_format'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'can_create_doc'); ?>
		<?php echo $form->dropDownList($model,'can_create_doc',array(
			'yes'=>'Yes',
			'no'=>'No'
		)); ?>
		<?php echo $form->error($model,'can_create_doc'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->