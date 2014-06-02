<?php
/* @var $this FilemovementController */
/* @var $data Filemovement */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('document_id')); ?>:</b>
	<?php echo CHtml::encode($data->document_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('received_by')); ?>:</b>
	<?php echo CHtml::encode($data->received_by); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('marked_to')); ?>:</b>
	<?php echo CHtml::encode($data->marked_to); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('marked_by')); ?>:</b>
	<?php echo CHtml::encode($data->marked_by); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('file_id')); ?>:</b>
	<?php echo CHtml::encode($data->file_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('marked_date')); ?>:</b>
	<?php echo CHtml::encode($data->marked_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('received_date')); ?>:</b>
	<?php echo CHtml::encode($data->received_date); ?>
	<br />


</div>