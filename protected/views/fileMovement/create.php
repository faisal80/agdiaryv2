<?php
/* @var $this FileMovementController */
/* @var $model FileMovement */

// $this->breadcrumbs=array(
// 	'File Movements'=>array('index'),
// 	'Create',
// );

$this->menu=array(
	array('label'=>'Back', 'url'=>array('file/view', 'id'=>$model->file_id)),
	// array('label'=>'Manage FileMovement', 'url'=>array('admin')),
);
?>

<h1>Enter File Movement for <?php echo $file_number ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>