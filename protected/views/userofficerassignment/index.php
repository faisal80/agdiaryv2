<?php
/* @var $this UserofficerassignmentController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'User Officer Assignments',
);

$this->menu=array(
	array('label'=>'Create UserOfficerAssignment', 'url'=>array('create')),
	array('label'=>'Manage UserOfficerAssignment', 'url'=>array('admin')),
);
?>

<h1>User Officer Assignments</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
