<?php
/* @var $this FileMovementController */
/* @var $model FileMovement */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'file-movement-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'document_id'); ?>
		<?php echo $form->textField($model,'document_id'); ?>
		<?php echo $form->error($model,'document_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'file_page_no'); ?>
		<?php echo $form->textField($model,'file_page_no'); ?>
		<?php echo $form->error($model,'file_page_no'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'marked_date'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$model,
					'attribute'=>'marked_date',
					'value'=>$model->marked_date,
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
						'disabled' => $model->marked_date ? true : false,
	    			),
				)); ?>
		<?php echo $form->error($model,'marked_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'marked_to'); ?>
		<?php echo $form->dropDownList($model,'marked_to',  Assignments::getDesignationsListIndexedByDutyID1(true)); ?>
		<?php echo $form->error($model,'marked_to'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'marked_by'); ?>
		<?php echo $form->dropDownList($model,'marked_by', User::getListOfDesigByDutyID()); ?>
		<?php echo $form->error($model,'marked_by'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->