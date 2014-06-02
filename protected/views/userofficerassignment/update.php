<?php
/* @var $this UserofficerassignmentController */
/* @var $model UserOfficerAssignment */

$this->breadcrumbs=array(
	'User Officer Assignments'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List UserOfficerAssignment', 'url'=>array('index')),
	array('label'=>'Create UserOfficerAssignment', 'url'=>array('create')),
	array('label'=>'View UserOfficerAssignment', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage UserOfficerAssignment', 'url'=>array('admin')),
);
?>

<h1>Update UserOfficerAssignment <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>