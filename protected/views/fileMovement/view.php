<?php
/* @var $this FileMovementController */
/* @var $model FileMovement */

// $this->breadcrumbs=array(
// 	'File Movements'=>array('index'),
// 	$model->id,
// );

$this->menu=array(
	array('label'=>'List FileMovement', 'url'=>array('index')),
	array('label'=>'Create FileMovement', 'url'=>array('create')),
	array('label'=>'Update FileMovement', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete FileMovement', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage FileMovement', 'url'=>array('admin')),
);
?>

<h1>View FileMovement #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'document_id',
		'received_by',
		'marked_date',
		'marked_to',
		'marked_by',
		'file_id',
		'received_date',
		'create_time',
		'create_user',
		'update_time',
		'update_user',
	),
)); ?>
