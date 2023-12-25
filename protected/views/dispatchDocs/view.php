<?php
/* @var $this DispatchDocsController */
/* @var $model DispatchDocs */

$this->breadcrumbs = array(
	'Dispatch Docs' => array('index'),
	$model->id,
);

$has_attachement = is_array($model->images) && isset($model->images[0]);

$this->menu = array(
	array('label' => 'List DispatchDocs', 'url' => array('index')),
	array('label' => 'Create DispatchDocs', 'url' => array('create')),
	array('label' => 'Update DispatchDocs', 'url' => array('update', 'id' => $model->id)),
	// array('label' => 'Delete DispatchDocs', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
	array('label' => 'Manage DispatchDocs', 'url' => array('admin')),
	$has_attachement ? array('label' => 'View Document', 'url' => '#', 'linkOptions' => array('onclick' => '$("#attachment_dialog").dialog("open"); return false;')) : array(),
);
?>

<h1>Detail of Dispatch Document #
	<?php echo $model->id; ?>
</h1>

<?php MyFunctions::Navigation($model, $model->count(), $this); ?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data' => $model,
	'attributes' => array(
		'id',
		'reference_number',
		'reference_dated',
		'description',
		array(
			'label' => 'Signed by',
			'value' => $model->duty->assigned_to->getFullAssignment(),
		),
		array(
			'label'=> 'Document ID',
			'type' => 'raw',
			'value'=> CHtml::link(CHtml::encode($model->document_id),
								  array('document/view', 'id'=>$model->document_id)),
		),
		'addressed_to',
		'copy_to',
		'dispatcher.username:text:Received by',
		'received_timestamp',
		'dispatchStatus'
	),
)
); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'dispatch-details-grid',
	'dataProvider' => $dispatch_details,
	// 'filter'=>$model,
	'columns' => array(
		'dispatch_date',
		'dispatch_through',
		'receipt_no',
		'receipt_date',
		array(
			'class' => 'CButtonColumn',
			// 'header' => 'Actions',
			'htmlOptions' => array('style' => 'text-align:center;'),
			'template' => '{attachments}',
			'buttons' => array(
				'attachments' => array(
					'label' => 'Attachement',
					'imageUrl' => Yii::app()->request->baseUrl . '/images/attach.png',
					'click' => "function(){
				                    $.fn.yiiGridView.update('dispatch-details-grid', {
				                        type:'POST',
				                        url:$(this).attr('href'),
				                        success:function(data) {
				                            $('#disp_det_dialog').html(data).dialog('open');
				                            $.fn.yiiGridView.update('disptch-details-grid');
				                        }
				                    });
				                    return false;
				                    }",
					'visible' => 'is_array($data->images) && isset($data->images[0]);',
					'url' => 'Yii::app()->createUrl("dispatchDetails/imagesajax",array("id"=>$data->id));',
				),
			)

		),
	),
)
); ?>

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

	$this->renderPartial('_dispdocimages', $model);

	$this->endWidget('zii.widgets.jui.CJuiDialog');
}
?>

<?php
if ($has_attachement) {
	$this->beginWidget(
		'zii.widgets.jui.CJuiDialog',
		array(
			'id' => 'disp_det_dialog',
			// additional javascript options for the dialog plugin
			'options' => array(
				'title' => 'Dispatch Details Attachments',
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
						'click' => 'js:function(){$("#disp_det_dialog").printthis();}'
					),
					array(
						'text' => 'Close',
						'click' => 'js:function(){$(this).dialog("close");}'
					),
				),
			),
		)
	);

	// $this->renderPartial('_dispdetimages', $model);

	$this->endWidget('zii.widgets.jui.CJuiDialog');
}

// Register printThis jQuery Plugin for printing the contents of jquery dialog
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/printthis.js');

// Yii::app()->clientScript->registerCss('p',
// 	'@page {size: portrait;}',
// 	'print'
// );
?>