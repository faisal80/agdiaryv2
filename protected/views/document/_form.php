<?php
/* @var $this DocumentController */
/* @var $model Document */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'document-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data'),	
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'reference_number'); ?>
		<?php echo $form->textField($model,'reference_number',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'reference_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date_of_document'); ?>
		<?php  
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$model,
					'attribute'=>'date_of_document',
					'value'=>$model->date_of_document,
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
		<?php echo $form->error($model,'date_of_document'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'received_from'); ?>
		<?php //echo $form->textField($model,'received_from',array('size'=>60,'maxlength'=>255)); 
			$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
					//'name'=>'received_from',
					'model'=>$model,
					'attribute'=>'received_from',
					'sourceUrl'=>$this->createUrl('document/receivedfromarray'),
					'options'=>array(
						'minLength'=>'0',
						'showAnim'=>'fold',
					),
					'htmlOptions'=>  array(
						'size'=>'60',	
					),
			));
		?>
		<?php echo $form->error($model,'received_from'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'doc_status'); ?>
		<?php $this->widget('application.extensions.multicomplete.MultiComplete', array(
				'model'=>$model,
				'attribute'=>'doc_status',
				'splitter'=>',',
				'source'=>array('immediate', 'most immediate', 'confidential', 'secret', 'by fax', 'through special messenger', 'reminder'),
				//'sourceUrl'=>'search.html',
				// additional javascript options for the autocomplete plugin
				'options'=>array(
					'minLength'=>'0',
				),
				'htmlOptions'=>array(
					'size'=>'60'
				),
			));
		?>
		<?php echo $form->error($model,'doc_status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_case_disposed'); ?>
		<?php echo $form->dropDownList($model,'is_case_disposed',array('no'=>'No','yes'=>'Yes')); ?>
		<?php echo $form->error($model,'is_case_disposed'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type_of_document'); ?>
		<?php $this->widget('application.extensions.multicomplete.MultiComplete', array(
				'model'=>$model,
				'attribute'=>'type_of_document',
				'splitter'=>',',
				'source'=>array('original', 'photo copy', 'duplicate', 'fax'),
				//'sourceUrl'=>'search.html',
				// additional javascript options for the autocomplete plugin
				'options'=>array(
					'minLength'=>'0',
				),
				'htmlOptions'=>array(
					'size'=>'60'
				),
			));
		?>
		<?php echo $form->error($model,'type_of_document'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'detail_of_enclosure'); ?>
		<?php echo $form->textField($model,'detail_of_enclosure',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'detail_of_enclosure'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tags'); ?>
		<?php $this->widget('application.extensions.multicomplete.MultiComplete', array(
				'model'=>$model,
				'attribute'=>'tags',
				'splitter'=>',',
				//'sourceUrl'=>array('pay', 'pension', 'allowance', 'tax', 'deduction', 'disciplinary', 'rule', 'regulation', 'retirement', 'service', 'recruitment', 'dismissal', 'termination'),
				'sourceUrl'=>$this->createUrl('document/tags'),
				// additional javascript options for the autocomplete plugin
				'options'=>array(
					'minLength'=>'0',
				),
				'htmlOptions'=>array(
					'size'=>'60'
				),
			));
		?>
		<?php echo $form->error($model,'tags'); ?>
	</div>

	<div class="row">
		<?php echo 'Marked by'; ?>
		<?php echo $form->dropDownList($model, 'owner_id', OfficerDesigDutyAssignment::getDesignationsListIndexedByDutyID()); ?>
		<?php echo $form->error($model, 'owner_id'); ?>
	</div>

	<div class="row">
		<?php
		  	$this->widget('CMultiFileUpload', array(
		  		'name' => 'image',
	     		//'model'=>$model,
	     		//'attribute'=>'files',
	     		'accept'=>'jpeg|jpg|gif|png',
		  		'duplicate' => 'File already selected!',
		  		'denied' => 'Invalid file type. Please select JPG/JPEG/GIF/PNG files only.',
			  ));
		?>
	</div>
	
	<?php if($model->isNewRecord!='1'){ ?>
		<div class="row">
		     <?php	foreach ($model->images as $key=>$image)		     		 
		     		{
		     			echo '<div class="span-6" id="image' . $key . '">';		     			
		     			echo $form->hiddenField($model, '', array('name'=>$image->id .'-'. $image->document_id .'-'. $key, 'value'=>''));
		     			echo CHtml::image(Yii::app()->createUrl('/document_images/'.$image->image_path),$image->image_path,array("width"=>200));  // Image shown here if page is update page
		     			echo '<br><center><a href="#" id="remove' . $key .'" onclick="removeElement(getElementById(\'image'.$key.'\'), \''. $image->id .'-'. $image->document_id .'-'. $key. '\');">Remove</a></center>';
		     			echo '</div>'; 
		     		}
		     ?>
		</div>
	<?php } ?>
	
	<div class="row buttons clear">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<!-- Below script is for removing an image-->
<script type="text/javascript">
/*<![CDATA[*/
function removeElement(ele, hele) {
	var hf = document.getElementById(hele);
	if (window.confirm("Delete file: " + hf.name + " " + hf.value))
	{	
		ele.parentNode.removeChild(ele);
		hf.value = "delete";
	}
}
/*]]>*/
</script>