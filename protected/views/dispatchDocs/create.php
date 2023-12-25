<?php
/* @var $this DispatchDocsController */
/* @var $model DispatchDocs */

$this->breadcrumbs=array(
	'Dispatch Docs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List DispatchDocs', 'url'=>array('index')),
	array('label'=>'Manage DispatchDocs', 'url'=>array('admin')),
);
?>

<h1>Create DispatchDocs</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>