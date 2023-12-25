<?php
/* @var $this DutyController */
/* @var $model Duty */

$this->breadcrumbs=array(
	'Duties'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Duties', 'url'=>array('index')),
	array('label'=>'Manage Duties', 'url'=>array('admin')),
);
?>

<h1>Create Duties</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>