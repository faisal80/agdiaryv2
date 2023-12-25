<?php
/* @var $this AssignmentsController */
/* @var $dataProvider CActiveDataProvider */

$this->layout='//layouts/chart';

// $this->breadcrumbs=array(
// 	'Organization Chart',
// );

Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/js/jquery-orgchart-master/jquery.orgchart.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery-orgchart-master/jquery.orgchart.min.js');
//Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/js/jOrgChart-master/css/jquery.jOrgChart.js.css');
//Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jOrgChart-master/jquery.jOrgChart.js');
?>

<h1 class="center">Organization Chart</h1>

<?php echo $this->renderPartial('_chart', array('data'=>$dataProvider)); ?>
