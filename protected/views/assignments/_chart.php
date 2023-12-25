<?php
/* @var $this AssignmentsController */
/* @var $data Assignments */
?>

<div id="chart-view">

	<b><?php
		echo '<ul id="orgChart" style="display:none;">';
		$this->orgChart($data);
//		var_dump($data);
		echo '</ul>';
		
		Yii::app()->clientScript->registerScript('printdialog','
			 $("#orgChart").orgChart({container: $("#chart-view")});
		');		
	
	?>
	<br />

</div>