<?php
/* @var $this AssignmentsController */
/* @var $model Assignments */

$this->breadcrumbs=array(
	'Assignments'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Assignments', 'url'=>array('index')),
	array('label'=>'Create Assignment', 'url'=>array('create')),
	array('label'=>'View Assignment', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Assignments', 'url'=>array('admin')),
);
?>

<h1>Update Assignments <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'excludeAssigned' => $excludeAssigned,
	)); ?>