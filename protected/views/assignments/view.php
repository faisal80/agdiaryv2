<?php
/* @var $this AssignmentsController */
/* @var $model Assignments */

$this->breadcrumbs=array(
	'Assignments'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Assignments', 'url'=>array('index')),
	array('label'=>'Create Assignment', 'url'=>array('create')),
	array('label'=>'Update Assignment', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Assignment', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Assignments', 'url'=>array('admin')),
);
?>

<h1>View Duty Assignment #<?php echo $model->id; ?></h1>

<?php MyFunctions::Navigation($model, $count, $this);  ?>
	
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'officer.officer_name',
		'designation.title',
		'duty.duty',
		array(
			'label'=>'State',
			'type'=>'raw',
			'value'=>($model->state==1) ?  'Active' : 'Inactive',
		),
		'wef',
	),
)); ?>
