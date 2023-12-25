<?php
/* @var $this DutyController */
/* @var $data Duty */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('duty_id')); ?>:</b>
	<?php echo CHtml::encode($data->duty_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('duty')); ?>:</b>
	<?php echo CHtml::encode($data->duty); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('short_form')); ?>:</b>
	<?php echo CHtml::encode($data->short_form); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('info')); ?>:</b>
	<?php echo CHtml::encode($data->info); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('section_id')); ?>:</b>
	<?php echo CHtml::encode($data->section->name); ?>
	<br />

</div>