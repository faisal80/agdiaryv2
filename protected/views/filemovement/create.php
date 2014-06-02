<?php
/* @var $this FilemovementController */
/* @var $model Filemovement */

$this->breadcrumbs=array(
	'Filemovements'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Filemovement', 'url'=>array('index')),
	array('label'=>'Manage Filemovement', 'url'=>array('admin')),
);
?>

<h1>Create File Movement</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>