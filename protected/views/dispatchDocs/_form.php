<?php
/* @var $this DispatchDocsController */
/* @var $model DispatchDocs */
/* @var $form CActiveForm */
?>

<div class="form">

	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'dispatch-docs-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// There is a call to performAjaxValidation() commented in generated controller code.
		// See class documentation of CActiveForm for details on this.
		'enableAjaxValidation' => false,
		'htmlOptions' => array('enctype' => 'multipart/form-data'),	
	)
	); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'reference_number'); ?>
		<?php echo $form->textField($model, 'reference_number', array('size' => 60, 'maxlength' => 255)); ?>
		<?php echo $form->error($model, 'reference_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'reference_dated'); ?>
		<?php
		$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'model' => $model,
			'attribute' => 'reference_dated',
			'value' => $model->reference_dated,
			'options' => array(
				'showAnim' => 'fold',
				'dateFormat' => Yii::app()->user->getDateFormat(true),
				'changeMonth' => true,
				'changeYear' => true,
				'showOn' => 'both',
				'buttonText' => '...',
			),
			'htmlOptions' => array(
				'style' => 'height:20px;',
				'disabled' => $model->reference_dated ? true : false,
			),
		)
		);
		?>
		<?php echo $form->error($model, 'reference_dated'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'description'); ?>
		<?php echo $form->textArea($model, 'description', array('rows' => 6, 'cols' => 50)); ?>
		<?php echo $form->error($model, 'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'signed_by'); ?>
		<?php echo $form->dropDownList($model, 'signed_by', User::model()->findByPk(Yii::app()->user->id)->getListofOfficers()); ?>
		<?php echo $form->error($model, 'signed_by'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'addressed_to'); ?>
		<?php echo $form->textArea($model, 'addressed_to', array('rows' => 6, 'cols' => 50)); ?>
		<?php echo $form->error($model, 'addressed_to'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'copy_to'); ?>
		<?php echo $form->textArea($model, 'copy_to', array('rows' => 6, 'cols' => 50)); ?>
		<?php echo $form->error($model, 'copy_to'); ?>
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
		     			echo $form->hiddenField($model, '', array('name'=>$image->id .'-'. $image->disp_doc_id .'-'. $key, 'value'=>''));
		     			echo CHtml::image(Yii::app()->baseUrl.'/dispatch_doc_images/'.$image->image_path ,$image->image_path,array("width"=>200));  // Image shown here if page is update page
						if (!$image->id)
		     				echo '<br><center><a href="#" id="remove' . $key .'" onclick="removeElement(getElementById(\'image'.$key.'\'), \''. $image->id .'-'. $image->disp_doc_id .'-'. $key. '\');">Remove</a></center>';
		     			echo '</div>'; 
		     		}
		     ?>
		</div>
	<?php } ?>

	<div class="row buttons">
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