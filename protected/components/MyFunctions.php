<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MyFunctions
 * This class contains common functions
 * @author faisal
 */
class MyFunctions {
	//put your code here
	
	public static function Navigation($model, $count, $controller)
	{
		//Displays First | Previous | Next | Last links
		echo '<p align="right">';
		if ($model->id > 1)
		{
			echo CHtml::link ('First', $controller->createUrl('view', array('id'=>'1'))) . ' | ';
			echo CHtml::link('Previous', $controller->createUrl('view', array('id'=>($model->id - 1))));
		} else {
			echo 'First | Previous | ';
		}
		if ($model->id < $count)
		{
			echo ($model->id > 1)? ' | ' : '';
			echo CHtml::link('Next', $controller->createUrl('view', array('id'=>($model->id + 1))));
			echo ' | ' . CHtml::link('Last', $controller->createUrl('view', array('id'=>$count)));
		} else {
			echo ' | Next | Last';
		}
		echo '</p>';
	}
}

?>
