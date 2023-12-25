<?php
/* @var $this FileMovementController */
/* @var $model FileMovement */

// $this->breadcrumbs=array(
// 	'File Movements'=>array('index'),
// 	$model->id=>array('view','id'=>$model->id),
// 	'Update',
// );

$this->menu=array(
	array('label'=>'List FileMovement', 'url'=>array('index')),
	array('label'=>'Create FileMovement', 'url'=>array('create')),
	array('label'=>'View FileMovement', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage FileMovement', 'url'=>array('admin')),
);
?>

<h1>Update FileMovement <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>