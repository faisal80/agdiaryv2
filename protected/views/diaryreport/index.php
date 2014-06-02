<h1>Diary Report</h1>
<?php 

$this->breadcrumbs=array(
	'Diary Report',
);

	$this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider' => $dataProvider,
		'columns' => array(
				array(
					'type'=>'html',
					'header' => 'Officer',
					'name' => 'officer_title',
				),
				array(
					'type'=>'html',
					'header'=>'More than 3 days',
					'name'=>'more_than_3_days',
				),
				array(
					'type'=>'html',
					'header'=>'More than 5 days',
					'name'=>'more_than_5_days',
					'htmlOptions'=>array('class'=>'green-alert'),
				),
				array(
					'type'=>'html',
					'header'=>'More than 7 days',
					'name'=>'more_than_7_days',
					'htmlOptions'=>array('class'=>'orange-alert'),
				),
				array(
					'type'=>'html',
					'header'=>'More than 10 days',
					'name'=>'more_than_10_days',
					'htmlOptions'=>array('class'=>'red-alert'),
				),
			),
		)
	);
	Yii::app()->clientScript->registerCoreScript('jquery.ui');
	Yii::app()->clientScript->registerScript(
		'FlashingRed',
		'function flash() {
			$(".red-alert").effect("pulsate", {times: 1}, 1000);
			setTimeout(flash, 2000);
			return false;
			}
		flash();'
	);
?>