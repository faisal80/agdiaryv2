<?php

echo '<h2>Pendening Files with ' . $officerTitle . '</h2>';
if (!empty($dataProviderlt3->rawData)) {
	$this->widget(
		'zii.widgets.grid.CGridView',
		array(
			'dataProvider' => $dataProviderlt3,
			'summaryCssClass' => 'summary summary-heading',
			'summaryText' => 'Fresh Files',
			'columns' => array(
				array(
					'header' => 'ID',
					'name' => 'id',
				),
				array(
					'header' => 'File Number',
					'name' => 'number',
				),
				array(
					'header' => 'Description',
					'name' => 'description',
				),
				array(
					'header' => 'Section',
					'name' => 'section.section_name',
				),
				array(
					'header' => 'marked_by',
					'value' => '(!empty($data->marked_id)) ? FileMovement::model()->findByPk($data["marked_id"])->getMarkedBy() : null',
				),
				array(
					'header' => 'Marked Date',
					'value' => '(!empty($data->marked_id)) ? FileMovement::model()->findByPk($data["marked_id"])->marked_date : null',
				),
				array(
					'header' => 'Received on',
					'value' => '(!empty($data->marked_id)) ? FileMovement::model()->findByPk($data["marked_id"])->received_time_stamp : null',
				),
				array(
					'class' => 'CButtonColumn',
					'htmlOptions' => array('class' => 'center no-print'),
					'headerHtmlOptions' => array('class' => 'no-print'),
					'template' => '{view}',
					'buttons' => array(
						'view' => array(
							'url' => 'Yii::app()->createUrl("/file/view", array("id"=>$data["id"]))',
						)
					),
				),
			),
		)
	);
}

if (!empty($dataProvider3->rawData)) {
	$this->widget(
		'zii.widgets.grid.CGridView',
		array(
			'dataProvider' => $dataProvider3,
			'summaryCssClass' => 'summary summary-heading',
			'summaryText' => 'Outstanding from last 3 days',
			'columns' => array(
				array(
					'header' => 'ID',
					'name' => 'id',
				),
				array(
					'header' => 'File Number',
					'name' => 'number',
				),
				array(
					'header' => 'Description',
					'name' => 'description',
				),
				array(
					'header' => 'Section',
					'name' => 'section.section_name',
				),
				array(
					'header' => 'marked_by',
					'value' => '(!empty($data->marked_id)) ? FileMovement::model()->findByPk($data["marked_id"])->getMarkedBy() : null',
				),
				array(
					'header' => 'Marked Date',
					'value' => '(!empty($data->marked_id)) ? FileMovement::model()->findByPk($data["marked_id"])->marked_date : null',
				),
				array(
					'header' => 'Received on',
					'value' => '(!empty($data->marked_id)) ? FileMovement::model()->findByPk($data["marked_id"])->received_time_stamp : null',
				),
				array(
					'class' => 'CButtonColumn',
					'htmlOptions' => array('class' => 'center no-print'),
					'headerHtmlOptions' => array('class' => 'no-print'),
					'template' => '{view}',
					'buttons' => array(
						'view' => array(
							'url' => 'Yii::app()->createUrl("/file/view", array("id"=>$data["id"]))',
						)
					),
				),
			),
		)
	);
}

if (!empty($dataProvider5->rawData)) {
	$this->widget(
		'zii.widgets.grid.CGridView',
		array(
			'dataProvider' => $dataProvider5,
			'cssFile' => Yii::app()->request->baseUrl . '/css/gridview-green/styles-green.css',
			'filterCssClass' => 'filter-green',
			'itemsCssClass' => 'items-green',
			'loadingCssClass' => 'grid-view-loading-green',
			'rowCssClass' => array('odd-green', 'even-green'),
			'summaryCssClass' => 'summary-green summary-heading',
			'summaryText' => 'Outstanding from last 5 days',
			'columns' => array(
				array(
					'header' => 'ID',
					'name' => 'id',
				),
				array(
					'header' => 'File Number',
					'name' => 'number',
				),
				array(
					'header' => 'Description',
					'name' => 'description',
				),
				array(
					'header' => 'Section',
					'name' => 'section.section_name',
				),
				array(
					'header' => 'marked_by',
					'value' => '(!empty($data->marked_id)) ? FileMovement::model()->findByPk($data["marked_id"])->getMarkedBy() : null',
				),
				array(
					'header' => 'Marked Date',
					'value' => '(!empty($data->marked_id)) ? FileMovement::model()->findByPk($data["marked_id"])->marked_date : null',
				),
				array(
					'header' => 'Received on',
					'value' => '(!empty($data->marked_id)) ? FileMovement::model()->findByPk($data["marked_id"])->received_time_stamp : null',
				),
				array(
					'class' => 'CButtonColumn',
					'htmlOptions' => array('class' => 'center no-print'),
					'headerHtmlOptions' => array('class' => 'no-print'),
					'template' => '{view}',
					'buttons' => array(
						'view' => array(
							'url' => 'Yii::app()->createUrl("/file/view", array("id"=>$data["id"]))',
						)
					),
				),
			),
		)
	);
}

