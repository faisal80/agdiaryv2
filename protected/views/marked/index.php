<?php
/* @var $this MarkedController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Markeds',
);

$this->menu=array(
	array('label'=>'Create Marked', 'url'=>array('create')),
	array('label'=>'Manage Marked', 'url'=>array('admin')),
);
?>

<h1>Markeds</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
