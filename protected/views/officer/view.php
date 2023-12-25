<?php
/* @var $this OfficerController */
/* @var $model Officer */

$this->breadcrumbs=array(
	'Officers'=>array('index'),
	$model->officer_name,
);

if (Yii::app()->user->isAdmin()) {
$this->menu=array(
	array('label'=>'List Officers', 'url'=>array('index')),
	array('label'=>'Create Officers', 'url'=>array('create')),
	array('label'=>'Update Officers', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Officers', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Officers', 'url'=>array('admin')),
	array('label'=>'Assign Duty', 'url'=>array('/assignments/create', 'oid'=>$model->id)),
);
}
?>

<h1>View Officer #<?php echo $model->id; ?></h1>

<?php MyFunctions::Navigation($model, $count, $this);  ?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'officer_name',
	),
)); 

echo '<p/>';
//displays the duties assigned to this officer
$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider' => $assignments,
	'summaryText'=>'<div align="left" style="font-weight: bold; font-size:20px; font-color:black;">Duties assigned to this officer:</div> Displaying {start}-{end} of {count} results.',
	'columns'=> array(
		array(
			'header'=>'Designation',
			'name' =>'designation.title',
		),
		array(
			'header'=>'Duty',
			'name' =>'duty.fullDuty',
		),
		'wef',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} {delete}',
			'visible' => Yii::app()->user->isAdmin(),
			'updateButtonUrl'=> 'Yii::app()->createUrl("assignments/update", array("id"=>$data->id))',
			'deleteButtonUrl'=> 'Yii::app()->createUrl("assignments/delete", array("id"=>$data->id))',						
		),
	),
));

?>
