<?php
/* @var $this DutyController */
/* @var $model Duty */

$this->breadcrumbs=array(
	'Duties'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Duties', 'url'=>array('index')),
	array('label'=>'Create Duties', 'url'=>array('create')),
	array('label'=>'Update Duties', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Duties', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Duties', 'url'=>array('admin')),
);
?>

<h1>View Duties #<?php echo $model->id; ?></h1>

<?php MyFunctions::Navigation($model, $count, $this);  ?>
	
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
				'label'=>'Parent Duty',
				'name'=>'parent_duty.duty',
		),
		'duty',
		'short_form',
		'info',
		'section.section_name',
	),
)); 

echo '<p/>';
//displays the officers assigned this duty
$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider' => $assignments,
	'summaryText'=>'<div align="left" style="font-weight: bold; font-size:20px; font-color:black;">Duty assigned to these officer:</div> Displaying {start}-{end} of {count} results.',
	'columns'=> array(
		array(
			'header'=>'Name of Officer',
			'name' =>'officer.officer_name',
		),
        array(
			'header'=>'Designation',
			'name' =>'designation.title',
		),
		'wef',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} {delete}',
			'updateButtonUrl'=> 'Yii::app()->createUrl("assignments/update", array("id"=>$data->id))',
			'deleteButtonUrl'=> 'Yii::app()->createUrl("assignments/delete", array("id"=>$data->id))',						
		),
	),
));
?>
