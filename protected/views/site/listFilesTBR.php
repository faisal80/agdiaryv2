<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;
$this->breadcrumbs = array('Files to Receive');
?>

<h3>Files need to be received.</h3>
<?php
//displays the duties assigned to this officer
$this->widget(
	'zii.widgets.grid.CGridView',
	array(
		'dataProvider' => $filestbr,
		'htmlOptions' => array('style' => 'padding-top:0'),
		'columns' => array(
			'file_id:File ID',
			'number:File Number',
			// 'marked_by:Received From',
			'description:text:Description',
			array(
				'header' => 'Marking Date',
				'value'=>'MyFunctions::format_date($data["marked_date"]);',
			),
			array(
				'header' => 'Marked by',
				'value' => 'FileMovement::model()->findByPk($data["id"])->getMarkedBy(true);',
			),
			array(
				'header' => 'Actions',
				'class' => 'CButtonColumn',
				'template' => '{view} {receive}',
				// 'updateButtonUrl' => 'Yii::app()->createUrl("marked/update", array("id"=>$data["id"]))',
				// 'deleteButtonUrl' => 'Yii::app()->createUrl("marked/delete", array("id"=>$data["id"]))',
				'buttons' => array(
					'receive' => array(
						'label' => 'Receive',
						'url' => 'Yii::app()->createUrl("/fileMovement/receive", array("fm_id"=>$data["id"]));',
					),
					'view' => array(
						'label' => 'View',
						'url' => 'Yii::app()->createUrl("/file/view", array("id"=>$data["file_id"]));',
					)
				)
			),
		),
	)
);
?>