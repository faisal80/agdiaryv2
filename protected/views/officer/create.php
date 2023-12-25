<?php
/* @var $this OfficerController */
/* @var $model Officer */

$this->breadcrumbs=array(
	'Officers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Officers', 'url'=>array('index')),
	array('label'=>'Manage Officers', 'url'=>array('admin')),
);
?>

<h1>Create Officers</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>