<?php
/* @var $this SiteController */
/* @var $tbdispatch CArrayDataProvider */

$this->pageTitle = Yii::app()->name;
$this->breadcrumbs = array('Documents to Dispatch');
?>

<h3>Documents needs to be dispatched.</h3>
<?php

$this->widget(
	'zii.widgets.grid.CGridView',
	array(
		'dataProvider' => $tbdispatch,
		'htmlOptions' => array('style' => 'padding-top:0'),
		'columns' => array(
			'id:number:ID',
			'reference_number:text:Reference Number',
			'reference_dated:date:Dated',
			'description:text:Description',
			array(
				'header' => 'Signed by',
				'value' => 'Assignments::model()->find("duty_id=".$data["signed_by"])->shortAssignment',
			),
			'addressed_to:text:Addressed to',
			'copy_to:text:Copy to',
			array(
				'header' => 'Actions',
				'class' => 'CButtonColumn',
				'template' => '{view} {dispatch}',
				'buttons' => array(
					'view' => array(
						'label' => 'View',
						'url' => 'Yii::app()->createUrl("/dispatchDocs/view", array("id"=>$data["id"]));',
					),
					'dispatch' => array(
						'label' => 'Dispatch',
						'url' => 'Yii::app()->createUrl("/dispatchDetails/create", array("disp_doc_id"=>$data["id"]));'
					)
				)
			),
		),
	)
);
?>