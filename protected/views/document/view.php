<?php
/* @var $this DocumentController */
/* @var $model Document */

$this->breadcrumbs = array(
	'Documents' => array('index'),
	$model->id,
);

$has_attachement = is_array($model->images) && isset($model->images[0]);
$has_disposal_attachment = $disposals->getItemCount() && !empty($disposals->getData()[0]->images);

$this->menu = array(
	//array('label'=>'List Documents', 'url'=>array('index')),
	array('label' => 'Create Document', 'url' => array('create'), 'visible' => Yii::app()->user->canCreateDoc()),
	array('label' => 'Update Document', 'url' => array('update', 'id' => $model->id), 'visible' => Yii::app()->user->canMark($model)),
	// array('label' => 'Delete Document', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?'), 'visible' => Yii::app()->user->isOwner($model)),
	array('label' => 'Mark Document', 'url' => array('/marked/create', 'docid' => $model->id), 'visible' => Yii::app()->user->canMark($model)),
	array('label' => 'Dispose Off', 'url' => array('/disposal/create', 'docid' => $model->id), 'visible' => Yii::app()->user->canDispose($model)),
	array('label' => 'List/Manage Documents', 'url' => array('admin')),
	$has_attachement ? array('label' => 'View Document', 'url' => '#', 'linkOptions' => array('onclick' => '$("#attachment_dialog").dialog("open"); return false;', 'visible' => Yii::app()->user->canView($model))) : array(),
	array('label' => 'Receive', 'url' => array('/marked/receive', 'm_id' => (!empty($model->LastMarking())) ? $model->LastMarking()->id : ''), 'visible' => ((!empty($model->LastMarking()) ? !$model->LastMarking()->isReceived() : false) && Yii::app()->user->canMark($model))),
	$file ? array('label'=> 'File','url'=> array('/file/view', 'id'=>$file->id)): array(),
);
?>

<h1>Details of Document #
	<?php echo $model->id . ($model->isConfidential() ? ' <span style="color:red;">(Confidential/Secret)</span>' : ''); ?>
</h1>
<dfn class="large loud">
	<?php
	if ($model->is_case_disposed)
		echo 'Disposed in '. date_diff(date_create($model->disposals[0]->disposal_date), date_create())->format('%a days');
	else
		echo '<span class="red-alert">Pending from last '. date_diff(date_create($model->date_received), date_create())->format("%a Days") .'</span>';
	?>
</dfn>
<?php MyFunctions::Navigation($model, $count, $this); ?>

<?php $this->widget(
	'zii.widgets.CDetailView',
	array(
		'data' => $model,
		'attributes' => array(
			'id',
			'diary_number',
			'date_received',
			'reference_number',
			'date_of_document',
			'received_from',
			'description',
			//'owner_id',
			'doc_status',
			'is_case_disposed:boolean',
			'type_of_document',
			'detail_of_enclosure',
			'tags',
			'page_no_in_file',
			'created_by.username:text:Created By'
		),
	)
); ?>
<br />
<?php
//displays marking history of this document
$this->widget(
	'zii.widgets.grid.CGridView',
	array(
		'dataProvider' => $markings,
		'summaryCssClass' => 'summary-heading',
		'summaryText' => 'Marking History',
		'htmlOptions' => array('style' => 'padding-top:0'),
		'columns' => array(
			array(
				'header' => 'Marked to',
				'value' => '$data->getMarkedTo()',
			),
			'marked_date',
			array(
				'header' => 'Marked by',
				'value' => '$data->getMarkedBy()',
			),
			'time_limit',
			'received_by_user.username:Received by',
			'received_time_stamp',
			// array(
			// 	'header' => 'Actions',
			// 	'class' => 'CButtonColumn',
			// 	'template' => '{update} {delete}',
			// 	'updateButtonUrl' => 'Yii::app()->createUrl("marked/update", array("id"=>$data->id))',
			// 	'deleteButtonUrl' => 'Yii::app()->createUrl("marked/delete", array("id"=>$data->id))',
			// ),
		),
	)
);
?>