if (!empty($dataProvider7->rawData)) {
	$this->widget(
		'zii.widgets.grid.CGridView',
		array(
			'dataProvider' => $dataProvider7,
			'enableSorting' => true,
			'cssFile' => Yii::app()->request->baseUrl . '/css/gridview-yellow/styles-yellow.css',
			'filterCssClass' => 'filter-yellow',
			'itemsCssClass' => 'items-yellow',
			'loadingCssClass' => 'grid-view-loading-yellow',
			'rowCssClass' => array('odd-yellow', 'even-yellow'),
			'summaryCssClass' => 'summary-yellow summary-heading',
			'summaryText' => 'Outstanding for more than last 7 days',
			'columns' => array(
				array(
					'header' => 'ID',
					'name' => 'id',
				),
				array(
					'header' => 'File Number',
					'name' => 'number',
				),
				array(
					'header' => 'Description',
					'name' => 'description',
				),
				array(
					'header' => 'Section',
					'name' => 'section.section_name',
				),
				array(
					'header' => 'marked_by',
					'value' => '(!empty($data->marked_id)) ? FileMovement::model()->findByPk($data["marked_id"])->getMarkedBy() : null',
				),
				array(
					'header' => 'Marked Date',
					'value' => '(!empty($data->marked_id)) ? FileMovement::model()->findByPk($data["marked_id"])->marked_date : null',
				),
				array(
					'header' => 'Received on',
					'value' => '(!empty($data->marked_id)) ? FileMovement::model()->findByPk($data["marked_id"])->received_time_stamp : null',
				),
				array(
					'class' => 'CButtonColumn',
					'htmlOptions' => array('class' => 'center no-print'),
					'headerHtmlOptions' => array('class' => 'no-print'),
					'template' => '{view}',
					'buttons' => array(
						'view' => array(
							'url' => 'Yii::app()->createUrl("/file/view", array("id"=>$data["id"]))',
						)
					),
				),
			),
		)
	);
}

if (!empty($dataProvider10->rawData)) {
	$this->widget(
		'zii.widgets.grid.CGridView',
		array(
			'dataProvider' => $dataProvider10,
			'enableSorting' => true,
			'cssFile' => Yii::app()->request->baseUrl . '/css/gridview-red/styles-red.css',
			'filterCssClass' => 'filter-red',
			'itemsCssClass' => 'items-red',
			'loadingCssClass' => 'grid-view-loading-red',
			'rowCssClass' => array('odd-red', 'even-red'),
			'summaryCssClass' => 'summary-red summary-heading',
			'summaryText' => 'Outstanding for more than 10 days',
			'columns' => array(
				array(
					'header' => 'ID',
					'name' => 'id',
				),
				array(
					'header' => 'File Number',
					'name' => 'number',
				),
				array(
					'header' => 'Description',
					'name' => 'description',
				),
				array(
					'header' => 'Section',
					'name' => 'section.section_name',
				),
				array(
					'header' => 'marked_by',
					'value' => '(!empty($data->marked_id)) ? FileMovement::model()->findByPk($data["marked_id"])->getMarkedBy() : null',
				),
				array(
					'header' => 'Marked Date',
					'value' => '(!empty($data->marked_id)) ? FileMovement::model()->findByPk($data["marked_id"])->marked_date : null',
				),
				array(
					'header' => 'Received on',
					'value' => '(!empty($data->marked_id)) ? FileMovement::model()->findByPk($data["marked_id"])->received_time_stamp : null',
				),
				array(
					'class' => 'CButtonColumn',
					'htmlOptions' => array('class' => 'center no-print'),
					'headerHtmlOptions' => array('class' => 'no-print'),
					'template' => '{view}',
					'buttons' => array(
						'view' => array(
							'url' => 'Yii::app()->createUrl("/file/view", array("id"=>$data["id"]))',
						)
					),
				),
			),
		)
	);
}

?>