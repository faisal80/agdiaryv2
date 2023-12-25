<?php
/* @var $this DocumentController */
/* @var $model Document */

$this->layout='//layouts/column1';

$this->breadcrumbs=array(
	'Documents'=>array('index'),
	'Search',
);

$this->menu=array(
	//array('label'=>'List Document', 'url'=>array('index')),
	array('label'=>'Create Document', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#document-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Search Documents</h1>

<div class="search-form">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'document-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		'id',
		//'diary_number',
		'date_received',
		'reference_number',
		'date_of_document',
		'received_from',
		'description',
		'tags',
		//'owner_id',
//		'doc_status',
//		'is_case_disposed',
//		'type_of_document',
//		'detail_of_enclosure',
		array(
			'class'=>'CButtonColumn',
			'header'=>'Actions',
			'template'=>'{attachments}{view}{update}',
			'buttons'=>array(
					'attachments'=>array
					(
						'label'=>'Attachement',
            'imageUrl'=>Yii::app()->request->baseUrl.'/images/attach.png',
            'click'=>"function(){
                                    $.fn.yiiGridView.update('documents-grid', {
                                        type:'POST',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                              $('#attachment_dialog').html(data).dialog('open');
 
                                              $.fn.yiiGridView.update('document-grid');
                                        }
                                    })
                                    return false;
                              }
                     ",
            'url'=>'Yii::app()->controller->createUrl("imagesAjax",array("id"=>$data->id))',
					),
					'update'=>array(
						'visible'=>'Yii::app()->user->isOwner($data->id)',
					),
				),
			),
		),
	)); ?>

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
	    ),
	));
	
	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>

<?php
		// Register printThis jQuery Plugin for printing the contents of jquery dialog
		Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/printthis.js');
			
		Yii::app()->clientScript->registerScript('printdialog','
			$(document).on("click", ".btnPrint", function() {
				$("#attachment_dialog").printthis();
			});
		');
?>