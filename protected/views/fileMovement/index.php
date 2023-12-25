<?php
/* @var $this FileMovementController */
/* @var $dataProvider CActiveDataProvider */

// $this->breadcrumbs=array(
// 	'File Movements',
// );

$this->menu=array(
	array('label'=>'Create FileMovement', 'url'=>array('create')),
	array('label'=>'Manage FileMovement', 'url'=>array('admin')),
);
?>

<h1>File Movements</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
