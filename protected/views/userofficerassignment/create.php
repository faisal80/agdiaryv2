<?php
/* @var $this UserofficerassignmentController */
/* @var $model UserOfficerAssignment */

$this->breadcrumbs=array(
	'User Officer Assignments'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List UserOfficerAssignment', 'url'=>array('index')),
	array('label'=>'Manage UserOfficerAssignment', 'url'=>array('admin')),
);
?>

<h1>Create UserOfficerAssignment</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>