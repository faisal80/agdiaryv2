<?php
/* @var $this DispatchDocsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Dispatch Docs',
);

$this->menu=array(
	array('label'=>'Create DispatchDocs', 'url'=>array('create')),
	array('label'=>'Manage DispatchDocs', 'url'=>array('admin')),
);
?>

<h1>Dispatch Docs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
