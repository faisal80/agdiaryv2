<?php
/* @var $this DisposalController */
/* @var $model Disposal */

$this->breadcrumbs=array(
	'Disposals'=>array('index'),
	$model->id,
);

$has_attachement = is_array($model->images) && isset($model->images[0]);

$this->menu=array(
	array('label'=>'List Disposal', 'url'=>array('index')),
	array('label'=>'Create Disposal', 'url'=>array('create')),
	array('label'=>'Update Disposal', 'url'=>array('update', 'id'=>$model->id)),
	// array('label'=>'Delete Disposal', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Disposal', 'url'=>array('admin')),
	$has_attachement ? array('label' => 'View Attachments', 'url' => '#', 'linkOptions' => array('onclick' => '$("#attachment_dialog").dialog("open"); return false;')) : array(),
);
?>

<h1>View Disposal #<?php echo $model->id; ?></h1>

<?php MyFunctions::Navigation($model, $count, $this);  ?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'document_id',
		array(
			'label' => 'Officer',
			'value' => $model->officer->assigned_to->getFullAssignment(),
		),
		'disposal_date',
		'disposal_type',
		'disposal_ref_number',
		'reference_dated',
		'dispatcher_user_id',
		'out_date',
		'ref_file_page',
		'is_reminder:boolean',
		'owner_id',
		'disposal',
	),
)); ?>

<?php

if ($has_attachement) {
	$this->beginWidget(
		'zii.widgets.jui.CJuiDialog',
		array(
			'id' => 'attachment_dialog',
			// additional javascript options for the dialog plugin
			'options' => array(
				'title' => 'Attachments',
				'autoOpen' => false,
				'maxWidth' => 900,
				'minWidth' => 500,
				'maxHeight' => 600,
				'minHeight' => 200,
				'width' => 935,
				'height' => 600,
				'modal' => true,
				'closeOnEscape' => true,
				'buttons' => array(
					array(
						'text' => 'Print',
						'click' => 'js:function(){$("#attachment_dialog").printthis();}'
					),
					array(
						'text' => 'Close',
						'click' => 'js:function(){$(this).dialog("close");}'
					),
				),
			),
		)
	);

	$this->renderPartial('_disposalimages', $model);

	$this->endWidget('zii.widgets.jui.CJuiDialog');


	// Register printThis jQuery Plugin for printing the contents of jquery dialog
	Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/printthis.js');

}

?>