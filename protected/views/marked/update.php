<?php
/* @var $this MarkedController */
/* @var $model Marked */

$this->breadcrumbs=array(
	'Markeds'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Marked', 'url'=>array('index')),
	array('label'=>'Create Marked', 'url'=>array('create')),
	array('label'=>'View Marked', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Marked', 'url'=>array('admin')),
);
?>

<h1>Update Marked <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>