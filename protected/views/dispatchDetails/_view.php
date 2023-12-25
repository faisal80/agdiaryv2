<?php
/* @var $this DispatchDetailsController */
/* @var $data DispatchDetails */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('disp_doc_id')); ?>:</b>
	<?php echo CHtml::encode($data->disp_doc_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dispatch_date')); ?>:</b>
	<?php echo CHtml::encode($data->dispatch_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dispatch_through')); ?>:</b>
	<?php echo CHtml::encode($data->dispatch_through); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('receipt_no')); ?>:</b>
	<?php echo CHtml::encode($data->receipt_no); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('receipt_date')); ?>:</b>
	<?php echo CHtml::encode($data->receipt_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo CHtml::encode($data->create_time); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('create_user')); ?>:</b>
	<?php echo CHtml::encode($data->create_user); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_time')); ?>:</b>
	<?php echo CHtml::encode($data->update_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_user')); ?>:</b>
	<?php echo CHtml::encode($data->update_user); ?>
	<br />

	*/ ?>

</div>