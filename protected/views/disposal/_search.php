<?php
/* @var $this DisposalController */
/* @var $model Disposal */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'document_id'); ?>
		<?php echo $form->textField($model,'document_id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'officer_id'); ?>
		<?php echo $form->textField($model,'officer_id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'disposal_date'); ?>
		<?php echo $form->textField($model,'disposal_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'disposal_type'); ?>
		<?php echo $form->textField($model,'disposal_type',array('size'=>25,'maxlength'=>25)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'disposal_ref_number'); ?>
		<?php echo $form->textField($model,'disposal_ref_number',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'reference_dated'); ?>
		<?php echo $form->textField($model,'reference_dated'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dispatcher_user_id'); ?>
		<?php echo $form->textField($model,'dispatcher_user_id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'out_date'); ?>
		<?php echo $form->textField($model,'out_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ref_file_page'); ?>
		<?php echo $form->textField($model,'ref_file_page',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'filed_by'); ?>
		<?php echo $form->textField($model,'filed_by',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'is_reminder'); ?>
		<?php echo $form->textField($model,'is_reminder',array('size'=>5,'maxlength'=>5)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'owner_id'); ?>
		<?php echo $form->textField($model,'owner_id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'disposal'); ?>
		<?php echo $form->textField($model,'disposal',array('size'=>60,'maxlength'=>255)); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->