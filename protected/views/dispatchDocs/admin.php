<?php
/* @var $this DispatchDocsController */
/* @var $model DispatchDocs */

$this->breadcrumbs=array(
	'Dispatch Docs'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List DispatchDocs', 'url'=>array('index')),
	array('label'=>'Create DispatchDocs', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#dispatch-docs-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Dispatch Docs</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'dispatch-docs-grid',
	'dataProvider'=>$model->search(),
	// 'filter'=>$model,
	'columns'=>array(
		'id',
		'reference_number',
		'reference_dated',
		'description',
		array(
			'header' => 'Signed by',
			'name' => 'duty.assigned_to.shortAssignment',
		),
		// 'document_id:text:Doc ID',
	
		'addressed_to',
		'copy_to',
		'dispatchStatus',
		array(
			'class'=>'CButtonColumn',
			'template' => '{view} {update}',
		),
	),
)); ?>
