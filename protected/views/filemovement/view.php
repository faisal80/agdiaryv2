<?php
/* @var $this FilemovementController */
/* @var $model Filemovement */

$this->breadcrumbs=array(
	'Filemovements'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Filemovement', 'url'=>array('index')),
	array('label'=>'Create Filemovement', 'url'=>array('create')),
	array('label'=>'Update Filemovement', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Filemovement', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Filemovement', 'url'=>array('admin')),
);
?>

<h1>View File Movement #<?php echo $model->id; ?></h1>

<?php MyFunctions::Navigation($model, $count, $this);  ?>
	
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'document_id',
		'received_by',
		'marked_to',
		'marked_by',
		'file_id',
		'marked_date',
		'received_date',
	),
)); ?>
