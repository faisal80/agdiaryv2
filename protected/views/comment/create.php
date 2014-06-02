<?php
$this->breadcrumbs=array(
	'Comments'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Back', 'url'=>$_SERVER['HTTP_REFERER']),
);
?>

<h1>Create Comments on Document # <?php echo $model->document_id?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>