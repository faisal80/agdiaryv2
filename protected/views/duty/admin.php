<?php
/* @var $this DutyController */
/* @var $model Duty */

$this->breadcrumbs=array(
	'Duties'=>array('index'),
	'Manage',
);

$this->menu=array(
	//array('label'=>'List Duties', 'url'=>array('index')),
	array('label'=>'Create Duties', 'url'=>array('create')),
	array('label'=>'Duties Chart', 'url'=>array('chart')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#duties-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Duties</h1>

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
	'id'=>'duties-grid',
	'dataProvider'=>$model->search(),
//	'filter'=>$model,
	'columns'=>array(
		'id',
		array(
				'header'=>'Parent Duty',
				'name'=>'parent_duty.duty',
		),
		'duty',
		'short_form',
		'info',
		'section.section_name',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
