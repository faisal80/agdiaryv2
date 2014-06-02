<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'User'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Users', 'url'=>array('index')),
	array('label'=>'Create Users', 'url'=>array('create')),
	array('label'=>'Update Users', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Users', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Users', 'url'=>array('admin')),
);
?>

<h1>View Users #<?php echo $model->id; ?></h1>

<?php MyFunctions::Navigation($model, $count, $this);  ?>
	
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'fullname',
		'username',
		'user_type',
		'date_format',
		'can_create_doc',
	),
)); 
?>
<p/>
<?php
//displays the officers assigned to this user
$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider' => $officers,
	'summaryText'=>'<div align="left" style="font-weight: bold; font-size:20px; font-color:black;">Officers attached with this user:</div> Displaying {start}-{end} of {count} results.',
	'htmlOptions'=>array('style'=>'padding-top:0'),
	'columns'=> array(
		array(
			'header'=>'Name',
			'name' =>'officer.name',
		),
		array(
			'header'=>'Designation',
			'name'=>'officer.designation.title',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} {delete}',
			'updateButtonUrl'=> 'Yii::app()->createUrl("userofficerassignment/update", array("id"=>$data->id))',
			'deleteButtonUrl'=> 'Yii::app()->createUrl("userofficerassignment/delete", array("id"=>$data->id))',						
		),
	),
));
?>
