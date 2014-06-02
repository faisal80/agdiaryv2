<?php
/* @var $this OfficerdesigdutyassignmentController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Officer Desig Duty Assignments',
);

$this->menu=array(
	array('label'=>'Create OfficerDesigDutyAssignment', 'url'=>array('create')),
	array('label'=>'Manage OfficerDesigDutyAssignment', 'url'=>array('admin')),
);
?>

<h1>Officer Desig Duty Assignments</h1>



<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
