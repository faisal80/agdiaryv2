<?php
/* @var $this OfficerdesigdutyassignmentController */
/* @var $model OfficerDesigDutyAssignment */

$this->breadcrumbs=array(
	'Officer Desig Duty Assignments'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List OfficerDesigDutyAssignment', 'url'=>array('index')),
	array('label'=>'Manage OfficerDesigDutyAssignment', 'url'=>array('admin')),
);
?>

<h1>Create OfficerDesigDutyAssignment</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>