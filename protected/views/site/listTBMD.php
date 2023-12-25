<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;
$this->breadcrumbs = array('Documents to be marked/disposed');
?>

<h3>Documents needs to be Marked/Disposed.</h3>
<?php
//displays the duties assigned to this officer
$this->widget(
	'zii.widgets.grid.CGridView',
	array(
		'dataProvider' => $tbmd,
		'htmlOptions' => array('style' => 'padding-top:0'),
		'columns' => array(
			'document_id:Doc ID',
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
				'header' => 'Actions',
				'class' => 'CButtonColumn',
				'template' => '{view} {mark}',
				// 'updateButtonUrl' => 'Yii::app()->createUrl("marked/update", array("id"=>$data["id"]))',
				// 'deleteButtonUrl' => 'Yii::app()->createUrl("marked/delete", array("id"=>$data["id"]))',
				'buttons' => array(
					'mark' => array(
						'label' => 'Mark',
						'url' => 'Yii::app()->createUrl("/marked/create", array("docid"=>$data["document_id"]));',
					),
					'view' => array(
						'label' => 'View',
						'url' => 'Yii::app()->createUrl("/document/view", array("id"=>$data["document_id"]));',
					)
				)
			),
		),
	)
);
?>