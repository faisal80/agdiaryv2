<?php
/* @var $this FileController */
/* @var $model File */

$this->breadcrumbs=array(
	'Files'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List File', 'url'=>array('index')),
	array('label'=>'Create File', 'url'=>array('create')),
	array('label'=>'Update File', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete File', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage File', 'url'=>array('admin')),
);
?>

<h1>View File #<?php echo $model->id; ?></h1>

<?php MyFunctions::Navigation($model, $count, $this);  ?>
	
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'section.name',
		'number',
		'description',
		'open_date',
		'close_date',
		'number_of_pages',
		'number_of_paras_on_note_part',
	),
)); ?>
