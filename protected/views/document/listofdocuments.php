<center><h2>Marked by <?php echo $officer_title ?> </h2></center>
<?php
// if(count($dataProvider->rawData)>0 && !empty($dataProvider->rawData))
// {
	$this->widget('zii.widgets.grid.CGridView', array(
		//'id'=>'documents-grid',
		'dataProvider'=>$dataProvider,
		//'filter'=>$dataProvider,
		'columns'=>array(
			array(
				'type'=>'html',
				'header'=>'ID',
				'name'=>'id',
			),
			// array(
			// 	'type'=>'html',
			// 	'header'=>'Diary Number',
			// 	'name'=>'diary_number',
			// ),
			array(
				'type'=>'html',
				'header'=>'Date Received',
				'name'=>'date_received',
			),
			array(
				'type'=>'html',
				'header'=>'Reference Number',
				'name'=>'reference_number',
			),
			array(
				'type'=>'html',
				'header'=>'Date of Document',
				'name'=>'date_of_document',
			),
			array(
				'type'=>'html',
				'header'=>'Received From',
				'name'=>'received_from',
			),	
			array(
				'type'=>'html',
				'header'=>'Description',
				'name'=>'description',		
			),
			array(
				'type'=>'html',
				'header'=>'Marked to',
				'value'=>'Assignments::model()->find("duty_id=".$data["marked_to"])->shortAssignment',		
			),
			array(
				'type'=>'text',
				'header'=>'Sign',
                'name' => 'Sign'
			),
		),
	));
// }