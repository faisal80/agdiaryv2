<?php
/* @var $this UserofficerassignmentController */
/* @var $model UserOfficerAssignment */

$this->breadcrumbs=array(
	'User Officer Assignments'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List UserOfficerAssignment', 'url'=>array('index')),
	array('label'=>'Create UserOfficerAssignment', 'url'=>array('create')),
	array('label'=>'Update UserOfficerAssignment', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UserOfficerAssignment', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UserOfficerAssignment', 'url'=>array('admin')),
);
?>

<h1>View User Officer Assignment #<?php echo $model->id; ?></h1>

<?php MyFunctions::Navigation($model, $count, $this);  ?>
	
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'officer.officer_name',
		'user.username',
		'start_date:date',
		'end_date:date',
	),
)); ?>
