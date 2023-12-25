<?php
/* @var $this DispatchDetailsController */
/* @var $model DispatchDetails */

$this->breadcrumbs=array(
	'Dispatch Details'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List DispatchDetails', 'url'=>array('index')),
	array('label'=>'Manage DispatchDetails', 'url'=>array('admin')),
);
?>

<h1>Create DispatchDetails</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>