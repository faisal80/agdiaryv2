<?php
/* @var $this OfficerdesigdutyassignmentController */
/* @var $model OfficerDesigDutyAssignment */

$this->breadcrumbs=array(
	'Officer Desig Duty Assignments'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List OfficerDesigDutyAssignment', 'url'=>array('index')),
	array('label'=>'Create OfficerDesigDutyAssignment', 'url'=>array('create')),
	array('label'=>'Update OfficerDesigDutyAssignment', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete OfficerDesigDutyAssignment', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage OfficerDesigDutyAssignment', 'url'=>array('admin')),
);
?>

<h1>View Duty Assignment #<?php echo $model->id; ?></h1>

<?php MyFunctions::Navigation($model, $count, $this);  ?>
	
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'officer.name',
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
