<?php
/* @var $this OfficerdesigdutyassignmentController */
/* @var $model OfficerDesigDutyAssignment */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'officer-desig-duty-assignment-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'officer_id'); ?>
		<?php echo $form->dropDownList($model,'officer_id',Officer::getListofOfficers()); ?>
		<?php echo $form->error($model,'officer_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'designation_id'); ?>
		<?php echo $form->dropDownList($model,'designation_id',Designation::getListofDesignations()); ?>
		<?php echo $form->error($model,'designation_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'duty_id'); ?>
		<?php echo $form->dropDownList($model,'duty_id',$this->getListofDuties()); ?>
		<?php echo $form->error($model,'duty_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'state'); ?>
		<?php echo $form->dropDownList($model,'state', array('Inactive', 'Active')); ?>
		<?php echo $form->error($model,'state'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'wef'); ?>
		<?php  
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$model,
					'attribute'=>'wef',
					'value'=>$model->wef,
	    			'options'=>array(
		       		'showAnim'=>'fold',
							'dateFormat'=>Yii::app()->user->getDateFormat(true), 
							'changeMonth' => true,
							'changeYear' => true,
							'showOn'=>'both',
		          'buttonText'=>'...',
		    		),
	    			'htmlOptions'=>array(
	        			'style'=>'height:20px;',
	    			),
				));
		?>
		<?php echo $form->error($model,'wef'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->