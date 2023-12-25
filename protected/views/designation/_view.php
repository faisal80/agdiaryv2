<?php
/* @var $this DesignationController */
/* @var $data Designation */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('short_form')); ?>:</b>
	<?php echo CHtml::encode($data->short_form); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bps')); ?>:</b>
	<?php echo CHtml::encode($data->bps); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('level')); ?>:</b>
	<?php echo CHtml::encode($data->level); ?>
	<br />

</div>