<?php
/* @var $this SiteController */
/* @var $tbrdispatch CArrayDataProvider */

$this->pageTitle = Yii::app()->name;
$this->breadcrumbs = array('Dispatch Documents to Receive');
?>

<h3>Dispatch Documents needs to be received.</h3>
<?php

$this->widget(
	'zii.widgets.grid.CGridView',
	array(
		'dataProvider' => $tbrdispatch,
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
				'template' => '{receive}',
				'buttons' => array(
					'receive' => array(
						'label' => 'Receive',
						'url' => 'Yii::app()->createUrl("/dispatchDocs/receive", array("id"=>$data["id"]));',
					),
				)
			),
		),
	)
);
?>