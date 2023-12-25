<?php
/* @var $this DispatchDetailsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Dispatch Details',
);

$this->menu=array(
	array('label'=>'Create DispatchDetails', 'url'=>array('create')),
	array('label'=>'Manage DispatchDetails', 'url'=>array('admin')),
);
?>

<h1>Dispatch Details</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
