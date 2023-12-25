<?php
/* @var $this MarkedController */
/* @var $model Marked */

$this->breadcrumbs=array(
	'Markeds'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Marked', 'url'=>array('index')),
	array('label'=>'Create Marked', 'url'=>array('create')),
	array('label'=>'Update Marked', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Marked', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Marked', 'url'=>array('admin')),
);
?>

<h1>View Marked #<?php echo $model->id; ?></h1>

<?php MyFunctions::Navigation($model, $count, $this);  ?>
	
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'document_id',
		array(
			'label'=>'Marked to',
			'value'=>$model->getMarkedTo(),
		),
		array(
			'label'=>'Marked by',
			'value'=>$model->getMarkedBy(),
		),
		'marked_date',
		'time_limit',
		'received_by',
		'received_time_stamp',
	),
)); ?>
