<?php
/* @var $this OfficerController */
/* @var $data Officer */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('officer_name')); ?>:</b>
	<?php echo CHtml::encode($data->officer_name); ?>
	<br />

</div>