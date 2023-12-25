<?php
/* @var $this DutyController */
/* @var $model Duty */

$this->breadcrumbs=array(
	'Duties'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Duties', 'url'=>array('index')),
	array('label'=>'Create Duties', 'url'=>array('create')),
	array('label'=>'View Duties', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Duties', 'url'=>array('admin')),
);
?>

<h1>Update Duties <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>