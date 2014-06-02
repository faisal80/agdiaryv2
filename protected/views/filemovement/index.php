<?php
/* @var $this FilemovementController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Filemovements',
);

$this->menu=array(
	array('label'=>'Create Filemovement', 'url'=>array('create')),
	array('label'=>'Manage Filemovement', 'url'=>array('admin')),
);
?>

<h1>File Movements</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
