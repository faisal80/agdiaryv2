<?php
/* @var $this DesignationController */
/* @var $model Designation */

$this->breadcrumbs=array(
	'Designations'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Designations', 'url'=>array('index')),
	array('label'=>'Manage Designations', 'url'=>array('admin')),
);
?>

<h1>Create Designations</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>