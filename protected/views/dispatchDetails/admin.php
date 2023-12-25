<?php
/* @var $this DispatchDetailsController */
/* @var $model DispatchDetails */

$this->breadcrumbs=array(
	'Dispatch Details'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List DispatchDetails', 'url'=>array('index')),
	array('label'=>'Create DispatchDetails', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#dispatch-details-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Dispatch Details</h1>

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
	'id'=>'dispatch-details-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'disp_doc_id',
		'dispatch_date',
		'dispatch_through',
		'receipt_no',
		'receipt_date',
		/*
		'create_time',
		'create_user',
		'update_time',
		'update_user',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
