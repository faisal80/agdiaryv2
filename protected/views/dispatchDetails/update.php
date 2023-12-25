<?php
/* @var $this DispatchDetailsController */
/* @var $model DispatchDetails */

$this->breadcrumbs=array(
	'Dispatch Details'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List DispatchDetails', 'url'=>array('index')),
	array('label'=>'Create DispatchDetails', 'url'=>array('create')),
	array('label'=>'View DispatchDetails', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage DispatchDetails', 'url'=>array('admin')),
);
?>

<h1>Update DispatchDetails <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>