<?php
/* @var $this DisposalController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Disposals',
);

$this->menu=array(
	array('label'=>'Create Disposal', 'url'=>array('create')),
	array('label'=>'Manage Disposal', 'url'=>array('admin')),
);
?>

<h1>Disposals</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
