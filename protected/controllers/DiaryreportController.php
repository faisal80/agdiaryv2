<?php

class DiaryreportController extends Controller
{
	
	private $_report = null;
	public $_officer_id = null;
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','pview'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete', 'pendency'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionIndex()
	{
		//$dataProvider = array();
		$dp = $this->buildArray();
		if (!empty($dp))
		{
			$dataProvider = new CArrayDataProvider( 
	      		$dp, array(
	      			'pagination'=>array(
	        			'pageSize'=>20,
	      				),
	    			));
		} else {
			$dataProvider = new CArrayDataProvider(array());
		} 

	    //if ($dataProvider!==null)
	    	$this->render('index', array(
				'dataProvider'=>$dataProvider
			));
	}
	
	public function actionPendency()
	{
		//$dataProvider = array();
		$dp = $this->buildArrayByLastOfficer();
		if (!empty($dp))
		{
			$dataProvider = new CArrayDataProvider( 
	      		$dp, array(
	      			'pagination'=>array(
	        			'pageSize'=>50,
	      				),
	    			));
		} else {
			$dataProvider = new CArrayDataProvider(array());
		} 

	    //if ($dataProvider!==null)
	    	$this->render('index', array(
				'dataProvider'=>$dataProvider
			));
	}
	
	public function actionView($id)
	{
		$this->_officer_id=$id;
		
		$_user = User::model()->findByPk(Yii::app()->user->id);
		//Query for more than 3 days
		$_sql = "SELECT `tbl_documents`.`id`, 
						`tbl_documents`.`diary_number`, 
						`tbl_documents`.`reference_number`, 
						`tbl_documents`.`date_of_document`, 
						`tbl_documents`.`received_from`, 
						`tbl_documents`.`description`, 
						`tbl_marked`.`marked_date`
				FROM 
						`tbl_documents`
				JOIN 
						(`tbl_marked` 
						LEFT JOIN `tbl_disposal` ON `tbl_marked`.`document_id`=`tbl_disposal`.`document_id`) 
						ON `tbl_documents`.`id`=`tbl_marked`.`document_id`
				WHERE
						(`tbl_marked`.`officer_id`=". $id .") 
						AND 
						(`tbl_marked`.`marked_by`=" . $_user->getOfficerID() . ") 
						AND 
						(`tbl_marked`.`marked_date` 
							BETWEEN
								DATE_SUB(CURDATE(), INTERVAL 4 DAY) 
							AND 
								DATE_SUB(CURDATE(), INTERVAL 3 DAY)) 
						AND 
							(`tbl_disposal`.`document_id` IS NULL)
				ORDER BY
						`tbl_documents`.`id`";
		
		$_more_than_3_days = Yii::app()->db->createCommand($_sql)->queryAll();

		//Query for more than 5 days
		$_sql = "SELECT `tbl_documents`.`id`, 
						`tbl_documents`.`diary_number`, 
						`tbl_documents`.`reference_number`, 
						`tbl_documents`.`date_of_document`, 
						`tbl_documents`.`received_from`, 
						`tbl_documents`.`description`, 
						`tbl_marked`.`marked_date`
				FROM 
						`tbl_documents`
				JOIN 
						(`tbl_marked` 
						LEFT JOIN `tbl_disposal` ON `tbl_marked`.`document_id`=`tbl_disposal`.`document_id`) 
						ON `tbl_documents`.`id`=`tbl_marked`.`document_id`
				WHERE
						(`tbl_marked`.`officer_id`=". $id .") 
						AND 
						(`tbl_marked`.`marked_by`=" . $_user->getOfficerID() . ") 
						AND 
						(`tbl_marked`.`marked_date` 
							BETWEEN 
								DATE_SUB(CURDATE(), INTERVAL 6 DAY) 
							AND 
								DATE_SUB(CURDATE(), INTERVAL 5 DAY)) 
						AND 
							(`tbl_disposal`.`document_id` IS NULL)
				ORDER BY
						`tbl_documents`.`id`";
		
		$_more_than_5_days = Yii::app()->db->createCommand($_sql)->queryAll();

		//Query for more than 7 days
		$_sql = "SELECT `tbl_documents`.`id`, 
						`tbl_documents`.`diary_number`, 
						`tbl_documents`.`reference_number`, 
						`tbl_documents`.`date_of_document`, 
						`tbl_documents`.`received_from`, 
						`tbl_documents`.`description`, 
						`tbl_marked`.`marked_date`
				FROM 
						`tbl_documents`
				JOIN 
						(`tbl_marked` 
						LEFT JOIN `tbl_disposal` ON `tbl_marked`.`document_id`=`tbl_disposal`.`document_id`) 
						ON `tbl_documents`.`id`=`tbl_marked`.`document_id`
				WHERE
						(`tbl_marked`.`officer_id`=". $id .") 
					AND 
						(`tbl_marked`.`marked_by`=" . $_user->getOfficerID() . ") 
					AND 
						(`tbl_marked`.`marked_date`<= DATE_SUB(CURDATE(), INTERVAL 7 DAY)) 
					AND 
						(`tbl_disposal`.`document_id` IS NULL)
				ORDER BY
						`tbl_documents`.`id`";

		$_more_than_7_days = Yii::app()->db->createCommand($_sql)->queryAll();
		
		//$callbackfunc = $this->formatDate($item, $key);
		
		array_walk_recursive($_more_than_3_days, 'DiaryreportController::formatDate');
		array_walk_recursive($_more_than_5_days, 'DiaryreportController::formatDate');
		array_walk_recursive($_more_than_7_days, 'DiaryreportController::formatDate');

		$cnt = count($_more_than_3_days);
		
		for ($i=0;$i<$cnt;$i++)
		{
			$_more_than_3_days[$i]['comment']="<font color=red>" .(($_comments=Comment::model()->findByAttributes(array('document_id'=>$_more_than_3_days[$i]['id']))) ? $_comments->comment : '') . "</font><BR>" .
												CHtml::link('Create', $this->createUrl('/comments/create', array('id'=>$_more_than_3_days[$i]['id'])));
			if ($_comments)
				$_more_than_3_days[$i]['comment'] .= " | " . CHtml::link('Edit', $this->createUrl('/comments/update', array('id'=>$_comments->id))) . " | " .
												CHtml::link('Delete', $this->createUrl('/comments/delete', array('id'=>$_comments->id)));
			
			$_more_than_3_days[$i]['actions']=CHtml::link('View',$this->createUrl('/documents/view', array('id'=>$_more_than_3_days[$i]['id'])));
		}
		
		$cnt = count($_more_than_5_days);
		
		for ($i=0;$i<$cnt;$i++)
		{
			$_more_than_5_days[$i]['comment']="<font color=red>" .(($_comments=Comment::model()->findByAttributes(array('document_id'=>$_more_than_5_days[$i]['id']))) ? $_comments->comment : '') . "</font><BR>" .
												CHtml::link('Create', $this->createUrl('/comments/create', array('id'=>$_more_than_5_days[$i]['id'])));
			if ($_comments)
				$_more_than_5_days[$i]['comment'] .= " | " . CHtml::link('Edit', $this->createUrl('/comments/update', array('id'=>$_comments->id))) . " | " .
												CHtml::link('Delete', $this->createUrl('/comments/delete', array('id'=>$_comments->id)));
			
			$_more_than_5_days[$i]['actions']=CHtml::link('View',$this->createUrl('/documents/view', array('id'=>$_more_than_5_days[$i]['id'])));
		}

		$cnt = count($_more_than_7_days);
		
		for ($i=0;$i<$cnt;$i++)
		{
			$_more_than_7_days[$i]['comment']="<font color=red>" .(($_comments=Comment::model()->findByAttributes(array('document_id'=>$_more_than_7_days[$i]['id']))) ? $_comments->comment : '') . "</font><BR>" .
												CHtml::link('Create', $this->createUrl('/comments/create', array('id'=>$_more_than_7_days[$i]['id'])));
			if ($_comments)
				$_more_than_7_days[$i]['comment'] .= " | " . CHtml::link('Edit', $this->createUrl('/comments/update', array('id'=>$_comments->id))) . " | " .
												CHtml::link('Delete', $this->createUrl('/comments/delete', array('id'=>$_comments->id)));
			
			$_more_than_7_days[$i]['actions']=CHtml::link('View',$this->createUrl('/documents/view', array('id'=>$_more_than_7_days[$i]['id']))); 
		}
		
		$dataProvider3 = new CArrayDataProvider( 
      		$_more_than_3_days, array(
      			'pagination'=>array(
        			'pageSize'=>50,
      				),
    			)); 

    	$dataProvider5 = new CArrayDataProvider( 
      		$_more_than_5_days, array(
      			'pagination'=>array(
        			'pageSize'=>50,
      				),
    			)); 

    	$dataProvider7 = new CArrayDataProvider( 
      		$_more_than_7_days, array(
      			'pagination'=>array(
        			'pageSize'=>50,
      				),
    			)); 
    			
		$this->render('view', array(
			'dataProvider3'=>$dataProvider3,
			'dataProvider5'=>$dataProvider5,
			'dataProvider7'=>$dataProvider7,
			'officerTitle'=>Officer::model()->getOfficerTitle($id),
		));		
	}
	
	
	/*
	 * Builds array for diary report to be displayed
	 * returns array
	 */
	protected function buildArray()
	{
		$_user = User::model()->findByPk(Yii::app()->user->id);
		//$_report = null;
		//lists all officers except that which is attached to current user
		$_sql = "SELECT DISTINCT `tbl_officers`.`id`, `tbl_officers`.`title`
				FROM `tbl_officers`
				JOIN `tbl_marked` ON `tbl_officers`.`id` = `tbl_marked`.`officer_id`
				WHERE `tbl_officers`.`id`<>" . $_user->getOfficerID() . "
					AND `tbl_officers`.`level`>=". $_user->getOfficerLevel() ."
					AND `tbl_marked`.`marked_by`=" . $_user->getOfficerID() . "
				ORDER BY `tbl_officers`.`level`";
		$_officers = Yii::app()->db->createCommand($_sql)->queryAll();

		if (!$_officers) return array();
		
		//Query for selecting number of document pending for more than 3 days
		$_sql = "SELECT `tbl_marked`.`officer_id` , COUNT( `tbl_marked`.`document_id` ) AS NumberOfDocs
				 FROM `tbl_marked`
				 LEFT OUTER JOIN `tbl_disposal` ON `tbl_marked`.`document_id` = `tbl_disposal`.`document_id`
				 WHERE (
					(`tbl_marked`.`marked_by`=". $_user->getOfficerID() .")
					AND 
					(`tbl_marked`.`marked_date`
						BETWEEN 
							(DATE_SUB(CURDATE(), INTERVAL 4 DAY))
						AND 
							(DATE_SUB(CURDATE(), INTERVAL 3 DAY))
					)
					AND 
					(`tbl_disposal`.`document_id` IS NULL)
				 )
				 GROUP BY `tbl_marked`.`officer_id`";
		
		$_more_than_3_days = Yii::app()->db->createCommand($_sql)->queryAll();
		
		//Query for selecting number of document pending for more than 5 days
		$_sql = "SELECT `tbl_marked`.`officer_id` , COUNT( `tbl_marked`.`document_id` ) AS NumberOfDocs
				 FROM `tbl_marked`
				 LEFT OUTER JOIN `tbl_disposal` ON `tbl_marked`.`document_id` = `tbl_disposal`.`document_id`
				 WHERE (
					(`tbl_marked`.`marked_by`=". $_user->getOfficerID() .")
					AND 
					(`tbl_marked`.`marked_date`
						BETWEEN 
							(DATE_SUB(CURDATE(), INTERVAL 6 DAY))
						AND 
							(DATE_SUB(CURDATE(), INTERVAL 5 DAY))
					)
					AND 
					(`tbl_disposal`.`document_id` IS NULL)
				 )
				 GROUP BY `tbl_marked`.`officer_id`";
		
		$_more_than_5_days = Yii::app()->db->createCommand($_sql)->queryAll();
		
		//Query for selecting number of document pending for more than 7 days
		$_sql = "SELECT `tbl_marked`.`officer_id` , COUNT(`tbl_marked`.`document_id`) AS NumberOfDocs
				 FROM `tbl_marked`
				 LEFT OUTER JOIN `tbl_disposal` ON `tbl_marked`.`document_id`=`tbl_disposal`.`document_id`
				 WHERE (
						(`tbl_marked`.`marked_by`=" . $_user->getOfficerID() . ")
					AND 
						(`tbl_marked`.`marked_date` <= DATE_SUB(CURDATE(), INTERVAL 7 DAY))
					AND 
						(`tbl_disposal`.`document_id` IS NULL)
					)
				 GROUP BY `tbl_marked`.`officer_id`";
		
		$_more_than_7_days = Yii::app()->db->createCommand($_sql)->queryAll();
		
//		print_r($_officers);
//		echo "<p>";
//		print_r($_more_than_3_days);
//		echo "<p>";
//		print_r($_more_than_5_days);
//		echo "<p>";
//		print_r($_more_than_7_days);
//		echo "<p>";		

		$i=0;
		foreach ($_officers as $officer)
		{
			if (DiaryreportController::compareOfficerID($_more_than_3_days, $officer['id']) ||
				DiaryreportController::compareOfficerID($_more_than_5_days, $officer['id']) ||
				DiaryreportController::compareOfficerID($_more_than_7_days, $officer['id']))
			{
				$this->_report[] = array(
					'id'=>$officer['id'],
					'officer_title'=>CHtml::link($officer['title'],$this->createUrl('/diaryreport/view', array('id'=>$officer['id']))),			
				);
				foreach ($_more_than_3_days as $numof3days)
				{
					if ($officer['id'] == $numof3days['officer_id'])
					{
						$this->_report[$i]['more_than_3_days'] = "<center>".$numof3days['NumberOfDocs']."</center>";
					}	
				}
				
				foreach ($_more_than_5_days as $numof5days)
				{
					if ($officer['id'] == $numof5days['officer_id'])
					{
						$this->_report[$i]['more_than_5_days'] = "<center>".$numof5days['NumberOfDocs']."</center>";
					}	
				}
				
				
				foreach ($_more_than_7_days as $numof7days)
				{
					if ($officer['id'] == $numof7days['officer_id'])
					{
						$this->_report[$i]['more_than_7_days'] = "<center>".$numof7days['NumberOfDocs']."</center>";
					}	
				}
				$i++;
			}
		}
//		print_r($this->_report);
		return $this->_report;
	}
	
