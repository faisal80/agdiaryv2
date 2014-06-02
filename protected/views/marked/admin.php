<?php
/* @var $this MarkedController */
/* @var $model Marked */

$this->breadcrumbs=array(
	'Markeds'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Marked', 'url'=>array('index')),
	array('label'=>'Create Marked', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#marked-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Markeds</h1>

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
	'id'=>'marked-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'document_id',
		'marked_to',
		'marked_by',
		'marked_date',
		'time_limit',
		/*
		'received_by',
		'received_time_stamp',
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
