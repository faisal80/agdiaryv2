<?php
/* @var $this DispatchDetailsController */
/* @var $model DispatchDetails */

$this->breadcrumbs=array(
	'Dispatch Details'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List DispatchDetails', 'url'=>array('index')),
	array('label'=>'Create DispatchDetails', 'url'=>array('create')),
	array('label'=>'Update DispatchDetails', 'url'=>array('update', 'id'=>$model->id)),
	// array('label'=>'Delete DispatchDetails', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage DispatchDetails', 'url'=>array('admin')),
);
?>

<h1>View DispatchDetails #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'disp_doc_id',
		'dispatch_date',
		'dispatch_through',
		'receipt_no',
		'receipt_date',
		'create_time',
		'create_user',
		'update_time',
		'update_user',
	),
)); ?>
