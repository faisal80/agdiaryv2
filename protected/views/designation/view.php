<?php
/* @var $this DesignationController */
/* @var $model Designation */

$this->breadcrumbs=array(
	'Designations'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Designations', 'url'=>array('index')),
	array('label'=>'Create Designations', 'url'=>array('create')),
	array('label'=>'Update Designations', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Designations', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Designations', 'url'=>array('admin')),
);
?>

<h1>View Designations #<?php echo $model->id; ?></h1>

<?php MyFunctions::Navigation($model, $count, $this);  ?>
	
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'short_form',
		'bps',
		'level',
	),
)); ?>
