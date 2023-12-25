<h2>Pending Dispatch Documents</h2>
<?php

$this->widget(
	'zii.widgets.grid.CGridView',
	array(
		'dataProvider' => $dataProvider,
		'columns' => array(
			array(
				'type' => 'html',
				'header' => 'ID',
				'name' => 'id',
			),
			array(
				'type' => 'html',
				'header' => 'Reference Number',
				'name' => 'reference_number',
				// 'htmlOptions' => array('class' => 'center'),
			),
			array(
				'type' => 'html',
				'header' => 'Dated',
				'name' => 'reference_dated',
				// 'htmlOptions' => array('class' => 'green-alert center'),
			),
			array(
				'type' => 'html',
				'header' => 'Description',
				'name' => 'description',
				// 'htmlOptions' => array('class' => 'orange-alert center'),
			),
			// array(
			// 	'type' => 'html',
			// 	'header' => 'Signed by',
			// 	'value' => 'Assignments::model()->find($data["signed_by"])->shortAssignment',
			// 	// 'htmlOptions' => array('class' => 'red-alert center'),
			// ),
			array(
				'type' => 'html',
				'header' => 'Addressed to',
				'name' => 'addressed_to',
				// 'htmlOptions' => array('class' => 'red-alert center'),
			),
            array(
                // 'type'=>'html',
                'header' => 'Pending Since',
                'value' => 'date_diff(date_create($data["create_time"]), date_create())->format("%a Days");',
				'htmlOptions' => array('class' => 'red-alert center'),
            ),
            array(
                'class' => 'CButtonColumn',
				'headerHtmlOptions' => array('class'=>'no-print'),
				'htmlOptions' => array('class'=>'no-print'),
                'template' => '{view}',
                'buttons' => array(
                    'view' => array(
                        'url' => 'Yii::app()->createUrl("/dispatchDocs/view", array("id"=>$data["id"]))',
                    )
                )
            )
		),
	)
);