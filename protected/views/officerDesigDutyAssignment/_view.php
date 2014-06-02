<?php
/* @var $this OfficerdesigdutyassignmentController */
/* @var $data OfficerDesigDutyAssignment */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('officer_id')); ?>:</b>
	<?php echo CHtml::encode($data->officer->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('designation_id')); ?>:</b>
	<?php echo CHtml::encode($data->designation->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('duty_id')); ?>:</b>
	<?php echo CHtml::encode($data->duty->duty); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('state')); ?>:</b>
	<?php echo ($data->state==1)? 'Active' : 'Inactive' ; ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wef')); ?>:</b>
	<?php echo CHtml::encode($data->wef); ?>
	<br />

</div>