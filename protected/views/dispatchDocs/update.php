<?php
/* @var $this DispatchDocsController */
/* @var $model DispatchDocs */

$this->breadcrumbs=array(
	'Dispatch Docs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List DispatchDocs', 'url'=>array('index')),
	array('label'=>'Create DispatchDocs', 'url'=>array('create')),
	array('label'=>'View DispatchDocs', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage DispatchDocs', 'url'=>array('admin')),
);
?>

<h1>Update DispatchDocs <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>