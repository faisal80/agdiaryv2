<?php
/* @var $this DispatchDocsController */
/* @var $data DispatchDocs */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reference_number')); ?>:</b>
	<?php echo CHtml::encode($data->reference_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reference_dated')); ?>:</b>
	<?php echo CHtml::encode($data->reference_dated); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('signed_by')); ?>:</b>
	<?php echo CHtml::encode($data->signed_by); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('document_id')); ?>:</b>
	<?php echo CHtml::encode($data->document_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('addressed_to')); ?>:</b>
	<?php echo CHtml::encode($data->addressed_to); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('copy_to')); ?>:</b>
	<?php echo CHtml::encode($data->copy_to); ?>
	<br />

	*/ ?>

</div>