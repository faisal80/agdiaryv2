<?php
/* @var $this MarkedController */
/* @var $model Marked */

$this->breadcrumbs=array(
	'Markeds'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Back', 'url'=>array('/document/view', 'id'=>$model->document_id)),
);
?>

<h1>Mark document #<?php echo $model->document_id ?> to</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>