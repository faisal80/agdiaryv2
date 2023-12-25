<h2>Un-attended Documents </h2>
<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    //'summaryCssClass' => 'summary summary-heading',
    //'summaryText' => 'Outstanding from last 3 days',
    'columns' => array(
        array(
            'header' => 'ID',
            'name' => 'id',
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
            'header' => 'Created by',
            'value' => 'User::model()->findByPk($data["create_user"])->username',
        ),
        array(
            'class'=>'CButtonColumn',
            'header'=>'Actions',
            'template' => '{view}',
            'headerHtmlOptions'=>array('class'=>'no-print'),
            'htmlOptions'=>array('class'=>'no-print'),
            'buttons' => array(
                'view' => array(
                    'url' => 'Yii::app()->createUrl("/document/view", array("id"=>$data["id"]))'
                )
            )
        ),				
    ),
)
);