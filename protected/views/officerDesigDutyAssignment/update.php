<?php
/* @var $this OfficerdesigdutyassignmentController */
/* @var $model OfficerDesigDutyAssignment */

$this->breadcrumbs=array(
	'Officer Desig Duty Assignments'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List OfficerDesigDutyAssignment', 'url'=>array('index')),
	array('label'=>'Create OfficerDesigDutyAssignment', 'url'=>array('create')),
	array('label'=>'View OfficerDesigDutyAssignment', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage OfficerDesigDutyAssignment', 'url'=>array('admin')),
);
?>

<h1>Update OfficerDesigDutyAssignment <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>