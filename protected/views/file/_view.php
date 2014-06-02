<?php
/* @var $this FileController */
/* @var $data File */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('section_id')); ?>:</b>
	<?php echo CHtml::encode($data->section->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('number')); ?>:</b>
	<?php echo CHtml::encode($data->number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('open_date')); ?>:</b>
	<?php echo CHtml::encode($data->open_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('close_date')); ?>:</b>
	<?php echo CHtml::encode($data->close_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('number_of_pages')); ?>:</b>
	<?php echo CHtml::encode($data->number_of_pages); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('number_of_paras_on_note_part')); ?>:</b>
	<?php echo CHtml::encode($data->number_of_paras_on_note_part); ?>
	<br />

</div>