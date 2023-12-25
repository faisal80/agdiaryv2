<?php
/* @var $this DisposalController */
/* @var $model Disposal */

$this->breadcrumbs=array(
	'Disposals'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Back', 'url'=>array('/document/view', 'id'=>$model->document_id)),
);
?>

<h1>Create Disposal for Document # <?php echo $model->document_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>