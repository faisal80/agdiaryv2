<?php
/* @var $this UserController */
/* @var $data User */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fullname')); ?>:</b>
	<?php echo CHtml::encode($data->fullname); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('designation')); ?>:</b>
	<?php echo CHtml::encode($data->designation); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_type')); ?>:</b>
	<?php echo CHtml::encode($data->user_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_format')); ?>:</b>
	<?php echo CHtml::encode($data->date_format); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('can_create_doc')); ?>:</b>
	<?php echo CHtml::encode($data->can_create_doc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('can_create_blank_doc')); ?>:</b>
	<?php echo CHtml::encode($data->can_create_blank_doc); ?>
	<br />

</div>