<?php
/* @var $this DisposalController */
/* @var $data Disposal */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('document_id')); ?>:</b>
	<?php echo CHtml::encode($data->document_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('officer_id')); ?>:</b>
	<?php echo CHtml::encode($data->officer_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('disposal_date')); ?>:</b>
	<?php echo CHtml::encode($data->disposal_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('disposal_type')); ?>:</b>
	<?php echo CHtml::encode($data->disposal_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('disposal_ref_number')); ?>:</b>
	<?php echo CHtml::encode($data->disposal_ref_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('reference_dated')); ?>:</b>
	<?php echo CHtml::encode($data->reference_dated); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dispatcher_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->dispatcher_user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('out_date')); ?>:</b>
	<?php echo CHtml::encode($data->out_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ref_file_page')); ?>:</b>
	<?php echo CHtml::encode($data->ref_file_page); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('filed_by')); ?>:</b>
	<?php echo CHtml::encode($data->filed_by); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_reminder')); ?>:</b>
	<?php echo CHtml::encode($data->is_reminder); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('owner_id')); ?>:</b>
	<?php echo CHtml::encode($data->owner_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('disposal')); ?>:</b>
	<?php echo CHtml::encode($data->disposal); ?>
	<br />


</div>