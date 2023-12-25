<?php
/* @var $this FileController */
/* @var $model File */
/* @var $file_movement */

$this->breadcrumbs=array(
	'Files'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Files', 'url'=>array('index'), 'visible'=>Yii::app()->user->isAdmin()),
	array('label'=>'Create File', 'url'=>array('create'), 'visible'=>Yii::app()->user->isAdmin()),
	array('label'=>'Update File', 'url'=>array('update', 'id'=>$model->id), 'visible'=>Yii::app()->user->isAdmin()),
	array('label'=>'Delete File', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?'), 'visible'=>Yii::app()->user->isAdmin()),
	array('label'=>'Manage File', 'url'=>array('admin'), 'visible'=>Yii::app()->user->isAdmin()),
	array('label'=>'Rack it', 'url'=> array('rackit', 'id'=>$model->id), 'visible'=> Yii::app()->user->isInitiatorOfFile($model) && Yii::app()->user->canMarkFile($model) ),
	array('label'=>'Movement Entry', 'url'=>array('/fileMovement/create', 'fileid'=> $model->id), 'visible'=>Yii::app()->user->canMarkFile($model)),
	array('label'=>'Receive', 'url' => array('/fileMovement/receive', 'fm_id' => (!empty($model->LastMarking())) ? $model->LastMarking()->id : ''), 'visible' => ((!empty($model->LastMarking()) ? !$model->LastMarking()->isReceived() : false) && Yii::app()->user->canMarkFile($model))),
	array('label'=>'Print', 'url'=> '#', 'linkOptions' => array('onclick'=>'window.print();'))
);
?>

<h1>View File No. <?php echo $model->number; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'section.section_name',
		'number',
		'description',
		'open_date',
		'close_date',
		'number_of_pages',
		'number_of_paras_on_note_part',
		// 'create_time',
		// 'created_by.username:text:Created by',
		// 'update_time',
		// 'updated_by.username:text:Updated by',
		'enroute:boolean',
	),
)); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'file-movement-grid',
	'dataProvider'=>$file_movement,
	'summaryText' => 'Movement History',
	'summaryCssClass' => 'summary-heading',
	// 'filter'=>$model,
	'columns'=>array(
		// 'id',
		array(
			'header' => 'Document ID',
			'type' => 'html',
			'value' => 'CHtml::link($data->document_id, array("document/view", "id"=>$data->document_id))',
			'htmlOptions'=> array('class'=>'center')
		),
		'marked_date',
		array(
			'header' => 'Marked to',
			'value' => '$data->getMarkedTo()'
		),
		array(
			'header' => 'Marked by',
			'value' => '$data->getMarkedBy()'
		),
		'received_by_user.username:text:Received by',
		'received_time_stamp',
		// 'movement_count',
		/*
		'file_id',
		'create_time',
		'create_user',
		'update_time',
		'update_user',
		*/
		// array(
		// 	'class'=>'CButtonColumn',
		// ),
	),
)); ?>