	/*
	 * This function displayes pendency by detail
	 */
	public function actionPview($id)
	{
		$this->_officer_id=$id;

		$date_3_days = strtotime('-3 days');
		$date_5_days = strtotime('-5 days');
		$date_7_days = strtotime('-7 days');
		$date_10_days= strtotime('-10 days');
		
		$_more_than_3_days = array();
		$_more_than_5_days = array();
		$_more_than_7_days = array();
		$_more_than_10_days = array();
		
		//$_user = Users::model()->findByPk(Yii::app()->user->id);
		//Query for more than 3 days
		$_sql = "SELECT `tbl_documents`.`id`, 
						`tbl_documents`.`diary_number`, 
						`tbl_documents`.`reference_number`, 
						`tbl_documents`.`date_of_document`, 
						`tbl_documents`.`received_from`, 
						`tbl_documents`.`description`, 
						`tbl_marked`.`marked_date`
				FROM 
						`tbl_documents`
				JOIN 
						(`tbl_marked` 
						LEFT JOIN `tbl_disposal` ON `tbl_marked`.`document_id`=`tbl_disposal`.`document_id`) 
						ON `tbl_documents`.`id`=`tbl_marked`.`document_id`
				WHERE
						(`tbl_marked`.`officer_id`=$id)					
					 AND
						(`tbl_disposal`.`document_id` IS NULL)
				ORDER BY
						`tbl_documents`.`id`";
		
		$_pending_documents = Yii::app()->db->createCommand($_sql)->queryAll();

		foreach ($_pending_documents as $_document)
		{
			$LastMarked = $this->LastMarking($_document['id']);	
			if ($LastMarked['officer_id']==$id){
				if (strtotime($LastMarked['marked_date']) > $date_5_days && strtotime($LastMarked['marked_date']) <= $date_3_days )
				{
					$_more_than_3_days[] = $_document;				
				} 
				elseif (strtotime($LastMarked['marked_date']) > $date_7_days && strtotime($LastMarked['marked_date']) <= $date_5_days )
				{
					$_more_than_5_days[] = $_document;
				}
				elseif (strtotime($LastMarked['marked_date']) > $date_10_days && strtotime($LastMarked['marked_date']) <= $date_7_days)
				{
					$_more_than_7_days[] = $_document;
				}
				elseif (strtotime($LastMarked['marked_date']) <= $date_10_days)
				{
					$_more_than_10_days[] = $_document;
				}
			}
		}
		if ($_more_than_3_days)
		{	
			$cnt = count($_more_than_3_days);
			
			for ($i=0;$i<$cnt;$i++)
			{
				$_more_than_3_days[$i]['comment']="<font color=red>" .(($_comments=Comment::model()->findByAttributes(array('document_id'=>$_more_than_3_days[$i]['id']))) ? $_comments->comment : '') . "</font>" .
													(((Yii::app()->user->name == 'ag') || (Yii::app()->user->name == 'admin')) ? "<BR>" . CHtml::link('Create', $this->createUrl('/comments/create', array('id'=>$_more_than_3_days[$i]['id']))) : '');
				if ($_comments && ((Yii::app()->user->name == 'ag') || (Yii::app()->user->name == 'admin')))
						$_more_than_3_days[$i]['comment'] .= " | " . CHtml::link('Edit', $this->createUrl('/comments/update', array('id'=>$_comments->id))) . " | " .
													CHtml::link('Delete', $this->createUrl('/comments/delete', array('id'=>$_comments->id)));
				
				$_more_than_3_days[$i]['actions']=CHtml::link('View',$this->createUrl('/documents/view', array('id'=>$_more_than_3_days[$i]['id'])));
			}
		}
		
		if ($_more_than_5_days)
		{
			$cnt = count($_more_than_5_days);
			
			for ($i=0;$i<$cnt;$i++)
			{
				$_more_than_5_days[$i]['comment']="<font color=red>" .(($_comments=Comment::model()->findByAttributes(array('document_id'=>$_more_than_5_days[$i]['id']))) ? $_comments->comment : '') . "</font><BR>" .
													(((Yii::app()->user->name == 'ag') || (Yii::app()->user->name == 'admin')) ? "<BR>" . CHtml::link('Create', $this->createUrl('/comments/create', array('id'=>$_more_than_5_days[$i]['id']))) : '');
				if ($_comments && ((Yii::app()->user->name == 'ag') || (Yii::app()->user->name == 'admin')))
						$_more_than_5_days[$i]['comment'] .= " | " . CHtml::link('Edit', $this->createUrl('/comments/update', array('id'=>$_comments->id))) . " | " .
													CHtml::link('Delete', $this->createUrl('/comments/delete', array('id'=>$_comments->id)));
				
				$_more_than_5_days[$i]['actions']=CHtml::link('View',$this->createUrl('/documents/view', array('id'=>$_more_than_5_days[$i]['id'])));
			}
		}
		
		if ($_more_than_7_days)
		{
			$cnt = count($_more_than_7_days);
			
			for ($i=0;$i<$cnt;$i++)
			{
				$_more_than_7_days[$i]['comment']="<font color=red>" .(($_comments=Comment::model()->findByAttributes(array('document_id'=>$_more_than_7_days[$i]['id']))) ? $_comments->comment : '') . "</font><BR>" .
													(((Yii::app()->user->name == 'ag') || (Yii::app()->user->name == 'admin')) ?  "<BR>" . CHtml::link('Create', $this->createUrl('/comments/create', array('id'=>$_more_than_7_days[$i]['id']))) : '');
				if ($_comments && ((Yii::app()->user->name == 'ag') || (Yii::app()->user->name == 'admin')))
						$_more_than_7_days[$i]['comment'] .= " | " . CHtml::link('Edit', $this->createUrl('/comments/update', array('id'=>$_comments->id))) . " | " .
													CHtml::link('Delete', $this->createUrl('/comments/delete', array('id'=>$_comments->id)));
				
				$_more_than_7_days[$i]['actions']=CHtml::link('View',$this->createUrl('/documents/view', array('id'=>$_more_than_7_days[$i]['id']))); 
			}
		}
		
		if ($_more_than_10_days)
		{
			$cnt = count($_more_than_10_days);
			
			for ($i=0;$i<$cnt;$i++)
			{
				$_more_than_10_days[$i]['comment']="<font color=red>" .(($_comments=Comment::model()->findByAttributes(array('document_id'=>$_more_than_10_days[$i]['id']))) ? $_comments->comment : '') . "</font><BR>" .
													(((Yii::app()->user->name == 'ag') || (Yii::app()->user->name == 'admin')) ?  "<BR>" . CHtml::link('Create', $this->createUrl('/comments/create', array('id'=>$_more_than_10_days[$i]['id']))) : '');
				if ($_comments && ((Yii::app()->user->name == 'ag') || (Yii::app()->user->name == 'admin')))
						$_more_than_10_days[$i]['comment'] .= " | " . CHtml::link('Edit', $this->createUrl('/comments/update', array('id'=>$_comments->id))) . " | " .
													CHtml::link('Delete', $this->createUrl('/comments/delete', array('id'=>$_comments->id)));
				
				$_more_than_10_days[$i]['actions']=CHtml::link('View',$this->createUrl('/documents/view', array('id'=>$_more_than_10_days[$i]['id']))); 
			}
		}
		
		array_walk_recursive($_more_than_3_days, 'DiaryreportController::formatDate');
		array_walk_recursive($_more_than_5_days, 'DiaryreportController::formatDate');
		array_walk_recursive($_more_than_7_days, 'DiaryreportController::formatDate');
		array_walk_recursive($_more_than_10_days, 'DiaryreportController::formatDate');		

		$dataProvider3 = new CArrayDataProvider( 
      		$_more_than_3_days, array(
      			'pagination'=>array(
        			'pageSize'=>50,
      				),
    			)); 

    	$dataProvider5 = new CArrayDataProvider( 
      		$_more_than_5_days, array(
      			'pagination'=>array(
        			'pageSize'=>50,
      				),
    			)); 

    	$dataProvider7 = new CArrayDataProvider( 
      		$_more_than_7_days, array(
      			'pagination'=>array(
        			'pageSize'=>50,
      				),
    			)); 
    			
    	$dataProvider10 = new CArrayDataProvider( 
      		$_more_than_10_days, array(
      			'pagination'=>array(
        			'pageSize'=>50,
      				),
    			)); 
    			
    	$this->render('view', array(
			'dataProvider3'=>$dataProvider3,
			'dataProvider5'=>$dataProvider5,
			'dataProvider7'=>$dataProvider7,
			'dataProvider10'=>$dataProvider10,
   			'officerTitle'=>Officer::model()->getOfficerTitle($id),
		));		
	}
	

