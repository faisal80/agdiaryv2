<?php
/* @var $this DutyController */
/* @var $data Duty */
?>

<div id="view">

	<b><?php
		echo '<div><ul id="duties">';
		$this->dutiesforChart($data);
		echo '</ul></div>';
		
		Yii::app()->clientScript->registerScript('printdialog','
			 $("#duties").orgChart({container: $("#view")});
		');		
	
	?>
	<br />

</div>