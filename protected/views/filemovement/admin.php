<?php
/* @var $this FilemovementController */
/* @var $model Filemovement */

$this->breadcrumbs=array(
	'Filemovements'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Filemovement', 'url'=>array('index')),
	array('label'=>'Create Filemovement', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#filemovement-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage File Movements</h1>

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
	'id'=>'filemovement-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'document_id',
		'received_by',
		'marked_to',
		'marked_by',
		'file_id',
		'marked_date',
		'received_date',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
