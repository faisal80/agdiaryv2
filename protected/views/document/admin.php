<?php
/* @var $this DocumentController */
/* @var $model Document */

$this->layout='//layouts/column1';

$this->breadcrumbs=array(
	'Documents'=>array('index'),
	'Manage',
);

$this->menu=array(
	//array('label'=>'List Documents', 'url'=>array('index')),
	array('label'=>'Create Documents', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#documents-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Documents</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'documents-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'rowCssClassExpression'=>'(!Yii::app()->user->canView($data))? "hide" : (($row%2)?"odd":"even")',
	'columns'=>array(
		'id',
		//'diary_number',
		'date_received',
		'reference_number',
		'date_of_document',
		'received_from',
		'description',
//		'tags',
//		'owner_id',
//		'doc_status',
//		'is_case_disposed',
//		'type_of_document',
//		'detail_of_enclosure',
		array(
			'class'=>'CButtonColumn',
			'header'=>'Actions',
			'htmlOptions'=>array('style'=>'width:64px;'),
			'template'=>'{attachments}{view}{update}{delete}',
			'buttons'=>array(
					'attachments'=>array(
						'label'=>'Attachement',
                        'imageUrl'=>Yii::app()->request->baseUrl.'/images/attach.png',
                        'click'=>"function(){
                                    $.fn.yiiGridView.update('documents-grid', {
                                        type:'POST',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                              $('#attachment_dialog').html(data).dialog('open');
                                               $.fn.yiiGridView.update('documents-grid');
                                        }
                                    })
                                    return false;
                              }
                     ",
                    'url'=>'Yii::app()->controller->createUrl("imagesajax",array("id"=>$data->id))',
					),
					'view'=>array(
						'imageUrl'=>Yii::app()->request->baseUrl.'/images/view.png',
					),
					'update'=>array(
						'imageUrl'=>Yii::app()->request->baseUrl.'/images/update.png',
						'visible'=>'Yii::app()->user->isOwner($data)',							
					),
					'delete'=>array(
						'imageUrl'=>Yii::app()->request->baseUrl.'/images/delete.png',
						'visible'=>'Yii::app()->user->isOwner($data)',
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
			'maxHeight'=>'auto',
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
	
	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>

<?php
		// Register printThis jQuery Plugin for printing the contents of jquery dialog
		Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/printthis.js');
			
//		Yii::app()->clientScript->registerScript('printdialog','
//			$(document).on("click", ".btnPrint", function() {
//				$("#attachment_dialog").printthis();
//			});
//		');
?>