<?php $this->widget(
	'zii.widgets.grid.CGridView',
	array(
		'id' => 'disposal-grid',
		'dataProvider' => $disposals,
		// 'filter'=>$model->disposals,
		'summaryCssClass' => 'summary-heading',
		'summaryText' => 'Disposals',
		'columns' => array(
			// 'id',
			// 'document_id',
			array(
				'header' => 'Disposed by',
				'value' => '$data->officer->assigned_to->getFullAssignment(true)',
			),
			'disposal_date',
			'disposal_type',
			array(
				'name' => 'disposal_ref_number',
				// 'cssClassExpression' => '($data->disposal_type=="filed" ? "hide" : "")',
			),
			'reference_dated',
			// 'dispatcher_user_id',
			// 'out_date',
			array(
				'name' => 'file_id',
				// 'cssClassExpression' => '($data->disposal_type=="filed" ? "" : "hide")',
				// 'headerHtmlOptions' => array()
			),
			'ref_file_page',
			'noting_paras',
			'is_reminder:boolean',
			// 'owner_id',
			'disposal',
			array( 
				'class' => 'CButtonColumn',
				// 'header' => 'Actions',
				'htmlOptions' => array('style' => 'text-align:center;'),
				'template' => '{attachments} {dispatch}',
				'buttons' => array(
					'attachments' => array(
						'label' => 'Attachement',
						'imageUrl' => Yii::app()->request->baseUrl . '/images/attach.png',
						'click' => "function(){
                                    $.fn.yiiGridView.update('disposal-grid', {
                                        type:'POST',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                              $('#disposal_attachment_dialog').html(data).dialog('open');
                                              $.fn.yiiGridView.update('disposal-grid');
                                        }
                                    });
                                    return false;
                                }",
						'visible' => 'is_array($data->images) && isset($data->images[0]);',
						'url' => 'Yii::app()->createUrl("disposal/imagesajax",array("id"=>$data->id));',
					),
					'dispatch' => array(
						'label' => 'Dispatch',
						'url' => 'Yii::app()->createUrl("dispatchDocs/create", array("disposal_id"=>$data->id));',
						'visible' => '!(is_array($data->dispatch_docs) && isset($data->dispatch_docs[0]));',
					),
				),
			),
		),
	)
); ?>

<?php
if (!empty($dispatch->totalItemCount)) {
	$this->widget(
		'zii.widgets.grid.CGridView',
		array(
			'id' => 'dispatch-grid',
			'dataProvider' => $dispatch,
			// 'filter'=>$model->disposals,
			'summaryCssClass' => 'summary-heading',
			'summaryText' => 'Dispatch Documents',
			'columns' => array(
				'reference_number',
				'reference_dated',
				'description',
				array(
					'header' => 'Signed by',
					'name' => 'duty.assigned_to.shortAssignment',
				),

				'addressed_to',
				'copy_to',
				array(
					'header' => 'Received by',
					'name' => 'dispatcher.username',
					// 'value' => '$data->dispatcher->username'
				),
				array(
					'header' => 'Dispatch Status',
					'value' => '$data->dispatchStatus'
				),
				array(
					'class' => 'CButtonColumn',
					// 'header' => 'Actions',
					'htmlOptions' => array('style' => 'text-align:center;'),
					'template' => '{attachments} {dispatchDetail}',
					'buttons' => array(
						'attachments' => array(
							'label' => 'Attachement',
							'imageUrl' => Yii::app()->request->baseUrl . '/images/attach.png',
							'click' => "function(){
				                            $.fn.yiiGridView.update('dispatch-grid', {
				                                type:'POST',
				                                url:$(this).attr('href'),
				                                success:function(data) {
				                                      $('#dispatch_attachment_dialog').html(data).dialog('open');
				                                      $.fn.yiiGridView.update('dispatch-grid');
				                                }
				                            });
				                            return false;
				                        }",
							'visible' => 'is_array($data->images) && isset($data->images[0]);',
							'url' => 'Yii::app()->createUrl("dispatchDocs/imagesajax",array("id"=>$data->id));',
						),
						'dispatchDetail' => array(
							'label' => 'Dispatch Detail',
							'visible' => '$data->dispatchStatus === "Dispatched"',
							'url' => 'Yii::app()->createUrl("dispatchDocs/view", array("id"=>$data->dispatch_details[0]->disp_doc_id));',
						),
					),
				),
			),
		)
	);
} ?>

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

	$this->renderPartial('_documentimages', $model);

	$this->endWidget('zii.widgets.jui.CJuiDialog');

}

if ($has_disposal_attachment) {
	$this->beginWidget(
		'zii.widgets.jui.CJuiDialog',
		array(
			'id' => 'disposal_attachment_dialog',
			// additional javascript options for the dialog plugin
			'options' => array(
				'title' => 'Disposal Attachments',
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
						'click' => 'js:function(){$("#disposal_attachment_dialog").printthis();}'
					),
					array(
						'text' => 'Close',
						'click' => 'js:function(){$(this).dialog("close");}'
					),
				),
			),
		)
	);

	// $this->renderPartial('/disposal/_disposalimages', $model);

	$this->endWidget('zii.widgets.jui.CJuiDialog');
}

if (!empty($dispatch->totalItemCount)) {
	$this->beginWidget(
		'zii.widgets.jui.CJuiDialog',
		array(
			'id' => 'dispatch_attachment_dialog',
			// additional javascript options for the dialog plugin
			'options' => array(
				'title' => 'Dispatch Documents Attachments',
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
						'click' => 'js:function(){$("#dispatch_attachment_dialog").printthis();}'
					),
					array(
						'text' => 'Close',
						'click' => 'js:function(){$(this).dialog("close");}'
					),
				),
			),
		)
	);

	// $this->renderPartial('/disposal/_disposalimages', $model);

	$this->endWidget('zii.widgets.jui.CJuiDialog');
}

// Register printThis jQuery Plugin for printing the contents of jquery dialog
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/printthis.js');


?>