<?php
$this->breadcrumbs=array(
	'Comments'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Back', 'url'=>$_SERVER['HTTP_REFERER']),
);
?>

<h1>Update Comments on Document # <?php echo $model->document_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>