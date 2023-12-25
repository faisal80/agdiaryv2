<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;
$this->breadcrumbs = array('Documents with Time Limit');
?>

<h3>Documents with Time Limit.</h3>
<?php
//displays the duties assigned to this officer
$this->widget(
	'zii.widgets.grid.CGridView',
	array(
		'dataProvider' => $timeLimitCases,
		'htmlOptions' => array('style' => 'padding-top:0'),
		'rowCssClassExpression' => '($data["time_limit"] < date_diff(date_create($data["marked_date"]), date_create())->format("%a")) ? "red-alert" : ""',
		'columns' => array(
			'id:Doc ID',
			'reference_number:Reference Number',
			'received_from:Received From',
			'description:text:Description',
			array(
				'header' => 'Marking Date',
				'value'=>'MyFunctions::format_date($data["marked_date"]);',
			),
			array(
				'header' => 'Marked to',
				'value' => 'Marked::model()->findByPk($data["id"])->getMarkedTo(true);',
			),
			array(
				'header' => 'Time Limit',
				'value' => '$data["time_limit"] . " days"',
				'htmlOptions' => array(
					'class' => 'red-alert',
				),
			), 
			array(
				'header' => 'Actions',
				'class' => 'CButtonColumn',
				'template' => '{view}',
				'buttons' => array(
					'view' => array(
						'label' => 'View',
						'url' => 'Yii::app()->createUrl("/document/view", array("id"=>$data["id"]));',
					)
				)
			),
		),
	)
);

?>