	/*
	 * Builds array for diary report to be displayed by the officer to whom the document was last marked
	 * returns array
	 */
	protected function buildArrayByLastOfficer()
	{
		//$_user = Users::model()->findByPk(Yii::app()->user->id);
		$_report = array();
		
		$date_3_days = strtotime('-3 days');
		$date_5_days = strtotime('-5 days');
		$date_7_days = strtotime('-7 days');
		$date_10_days= strtotime('-10 days');
		
		//SELECT all documents which are not disposed off
		$_sql = "SELECT `tbl_marked`.`document_id`
				FROM `tbl_marked`
				LEFT OUTER JOIN `tbl_disposal` ON `tbl_marked`.`document_id` = `tbl_disposal`.`document_id`
				WHERE (`tbl_disposal`.`document_id` IS NULL) 
				GROUP BY `tbl_marked`.`document_id`";
		
		$_pending_documents = Yii::app()->db->createCommand($_sql)->queryAll();
		
//		echo $total_pending_docs = count($_pending_documents);
		
		$_sql = "SELECT DISTINCT `tbl_officers`.`id` , `tbl_officers`.`title`
				FROM `tbl_officers`
				JOIN (`tbl_marked`
					LEFT OUTER JOIN `tbl_disposal` ON `tbl_marked`.`document_id` = `tbl_disposal`.`document_id`) 
				ON `tbl_officers`.`id` = `tbl_marked`.`officer_id`
				WHERE `tbl_disposal`.`document_id` IS NULL
				ORDER BY `tbl_officers`.`level`";
		
		$_officers = Yii::app()->db->createCommand($_sql)->queryAll();
		
		$_off = array();
		
		if ($_officers)
		{
			foreach($_officers as $key => $value)
			{
				$_off[$value['id']] = array('id'=> $value['id'],
											'officer_title'=> CHtml::link($value['title'],$this->createUrl('/diaryreport/pview', array('id'=>$value['id']))),
											'more_than_3_days'=>NULL,
											'more_than_5_days'=>NULL,
											'more_than_7_days'=>NULL,
											'more_than_10_days'=>NULL,
											);
			}
		}
		
		$_officers = $_off;
//		echo '<pre>';
//		print_r($_officers);
		
		foreach ($_pending_documents as $_p_document)
		{
			$LastMarked = $this->LastMarking($_p_document['document_id']);
			if (strtotime($LastMarked['marked_date']) > $date_5_days && strtotime($LastMarked['marked_date']) <= $date_3_days )
			{
				$_officers[$LastMarked['officer_id']]['more_than_3_days']++;				
			} 
			elseif (strtotime($LastMarked['marked_date']) > $date_7_days && strtotime($LastMarked['marked_date']) <= $date_5_days )
			{
				$_officers[$LastMarked['officer_id']]['more_than_5_days']++;
			}
			elseif (strtotime($LastMarked['marked_date']) > $date_10_days && strtotime($LastMarked['marked_date']) <= $date_7_days)
			{
				$_officers[$LastMarked['officer_id']]['more_than_7_days']++;
			}
			elseif (strtotime($LastMarked['marked_date']) <= $date_10_days)
			{
				$_officers[$LastMarked['officer_id']]['more_than_10_days']++;
			}
		}
//		print_r($_officers);

		$_report = array_values($_officers);
		
		$cnt = count($_report);
		for ($i=0; $i<$cnt;$i++)
		{
			if(!isset($_report[$i]['more_than_3_days']) && !isset($_report[$i]['more_than_5_days']) && !isset($_report[$i]['more_than_7_days']) && !isset($_report[$i]['more_than_10_days']))
			{
				unset($_report[$i]);
				$_report = array_values($_report);
				$i--;
				$cnt--;		
			} else {
				$_report[$i]['more_than_3_days']  = "<center>".$_report[$i]['more_than_3_days'] ."</center>";
				$_report[$i]['more_than_5_days']  = "<center>".$_report[$i]['more_than_5_days'] ."</center>";
				$_report[$i]['more_than_7_days']  = "<center>".$_report[$i]['more_than_7_days'] ."</center>";
				$_report[$i]['more_than_10_days'] = "<center>".$_report[$i]['more_than_10_days']."</center>";
			}
		}
		return $_report;
	}

