<?php
/* @var $this FileController */
/* @var $model File */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'file-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'section_id'); ?>
		<?php echo $form->dropDownList($model,'section_id',$this->getListofSections()); ?>
		<?php echo $form->error($model,'section_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'number'); ?>
		<?php echo $form->textField($model,'number',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'open_date'); ?>
		<?php  
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$model,
					'attribute'=>'open_date',
					'value'=>$model->open_date,
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
		<?php echo $form->error($model,'open_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'close_date'); ?>
		<?php  
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$model,
					'attribute'=>'close_date',
					'value'=>$model->close_date,
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
		<?php echo $form->error($model,'close_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'number_of_pages'); ?>
		<?php echo $form->textField($model,'number_of_pages',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'number_of_pages'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'number_of_paras_on_note_part'); ?>
		<?php echo $form->textField($model,'number_of_paras_on_note_part',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'number_of_paras_on_note_part'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->