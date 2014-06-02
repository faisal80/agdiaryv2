<?php
/* @var $this FilemovementController */
/* @var $model Filemovement */

$this->breadcrumbs=array(
	'Filemovements'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Filemovement', 'url'=>array('index')),
	array('label'=>'Create Filemovement', 'url'=>array('create')),
	array('label'=>'View Filemovement', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Filemovement', 'url'=>array('admin')),
);
?>

<h1>Update File Movement <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>