	/*
	 * This function returns id of the officer to whom the document was last marked
	 * @param integer document_id: document to look for
	 * @return array containing officer_id: the id officer to whom last marked and id: of the marked table
	 */
	public function LastMarking($document_id)
	{
		$sql = "SELECT id, officer_id, marked_date FROM tbl_marked WHERE document_id=:documentID";
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(":documentID", $document_id, PDO::PARAM_INT);
		$result = $command->queryAll();
		$sizeOfResult = count($result);
		return array('id'=>$result[$sizeOfResult-1]['id'], 
					 'officer_id'=>$result[$sizeOfResult-1]['officer_id'],
					 'marked_date'=>$result[$sizeOfResult-1]['marked_date']);
	}
	
	
	/**
	 * Call back function for array_walk_recursive()
	 * Formats the date to dd-mm-yyyy
	 * @param string $item from the array passed by reference
	 * @param string $key from the array
	 */
	protected function formatDate(&$item, $key)
	{
		if ($this->valid_date($item))
		{
			//list($y, $m, $d) = explode('-', $item);
        	//$mk=mktime(0, 0, 0, $m, $d, $y);
        	$item= date('d-m-Y', strtotime($item));
		}
	}
	
	/**
	 * Checks if the date is valid
	 * @param string $date
	 * @param string $format (optional)
	 * @return boolean
	 */	
	public function valid_date($date, $format = 'YYYY-MM-DD')
	{
	    if(strlen($date) >= 8 && strlen($date) <= 10){
	        $separator_only = str_replace(array('M','D','Y'),'', $format);
	        $separator = $separator_only[0];
	        if($separator){
	            $regexp = str_replace($separator, "\\" . $separator, $format);
	            $regexp = str_replace('MM', '(0[1-9]|1[0-2])', $regexp);
	            $regexp = str_replace('M', '(0?[1-9]|1[0-2])', $regexp);
	            $regexp = str_replace('DD', '(0[1-9]|[1-2][0-9]|3[0-1])', $regexp);
	            $regexp = str_replace('D', '(0?[1-9]|[1-2][0-9]|3[0-1])', $regexp);
	            $regexp = str_replace('YYYY', '\d{4}', $regexp);
	            $regexp = str_replace('YY', '\d{2}', $regexp);
	            if($regexp != $date && preg_match('/'.$regexp.'$/', $date)){
	                foreach (array_combine(explode($separator,$format), explode($separator,$date)) as $key=>$value) {
	                    if ($key == 'YY') $year = '20'.$value;
	                    if ($key == 'YYYY') $year = $value;
	                    if ($key[0] == 'M') $month = $value;
	                    if ($key[0] == 'D') $day = $value;
	                }
	                if (checkdate($month,$day,$year)) return true;
	            }
	        }
	    }
	    return false;
	}
	
			
	/**
	 * Checks that officer id exsists in provided array
	 * @param array $array_to_search to be looked in
	 * @param string $officerID to be searched
	 */
	protected function compareOfficerID($array_to_search, $officerID)
	{
		foreach ($array_to_search as $outstanding)
		{
			if ($outstanding['officer_id']==$officerID)
				return true;
		}
		return false;
	}
	
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}
