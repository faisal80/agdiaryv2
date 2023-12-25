<?php
/* @var $this OfficerController */
/* @var $model Officer */

$this->breadcrumbs=array(
	'Officers'=>array('index'),
	$model->officer_name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Officers', 'url'=>array('index')),
	array('label'=>'Create Officers', 'url'=>array('create')),
	array('label'=>'View Officers', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Officers', 'url'=>array('admin')),
);
?>

<h1>Update Officers <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>