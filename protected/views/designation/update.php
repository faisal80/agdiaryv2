<?php
/* @var $this DesignationController */
/* @var $model Designation */

$this->breadcrumbs=array(
	'Designations'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Designations', 'url'=>array('index')),
	array('label'=>'Create Designations', 'url'=>array('create')),
	array('label'=>'View Designations', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Designations', 'url'=>array('admin')),
);
?>

<h1>Update Designations <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>