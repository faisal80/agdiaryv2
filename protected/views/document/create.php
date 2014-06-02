<?php
/* @var $this DocumentController */
/* @var $model Document */

$this->breadcrumbs=array(
	'Documents'=>array('index'),
	'Create',
);

$this->menu=array(
	//array('label'=>'List Documents', 'url'=>array('index')),
	array('label'=>'List/Manage Documents', 'url'=>array('admin')),
);
?>

<h1>Create Document</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>