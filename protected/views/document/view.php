<?php
/* @var $this DocumentController */
/* @var $model Document */

$this->breadcrumbs=array(
	'Documents'=>array('index'),
	$model->id,
);

$this->menu=array(
	//array('label'=>'List Documents', 'url'=>array('index')),
	array('label'=>'Create Document', 'url'=>array('create'),  'visible'=>  Yii::app()->user->canCreateDoc()),
	array('label'=>'Update Document', 'url'=>array('update', 'id'=>$model->id), 'visible'=>  Yii::app()->user->isOwner($model)),
	array('label'=>'Delete Document', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?'), 'visible'=>  Yii::app()->user->isOwner($model)),
	array('label'=>'Mark Document', 'url'=>array('/marked/create', 'docid'=>$model->id), 'visible'=>Yii::app()->user->canMark($model)),
	array('label'=>'Dispose Off', 'url'=>array('/disposal/create', 'docid'=>$model->id), 'visible'=>Yii::app()->user->canDispose($model)),
	array('label'=>'List/Manage Documents', 'url'=>array('admin')),
	isset($model->images[0]) ? array('label'=>'View Document', 'url'=>'#', 'linkOptions'=>array('onclick'=>'$("#attachment_dialog").dialog("open"); return false;', 'visible'=>  Yii::app()->user->canView($model))): '',		
);
?>

<h1>Details of Document #<?php echo $model->id . ($model->isConfidential()? ' <span style="color:red;">(Confidential/Secret)</span>': ''); ?></h1>

<?php MyFunctions::Navigation($model, $count, $this);  ?>
	
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'diary_number',
		'date_received',
		'reference_number',
		'date_of_document',
		'received_from',
		'description',
		//'owner_id',
		'doc_status',
		'is_case_disposed',
		'type_of_document',
		'detail_of_enclosure',
		'tags',
	),
)); ?>
<br>
<h3>Marking history</h3>
<?php
//displays the duties assigned to this officer
$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider' => $markings,
	'htmlOptions'=>array('style'=>'padding-top:0'),
	'columns'=> array(
			array(
					'header'=>'Marked to',
					'value'=>'$data->getMarkedTo()',
			),
			'marked_date',
			array(
					'header'=>'Marked by',
					'value'=>'$data->getMarkedBy()',
			),
		array(
			'header'=>'Actions',
			'class'=>'CButtonColumn',
			'template'=>'{update} {delete}',
			'updateButtonUrl'=> 'Yii::app()->createUrl("marked/update", array("id"=>$data->id))',
			'deleteButtonUrl'=> 'Yii::app()->createUrl("marked/delete", array("id"=>$data->id))',						
		),
	),
));
?>

<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
	    'id'=>'attachment_dialog',
	    // additional javascript options for the dialog plugin
	    'options'=>array(
	    'title'=>'Attachments',
	    'autoOpen'=>false,
			'maxWidth'=>900,
			'minWidth'=>500,
			'maxHeight'=>600,
			'minHeight'=>200,
			'width'=>935,
			'height'=>600,
			'modal'=>true,
            'closeOnEscape'=>true,
            'buttons'=>array(
                array(
                  'text'=>'Print',
                  'click'=>'js:function(){$("#attachment_dialog").printthis();}'
                ),  
                array(
                  'text'=>'Close',
                  'click'=>'js:function(){$(this).dialog("close");}'
                ),
            ),
	    ),
	));
	
		$this->renderPartial('_documentimages', $model);	
		
	$this->endWidget('zii.widgets.jui.CJuiDialog');

    
		// Register printThis jQuery Plugin for printing the contents of jquery dialog
		Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/printthis.js');
?>
