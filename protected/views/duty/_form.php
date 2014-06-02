<?php
/* @var $this DutyController */
/* @var $model Duty */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'duties-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'duty_id'); ?>
		<?php echo $form->dropDownList($model,'duty_id',Duty::getListofDuties()); 
			  echo $form->checkBox($model, 'null_value_for_duty_id', array('id'=>'null_chk'));
			  echo "NULL";
		?>
		<?php echo $form->error($model,'duty_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'duty'); ?>
		<?php echo $form->textField($model,'duty',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'duty'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'short_form'); ?>
		<?php echo $form->textField($model,'short_form',array('size'=>60,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'short_form'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'info'); ?>
		<?php echo $form->textField($model,'info',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'info'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'section_id'); ?>
		<?php echo $form->dropDownList($model,'section_id', $this->getListofSections()); ?> 
		<?php echo $form->error($model,'duty_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php 
Yii::app()->clientScript->registerScript('duty_id_chk', '
$("#null_chk").click(function() {
       if ($(this).is(":checked")) { 
          $("#Duties_duty_id").prop("disabled", true);
       } else {
          $("#Duties_duty_id").prop("disabled", false);  
       }
    });
');
?>