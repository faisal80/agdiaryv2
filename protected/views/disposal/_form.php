<?php
/* @var $this DisposalController */
/* @var $model Disposal */
/* @var $form CActiveForm */
?>

<div class="form">

	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'disposal-form',
		'enableAjaxValidation' => false,
		'htmlOptions' => array('enctype' => 'multipart/form-data'),
	)
	); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'officer_id'); ?>
		<?php echo $form->dropDownList($model, 'officer_id', User::model()->findByPk(Yii::app()->user->id)->getListofOfficers()); ?>
		<?php echo $form->error($model, 'officer_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'disposal_type'); ?>
		<?php echo $form->dropDownList($model, 'disposal_type', array(
			'reply' => 'Reply',
			'circular' => 'Circular',
			'notification' => 'Notification',
			'intimation' => 'Intimation',
			'filed' => 'Filed',
			'letter' => 'Letter',
			'other' => 'Other',
		), array('onChange' => 'viewHideElements(this.value);'));
		?>
		<?php echo $form->error($model, 'disposal_type'); ?>
	</div>

	<div class="row forReply forCircular forNotification forIntimation forLetter">
		<?php echo $form->labelEx($model, 'disposal_ref_number'); ?>
		<?php echo $form->textField($model, 'disposal_ref_number', array('size' => 60, 'maxlength' => 255)); ?>
		<?php echo $form->error($model, 'disposal_ref_number'); ?>
	</div>

	<div class="row forReply forCircular forNotification forIntimation forLetter">
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
			),
		)
		);
		?>
		<?php echo $form->error($model, 'reference_dated'); ?>
	</div>

	<div class="row forFiled">
		<?php echo $form->labelEx($model, 'file_id'); ?>
		<?php echo $form->textField($model, 'file_id', array('size' => 10, 'maxlength' => 10)); ?>
		<?php echo $form->error($model, 'file_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'ref_file_page'); ?>
		<?php echo $form->textField($model, 'ref_file_page', array('size' => 10, 'maxlength' => 10)); ?>
		<?php echo $form->error($model, 'ref_file_page'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'noting_paras'); ?>
		<?php echo $form->textField($model, 'noting_paras', array('size' => 10, 'maxlength' => 10)); ?>
		<?php echo $form->error($model, 'noting_paras'); ?>
	</div>

	<div class="row forLetter">
		<?php echo $form->labelEx($model, 'is_reminder'); ?>
		<?php echo $form->dropDownList($model, 'is_reminder', array(false => 'No', true => 'Yes')); ?>
		<?php echo $form->error($model, 'is_reminder'); ?>
	</div>

	<div class="row forOther">
		<?php echo $form->labelEx($model, 'disposal'); ?>
		<?php echo $form->textField($model, 'disposal', array('size' => 60, 'maxlength' => 255)); ?>
		<?php echo $form->error($model, 'disposal'); ?>
	</div>

	<div class="row">
		<?php
		$this->widget('CMultiFileUpload', array(
			'name' => 'image',
			//'model'=>$model,
			//'attribute'=>'files',
			'accept' => 'jpeg|jpg|gif|png',
			'duplicate' => 'File already selected!',
			'denied' => 'Invalid file type. Please select JPG/JPEG/GIF/PNG files only.',
		)
		);
		?>
	</div>

	<?php if ($model->isNewRecord != '1') { ?>
		<div class="row">
			<?php foreach ($model->images as $key => $image) {
				echo '<div class="span-6" id="image' . $key . '">';
				echo $form->hiddenField($model, '', array('name' => $image->id . '-' . $image->disposal_id . '-' . $key, 'value' => ''));
				echo CHtml::image(Yii::app()->baseUrl . '/disposal_images/' . $image->image_path, $image->image_path, array("width" => 200)); // Image shown here if page is update page
				if (!$image->id)
					echo '<br><center><a href="#" id="remove' . $key . '" onclick="removeElement(getElementById(\'image' . $key . '\'), \'' . $image->id . '-' . $image->document_id . '-' . $key . '\');">Remove</a></center>';
				echo '</div>';
			}
			?>
		</div>
	<?php } ?>
	<hr>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?><br>
		<div class="wide form">
			<?php echo $form->checkBox($model, 'isDisposed'); ?> Case Disposed.
		</div>

	</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->
<?php
Yii::app()->clientScript->registerScript(
	'disposal_type',
	'
function viewHideElements(val){
	if (val == "reply" || val == "circular" || val == "notification" || val == "intimation") 
	{ 
		$(".forFiled").hide();  
		$(".forLetter").hide();
		$(".forOther").hide();  
		$(".forReply").show();							
	} 
	else if (val == "filed")
	{
		$(".forReply").hide();
		$(".forCircular").hide();
		$(".forNotification").hide();
		$(".forIntimation").hide();
		$(".forLetter").hide();
		$(".forOther").hide();
		$(".forFiled").show();  
	}
	else if (val == "letter") 
	{
		$(".forReply").hide();
		$(".forCircular").hide();
		$(".forNotification").hide();
		$(".forIntimation").hide();
		$(".forFiled").hide();
		$(".forOther").hide();
		$(".forLetter").show();  
	}
	else if (val == "other") 
	{
		$(".forReply").hide();
		$(".forCircular").hide();
		$(".forNotification").hide();
		$(".forIntimation").hide();
		$(".forFiled").hide();
		$(".forLetter").hide();
		$(".forOther").show();  
	}
}',
	CClientScript::POS_HEAD
);

Yii::app()->clientScript->registerScript(
	'disposal_ready',
	'
	viewHideElements($("#Disposal_disposal_type").val());
	',
	CClientScript::POS_READY
);
?>

<!-- Below script is for removing an image-->
<script type="text/javascript">
	/*<![CDATA[*/
	function removeElement(ele, hele) {
		var hf = document.getElementById(hele);
		if (window.confirm("Delete file: " + hf.name + " " + hf.value)) {
			ele.parentNode.removeChild(ele);
			hf.value = "delete";
		}
	}
/*]]>*/
</script>