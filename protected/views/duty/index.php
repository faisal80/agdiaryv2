<?php
/* @var $this DutyController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Duties',
);

$this->menu=array(
	array('label'=>'Create Duties', 'url'=>array('create')),
	array('label'=>'Manage Duties', 'url'=>array('admin')),
);
?>

<h1>Duties</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
