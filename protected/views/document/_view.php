<?php
/* @var $this DocumentController */
/* @var $data Document */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('diary_number')); ?>:</b>
	<?php echo CHtml::encode($data->diary_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_received')); ?>:</b>
	<?php echo CHtml::encode($data->date_received); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reference_number')); ?>:</b>
	<?php echo CHtml::encode($data->reference_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_of_document')); ?>:</b>
	<?php echo CHtml::encode($data->date_of_document); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('received_from')); ?>:</b>
	<?php echo CHtml::encode($data->received_from); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('doc_status')); ?>:</b>
	<?php echo CHtml::encode($data->doc_status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_case_disposed')); ?>:</b>
	<?php echo CHtml::encode($data->is_case_disposed); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type_of_document')); ?>:</b>
	<?php echo CHtml::encode($data->type_of_document); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('detail_of_enclosure')); ?>:</b>
	<?php echo CHtml::encode($data->detail_of_enclosure); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tags')); ?>:</b>
	<?php echo CHtml::encode($data->tags); ?>
	<br />
	
</div>