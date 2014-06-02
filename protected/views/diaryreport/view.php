<?php

$this->breadcrumbs=array(
	'Diary Report'=>array('diaryreport/pendency'),
	'Pending with ' . $officerTitle,
);

	echo '<h2>Pendency with ' . $officerTitle . '</h2>';
//	echo '<pre>';
//	print_r($dataProvider3);
//	echo '</pre>';
	if (!empty($dataProvider3->rawData))
	{
		echo '<h3><center>Outstanding from last 3 days </center></h3>';
		$this->widget('zii.widgets.grid.CGridView', array(
				'dataProvider' => $dataProvider3,
				'columns' => array(
					array(
						'header' => 'ID',
						'name' => 'id',
					),
					array(
						'header'=>'Diary Number',
						'name'=>'diary_number',
					),
					array(
						'header'=>'Reference Number',
						'name'=>'reference_number',
					),
					array(
						'header'=>'Date of Document',
						'name'=>'date_of_document',
					),
					array(
						'header'=>'Received From',
						'name'=>'received_from',
					),
					array(
						'header'=>'Description',
						'name'=>'description',
					),
					array(
						'header'=>'Marked Date',
						'name'=>'marked_date',
					),
					array(
						'header'=>'AG\'s Comments',
						'name'=>'comment',
						'type'=>'html',
					),
					array(
						'type'=>'html',
					    'header'=>'Actions',
						'name'=>'actions',
						'headerHtmlOptions'=>array('class'=>'no-print'),
						'htmlOptions'=>array('class'=>'no-print'),
					),				
				),
			)
		);
	}

//	echo '<pre>';
//	print_r($dataProvider5);
//	echo '</pre>';
	if (!empty($dataProvider5->rawData))
	{
		echo '<h3 style="color:GREEN;"><center>Outstanding from last 5 days </center></h3>';
		$this->widget('zii.widgets.grid.CGridView', array(
			'dataProvider' => $dataProvider5,
			'cssFile' => Yii::app()->request->baseUrl . '/css/gridview-green/styles-green.css',
			'filterCssClass' => 'filter-green',
			'itemsCssClass' => 'items-green',
			'loadingCssClass' => 'grid-view-loading-green',
			'rowCssClass' => array('odd-green', 'even-green'),
			'summaryCssClass' => 'summary-green',
			'columns' => array(
					array(
						'header' => 'ID',
						'name' => 'id',
					),
					array(
						'header'=>'Diary Number',
						'name'=>'diary_number',
					),
					array(
						'header'=>'Reference Number',
						'name'=>'reference_number',
					),
					array(
						'header'=>'Date of Document',
						'name'=>'date_of_document',
					),
					array(
						'header'=>'Received From',
						'name'=>'received_from',
					),
					array(
						'header'=>'Description',
						'name'=>'description',
					),
					array(
						'header'=>'Marked Date',
						'name'=>'marked_date',
					),
					array(
						'header'=>'AG\'s Comments',
						'name'=>'comment',
						'type'=>'html',
					),
					array(
						'type'=>'html',
					    'header'=>'Actions',
						'name'=>'actions',
						'headerHtmlOptions'=>array('class'=>'no-print'),
						'htmlOptions'=>array('class'=>'no-print'),						
					),						
				),
			)
		);
	}

	if (!empty($dataProvider7->rawData))
	{
		echo '<h3 style="color:#989E00;"><center>Outstanding for more than 7 days </center></h3>';
		$this->widget('zii.widgets.grid.CGridView', array(
			'dataProvider' => $dataProvider7,
			'enableSorting' => true,
			'cssFile' => Yii::app()->request->baseUrl . '/css/gridview-yellow/styles-yellow.css',
			'filterCssClass' => 'filter-yellow',
			'itemsCssClass' => 'items-yellow',
			'loadingCssClass' => 'grid-view-loading-yellow',
			'rowCssClass' => array('odd-yellow', 'even-yellow'),
			'summaryCssClass' => 'summary-yellow',
			'columns' => array(
					array(
						'header' => 'ID',
						'name' => 'id',
					),
					array(
						'header'=>'Diary Number',
						'name'=>'diary_number',
					),
					array(
						'header'=>'Reference Number',
						'name'=>'reference_number',
					),
					array(
						'header'=>'Date of Document',
						'name'=>'date_of_document',
					),
					array(
						'header'=>'Received From',
						'name'=>'received_from',
					),
					array(
						'header'=>'Description',
						'name'=>'description',
					),
					array(
						'header'=>'Marked Date',
						'name'=>'marked_date',
					),
					array(
						'header'=>'AG\'s Comments',
						'name'=>'comment',
						'type'=>'html',
					),
					array(
						'type'=>'html',
						'name'=>'actions',
						'header'=>'Actions',
						'headerHtmlOptions'=>array('class'=>'no-print'),
						'htmlOptions'=>array('class'=>'no-print'),					
					),
				),
			)
		);
	}

	if (!empty($dataProvider10->rawData))
	{
		echo '<h3 style="color:red;"><center>Outstanding for more than 10 days </center></h3>';
		$this->widget('zii.widgets.grid.CGridView', array(
			'dataProvider' => $dataProvider10,
			'enableSorting' => true,
			'cssFile' => Yii::app()->request->baseUrl . '/css/gridview-red/styles-red.css',
			'filterCssClass' => 'filter-red',
			'itemsCssClass' => 'items-red',
			'loadingCssClass' => 'grid-view-loading-red',
			'rowCssClass' => array('odd-red', 'even-red'),
			'summaryCssClass' => 'summary-red',
			'columns' => array(
					array(
						'header' => 'ID',
						'name' => 'id',
					),
					array(
						'header'=>'Diary Number',
						'name'=>'diary_number',
					),
					array(
						'header'=>'Reference Number',
						'name'=>'reference_number',
					),
					array(
						'header'=>'Date of Document',
						'name'=>'date_of_document',
					),
					array(
						'header'=>'Received From',
						'name'=>'received_from',
					),
					array(
						'header'=>'Description',
						'name'=>'description',
					),
					array(
						'header'=>'Marked Date',
						'name'=>'marked_date',
					),
					array(
						'header'=>'AG\'s Comments',
						'name'=>'comment',
						'type'=>'html',
					),
					array(
						'type'=>'html',
						'name'=>'actions',
						'header'=>'Actions',
						'headerHtmlOptions'=>array('class'=>'no-print'),
						'htmlOptions'=>array('class'=>'no-print'),				
					),
				),
			)
		);
	}
	
//$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
//    'id'=>'mydialog',
//    // additional javascript options for the dialog plugin
//    'options'=>array(
//        'title'=>'Dialog box 1',
//        'autoOpen'=>false,
//    ),
//));
//
//    echo 'dialog content here';
//
//$this->endWidget('zii.widgets.jui.CJuiDialog');
//
//// the link that may open the dialog
//echo CHtml::link('open dialog', '#', array(
//   'onclick'=>'$("#mydialog").dialog("open"); return false;',
//));
		
?>
