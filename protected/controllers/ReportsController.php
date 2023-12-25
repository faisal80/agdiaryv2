<?php

class ReportsController extends Controller
{

	private $_report = null;
	public $_officer_id = null;
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl',
			// perform access control for CRUD operations
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
			array(
				'allow',
				// allow all users to perform 'index' and 'view' actions
				'actions' => array('index', 'view', 'pview', 'pendency', 'diaryReport', 'dispatchReport', 'unAttendedDocs', 'pendingFiles', 'pFileview'),
				'users' => array('@'),
			),
			array(
				'allow',
				// allow authenticated user to perform 'create' and 'update' actions
				'actions' => array('create', 'update'),
				'users' => array('admin'),
			),
			array(
				'allow',
				// allow admin user to perform 'admin' and 'delete' actions
				'actions' => array('admin', 'delete'),
				'users' => array('admin'),
			),
			array(
				'deny',
				// deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionIndex()
	{

	}

	public function actionDiaryReport()
	{
		$result = array();
		$unmarked_docs = array();

		$period = new PeriodForm;
		$period->datefrom = date('d-m-Y', strtotime('-1 month'));
		$period->dateto = date('d-m-Y', strtotime('now'));
		// if period is given then
		if (isset($_POST['PeriodForm'])) {
			$period->attributes = $_POST['PeriodForm'];
			if ($period->validate()) {
				$dp = $this->buildArrayByLastOfficer($period);
				if (!empty($dp)) {
					$dataProvider = new CArrayDataProvider(
						$dp,
						array(
							'pagination' => array(
								'pageSize' => 100,
							),
						)
					);
				} else {
					$dataProvider = new CArrayDataProvider(array());
				}

				$this->render(
					'diaryReport',
					array(
						'dataProvider' => $dataProvider,
						'period' => $period,
						//'um_docs' => $unmarked_docs,
					)
				);
			}
		} else {

			// if period is not provided, get it
			$this->render('/site/period', array('model' => $period));
		}
	}

	public function actionDispatchReport()
	{
		$sql = "SELECT a.*
				FROM {{dispatch_docs}} AS a
				LEFT JOIN {{dispatch_details}} AS b
					ON a.id=b.disp_doc_id
				WHERE b.disp_doc_id IS NULL";
		$all_pending = Yii::app()->db->createCommand($sql)->queryAll();

		$dp = new CArrayDataProvider($all_pending);

		$this->render('dispatchReport', array('dataProvider' => $dp));

	}

	public function actionUnAttendedDocs()
	{
		$sql = "	SELECT a.*
				FROM {{documents}} AS a
				LEFT JOIN {{marked}} AS b
					ON a.id=b.document_id
				WHERE b.document_id is NULL";
		$unattended = Yii::app()->db->createCommand($sql)->queryAll();

		$dp = new CArrayDataProvider($unattended);

		$this->render('unattended', array('dataProvider' => $dp));
	}

	public function actionPendingFiles()
	{
		$dp = $this->buildArrayofFilesByLastOfficer();
		if (!empty($dp)) {
			$dataProvider = new CArrayDataProvider(
				$dp,
				array(
					'pagination' => array(
						'pageSize' => 100,
					),
				)
			);
		} else {
			$dataProvider = new CArrayDataProvider(array());
		}
		$this->render(
			'pendingFiles',
			array(
				'dataProvider' => $dataProvider,
			)
		);
	}
	public function actionPendency()
	{
		$dp = $this->buildArrayByLastOfficer();
		if (!empty($dp)) {
			$dataProvider = new CArrayDataProvider(
				$dp,
				array(
					'pagination' => array(
						'pageSize' => 50,
					),
				)
			);
		} else {
			$dataProvider = new CArrayDataProvider(array());
		}

		$this->render(
			'index',
			array(
				'dataProvider' => $dataProvider
			)
		);
	}

	public function actionView($id)
	{
		$tp = Yii::app()->db->tablePrefix;
		$this->_officer_id = $id;

		$_user = User::model()->findByPk(Yii::app()->user->id);
		//Query for more than 3 days
		$_sql = "SELECT `" . $tp . "documents`.`id`, 
						`" . $tp . "documents`.`diary_number`, 
						`" . $tp . "documents`.`reference_number`, 
						`" . $tp . "documents`.`date_of_document`, 
						`" . $tp . "documents`.`received_from`, 
						`" . $tp . "documents`.`description`, 
						`" . $tp . "marked`.`marked_date`
				FROM 
						`" . $tp . "documents`
				JOIN 
						(`" . $tp . "marked` 
						LEFT JOIN `" . $tp . "disposal` ON `" . $tp . "marked`.`document_id`=`" . $tp . "disposal`.`document_id`) 
						ON `" . $tp . "documents`.`id`=`" . $tp . "marked`.`document_id`
				WHERE
						(`" . $tp . "marked`.`officer_id`=" . $id . ") 
						AND 
						(`" . $tp . "marked`.`marked_by`=" . $_user->getOfficerID() . ") 
						AND 
						(`" . $tp . "marked`.`marked_date` 
							BETWEEN
								DATE_SUB(CURDATE(), INTERVAL 4 DAY) 
							AND 
								DATE_SUB(CURDATE(), INTERVAL 3 DAY)) 
						AND 
							(`" . $tp . "disposal`.`document_id` IS NULL)
				ORDER BY
						`" . $tp . "documents`.`id`";

		$_more_than_3_days = Yii::app()->db->createCommand($_sql)->queryAll();

		//Query for more than 5 days
		$_sql = "SELECT `" . $tp . "documents`.`id`, 
						`" . $tp . "documents`.`diary_number`, 
						`" . $tp . "documents`.`reference_number`, 
						`" . $tp . "documents`.`date_of_document`, 
						`" . $tp . "documents`.`received_from`, 
						`" . $tp . "documents`.`description`, 
						`" . $tp . "marked`.`marked_date`
				FROM 
						`" . $tp . "documents`
				JOIN 
						(`" . $tp . "marked` 
						LEFT JOIN `" . $tp . "disposal` ON `" . $tp . "marked`.`document_id`=`" . $tp . "disposal`.`document_id`) 
						ON `" . $tp . "documents`.`id`=`" . $tp . "marked`.`document_id`
				WHERE
						(`" . $tp . "marked`.`officer_id`=" . $id . ") 
						AND 
						(`" . $tp . "marked`.`marked_by`=" . $_user->getOfficerID() . ") 
						AND 
						(`" . $tp . "marked`.`marked_date` 
							BETWEEN 
								DATE_SUB(CURDATE(), INTERVAL 6 DAY) 
							AND 
								DATE_SUB(CURDATE(), INTERVAL 5 DAY)) 
						AND 
							(`" . $tp . "disposal`.`document_id` IS NULL)
				ORDER BY
						`" . $tp . "documents`.`id`";

		$_more_than_5_days = Yii::app()->db->createCommand($_sql)->queryAll();

		//Query for more than 7 days
		$_sql = "SELECT `" . $tp . "documents`.`id`, 
						`" . $tp . "documents`.`diary_number`, 
						`" . $tp . "documents`.`reference_number`, 
						`" . $tp . "documents`.`date_of_document`, 
						`" . $tp . "documents`.`received_from`, 
						`" . $tp . "documents`.`description`, 
						`" . $tp . "marked`.`marked_date`
				FROM 
						`" . $tp . "documents`
				JOIN 
						(`" . $tp . "marked` 
						LEFT JOIN `" . $tp . "disposal` ON `" . $tp . "marked`.`document_id`=`" . $tp . "disposal`.`document_id`) 
						ON `" . $tp . "documents`.`id`=`" . $tp . "marked`.`document_id`
				WHERE
						(`" . $tp . "marked`.`officer_id`=" . $id . ") 
					AND 
						(`" . $tp . "marked`.`marked_by`=" . $_user->getOfficerID() . ") 
					AND 
						(`" . $tp . "marked`.`marked_date`<= DATE_SUB(CURDATE(), INTERVAL 7 DAY)) 
					AND 
						(`" . $tp . "disposal`.`document_id` IS NULL)
				ORDER BY
						`" . $tp . "documents`.`id`";

		$_more_than_7_days = Yii::app()->db->createCommand($_sql)->queryAll();

		//$callbackfunc = $this->formatDate($item, $key);

		array_walk_recursive($_more_than_3_days, 'ReportsController::formatDate');
		array_walk_recursive($_more_than_5_days, 'ReportsController::formatDate');
		array_walk_recursive($_more_than_7_days, 'ReportsController::formatDate');

		$cnt = count($_more_than_3_days);

		for ($i = 0; $i < $cnt; $i++) {
			$_more_than_3_days[$i]['comment'] = "<font color=red>" . (($_comments = Comment::model()->findByAttributes(array('document_id' => $_more_than_3_days[$i]['id']))) ? $_comments->comment : '') . "</font><BR>" .
				CHtml::link('Create', $this->createUrl('/comments/create', array('id' => $_more_than_3_days[$i]['id'])));
			if ($_comments)
				$_more_than_3_days[$i]['comment'] .= " | " . CHtml::link('Edit', $this->createUrl('/comments/update', array('id' => $_comments->id))) . " | " .
					CHtml::link('Delete', $this->createUrl('/comments/delete', array('id' => $_comments->id)));

			$_more_than_3_days[$i]['actions'] = CHtml::link('View', $this->createUrl('/documents/view', array('id' => $_more_than_3_days[$i]['id'])));
		}

		$cnt = count($_more_than_5_days);

		for ($i = 0; $i < $cnt; $i++) {
			$_more_than_5_days[$i]['comment'] = "<font color=red>" . (($_comments = Comment::model()->findByAttributes(array('document_id' => $_more_than_5_days[$i]['id']))) ? $_comments->comment : '') . "</font><BR>" .
				CHtml::link('Create', $this->createUrl('/comments/create', array('id' => $_more_than_5_days[$i]['id'])));
			if ($_comments)
				$_more_than_5_days[$i]['comment'] .= " | " . CHtml::link('Edit', $this->createUrl('/comments/update', array('id' => $_comments->id))) . " | " .
					CHtml::link('Delete', $this->createUrl('/comments/delete', array('id' => $_comments->id)));

			$_more_than_5_days[$i]['actions'] = CHtml::link('View', $this->createUrl('/documents/view', array('id' => $_more_than_5_days[$i]['id'])));
		}

		$cnt = count($_more_than_7_days);

		for ($i = 0; $i < $cnt; $i++) {
			$_more_than_7_days[$i]['comment'] = "<font color=red>" . (($_comments = Comment::model()->findByAttributes(array('document_id' => $_more_than_7_days[$i]['id']))) ? $_comments->comment : '') . "</font><BR>" .
				CHtml::link('Create', $this->createUrl('/comments/create', array('id' => $_more_than_7_days[$i]['id'])));
			if ($_comments)
				$_more_than_7_days[$i]['comment'] .= " | " . CHtml::link('Edit', $this->createUrl('/comments/update', array('id' => $_comments->id))) . " | " .
					CHtml::link('Delete', $this->createUrl('/comments/delete', array('id' => $_comments->id)));

			$_more_than_7_days[$i]['actions'] = CHtml::link('View', $this->createUrl('/documents/view', array('id' => $_more_than_7_days[$i]['id'])));
		}

		$dataProvider3 = new CArrayDataProvider(
			$_more_than_3_days,
			array(
				'pagination' => array(
					'pageSize' => 50,
				),
			)
		);

		$dataProvider5 = new CArrayDataProvider(
			$_more_than_5_days,
			array(
				'pagination' => array(
					'pageSize' => 50,
				),
			)
		);

		$dataProvider7 = new CArrayDataProvider(
			$_more_than_7_days,
			array(
				'pagination' => array(
					'pageSize' => 50,
				),
			)
		);

		$this->render(
			'view',
			array(
				'dataProvider3' => $dataProvider3,
				'dataProvider5' => $dataProvider5,
				'dataProvider7' => $dataProvider7,
				'officerTitle' => Officer::model()->getOfficerTitle($id),
			)
		);
	}


	/*
	 * Builds array for diary report to be displayed
	 * returns array
	 */
	protected function buildArray()
	{

		$tp = Yii::app()->db->tablePrefix;

		$_user = User::model()->findByPk(Yii::app()->user->id);
		//lists all officers except that which is attached to current user
		$_sql = "SELECT DISTINCT `" . $tp . "officers`.`id`, `" . $tp . "officers`.`title`
				FROM `" . $tp . "officers`
				JOIN `" . $tp . "marked` ON `" . $tp . "officers`.`id` = `" . $tp . "marked`.`officer_id`
				WHERE `" . $tp . "officers`.`id`<>" . $_user->getOfficerID() . "
					AND `" . $tp . "officers`.`level`>=" . $_user->getOfficerLevel() . "
					AND `" . $tp . "marked`.`marked_by`=" . $_user->getOfficerID() . "
				ORDER BY `" . $tp . "officers`.`level`";
		$_officers = Yii::app()->db->createCommand($_sql)->queryAll();

		if (!$_officers)
			return array();

		//Query for selecting number of document pending for more than 3 days
		$_sql = "SELECT `" . $tp . "marked`.`officer_id` , COUNT( `" . $tp . "marked`.`document_id` ) AS NumberOfDocs
				 FROM `" . $tp . "marked`
				 LEFT OUTER JOIN `" . $tp . "disposal` ON `" . $tp . "marked`.`document_id` = `" . $tp . "disposal`.`document_id`
				 WHERE (
					(`" . $tp . "marked`.`marked_by`=" . $_user->getOfficerID() . ")
					AND 
					(`" . $tp . "marked`.`marked_date`
						BETWEEN 
							(DATE_SUB(CURDATE(), INTERVAL 4 DAY))
						AND 
							(DATE_SUB(CURDATE(), INTERVAL 3 DAY))
					)
					AND 
					(`" . $tp . "disposal`.`document_id` IS NULL)
				 )
				 GROUP BY `" . $tp . "marked`.`officer_id`";

		$_more_than_3_days = Yii::app()->db->createCommand($_sql)->queryAll();

		//Query for selecting number of document pending for more than 5 days
		$_sql = "SELECT `" . $tp . "marked`.`officer_id` , COUNT( `" . $tp . "marked`.`document_id` ) AS NumberOfDocs
				 FROM `" . $tp . "marked`
				 LEFT OUTER JOIN `" . $tp . "disposal` ON `" . $tp . "marked`.`document_id` = `" . $tp . "disposal`.`document_id`
				 WHERE (
					(`" . $tp . "marked`.`marked_by`=" . $_user->getOfficerID() . ")
					AND 
					(`" . $tp . "marked`.`marked_date`
						BETWEEN 
							(DATE_SUB(CURDATE(), INTERVAL 6 DAY))
						AND 
							(DATE_SUB(CURDATE(), INTERVAL 5 DAY))
					)
					AND 
					(`" . $tp . "disposal`.`document_id` IS NULL)
				 )
				 GROUP BY `" . $tp . "marked`.`officer_id`";

		$_more_than_5_days = Yii::app()->db->createCommand($_sql)->queryAll();

		//Query for selecting number of document pending for more than 7 days
		$_sql = "SELECT `" . $tp . "marked`.`officer_id` , COUNT(`" . $tp . "marked`.`document_id`) AS NumberOfDocs
				 FROM `" . $tp . "marked`
				 LEFT OUTER JOIN `" . $tp . "disposal` ON `" . $tp . "marked`.`document_id`=`" . $tp . "disposal`.`document_id`
				 WHERE (
						(`" . $tp . "marked`.`marked_by`=" . $_user->getOfficerID() . ")
					AND 
						(`" . $tp . "marked`.`marked_date` <= DATE_SUB(CURDATE(), INTERVAL 7 DAY))
					AND 
						(`" . $tp . "disposal`.`document_id` IS NULL)
					)
				 GROUP BY `" . $tp . "marked`.`officer_id`";

		$_more_than_7_days = Yii::app()->db->createCommand($_sql)->queryAll();

		$i = 0;
		foreach ($_officers as $officer) {
			if (
				ReportsController::compareOfficerID($_more_than_3_days, $officer['id']) ||
				ReportsController::compareOfficerID($_more_than_5_days, $officer['id']) ||
				ReportsController::compareOfficerID($_more_than_7_days, $officer['id'])
			) {
				$this->_report[] = array(
					'id' => $officer['id'],
					'officer_title' => CHtml::link($officer['title'], $this->createUrl('/reports/view', array('id' => $officer['id']))),
				);
				foreach ($_more_than_3_days as $numof3days) {
					if ($officer['id'] == $numof3days['officer_id']) {
						$this->_report[$i]['more_than_3_days'] = "<center>" . $numof3days['NumberOfDocs'] . "</center>";
					}
				}

				foreach ($_more_than_5_days as $numof5days) {
					if ($officer['id'] == $numof5days['officer_id']) {
						$this->_report[$i]['more_than_5_days'] = "<center>" . $numof5days['NumberOfDocs'] . "</center>";
					}
				}


				foreach ($_more_than_7_days as $numof7days) {
					if ($officer['id'] == $numof7days['officer_id']) {
						$this->_report[$i]['more_than_7_days'] = "<center>" . $numof7days['NumberOfDocs'] . "</center>";
					}
				}
				$i++;
			}
		}
		return $this->_report;
	}

	/*
	 * This function displayes pendency by detail
	 */
	public function actionPview($id, $period_from, $period_to)
	{
		$date_3_days = strtotime('-3 days');
		$date_5_days = strtotime('-5 days');
		$date_7_days = strtotime('-7 days');
		$date_10_days = strtotime('-10 days');

		$_more_than_3_days = array();
		$_more_than_5_days = array();
		$_more_than_7_days = array();
		$_more_than_10_days = array();

		//$_user = Users::model()->findByPk(Yii::app()->user->id);
		$_sql = "SELECT id
				FROM {{documents}}
				WHERE is_case_disposed=FALSE
					AND date_received
					   BETWEEN CAST('" . $period_from . "' AS DATE)
					   AND CAST('" . $period_to . "' AS DATE)
					";
		$_pending_documents = Yii::app()->db->createCommand($_sql)->queryAll();

		foreach ($_pending_documents as $_document) {

			$document = Document::model()->findByPk($_document['id']);
			$lastMarking = $document->lastMarking();
			$secondLastMarking = $document->secondLastMarking();

			$date_to_compare = null;
			$marked_to = null;
			// $marked_id = null;

			if (!empty($lastMarking) && !empty($lastMarking->received_time_stamp)) {
				$date_to_compare = $lastMarking->received_time_stamp;
				$marked_to = $lastMarking->marked_to;
				$document->marked_id = $lastMarking->id;
			} else {
				if (!empty($secondLastMarking) && !empty($secondLastMarking->received_time_stamp)) {
					$date_to_compare = $secondLastMarking->received_time_stamp;
					$marked_to = $secondLastMarking->marked_to;
					$document->marked_id = $secondLastMarking->id;
				} else {
					$date_to_compare = $document->date_received;
					$marked_to = $document->owner_id;
					$document->marked_id = null;
				}
			}

			if ($marked_to == $id) {
				if (strtotime($date_to_compare) > $date_5_days && strtotime($date_to_compare) <= $date_3_days) {
					$_more_than_3_days[] = $document;
				} elseif (strtotime($date_to_compare) > $date_7_days && strtotime($date_to_compare) <= $date_5_days) {
					$_more_than_5_days[] = $document;
				} elseif (strtotime($date_to_compare) > $date_10_days && strtotime($date_to_compare) <= $date_7_days) {
					$_more_than_7_days[] = $document;
				} elseif (strtotime($date_to_compare) <= $date_10_days) {
					$_more_than_10_days[] = $document;
				}
			}
			$marked_to = null;
		}

		$dataProvider3 = new CArrayDataProvider(
			$_more_than_3_days,
			array(
				'pagination' => array(
					'pageSize' => 50,
				),
			)
		);

		$dataProvider5 = new CArrayDataProvider(
			$_more_than_5_days,
			array(
				'pagination' => array(
					'pageSize' => 50,
				),
			)
		);

		$dataProvider7 = new CArrayDataProvider(
			$_more_than_7_days,
			array(
				'pagination' => array(
					'pageSize' => 50,
				),
			)
		);

		$dataProvider10 = new CArrayDataProvider(
			$_more_than_10_days,
			array(
				'pagination' => array(
					'pageSize' => 100,
				),
			)
		);

		$this->render(
			'view',
			array(
				'dataProvider3' => $dataProvider3,
				'dataProvider5' => $dataProvider5,
				'dataProvider7' => $dataProvider7,
				'dataProvider10' => $dataProvider10,
				'officerTitle' => Assignments::model()->find('duty_id=' . $id)->fullAssignment,
			)
		);
	}


	/*
	 * Builds array for diary report to be displayed by the officer to whom the document was last marked
	 * returns array
	 */
	protected function buildArrayByLastOfficer($period)
	{
		$_report = array();

		$date_3_days = strtotime('-3 days');
		$date_5_days = strtotime('-5 days');
		$date_7_days = strtotime('-7 days');
		$date_10_days = strtotime('-10 days');


		//SELECT all documents which are not disposed off
		$_sql = "SELECT id
				 FROM {{documents}}
				 WHERE is_case_disposed=FALSE
				 	AND date_received
						BETWEEN CAST('" . $period->datefrom . "' AS DATE)
						AND CAST('" . $period->dateto . "' AS DATE)
				 ";
		$_pending_documents = Yii::app()->db->createCommand($_sql)->queryAll();

		foreach ($_pending_documents as $pd) {
			$document = Document::model()->findByPk($pd['id']);
			$lastMarking = $document->lastMarking();
			$secondLastMarking = $document->secondLastMarking();

			$date_to_compare = null;
			$marked_to = null;

			if (!empty($lastMarking) && !empty($lastMarking->received_time_stamp)) {
				$date_to_compare = $lastMarking->received_time_stamp;
				$marked_to = $lastMarking->marked_to;
			} else {
				if (!empty($secondLastMarking) && !empty($secondLastMarking->received_time_stamp)) {
					$date_to_compare = $secondLastMarking->received_time_stamp;
					$marked_to = $secondLastMarking->marked_to;
				} else {
					$date_to_compare = $document->date_received;
					$marked_to = $document->owner_id;
				}
			}

			if (array_key_exists($marked_to, $_report)) {
				if (strtotime($date_to_compare) > $date_5_days && strtotime($date_to_compare) <= $date_3_days) {
					$_report[$marked_to]['more_than_3_days']++;
				} elseif (strtotime($date_to_compare) > $date_7_days && strtotime($date_to_compare) <= $date_5_days) {
					$_report[$marked_to]['more_than_5_days']++;
				} elseif (strtotime($date_to_compare) > $date_10_days && strtotime($date_to_compare) <= $date_7_days) {
					$_report[$marked_to]['more_than_7_days']++;
				} elseif (strtotime($date_to_compare) <= $date_10_days) {
					$_report[$marked_to]['more_than_10_days']++;
				}
			} else {
				$assignment = Assignments::model()->find('duty_id=' . $marked_to);
				$_report[$marked_to] = array(
					'id' => $marked_to,
					'officer' => CHtml::link($assignment->shortAssignment, $this->createUrl('/reports/pview', array('id' => $marked_to, 'period_from' => $period->datefrom, 'period_to' => $period->dateto))),
					'level' => $assignment->designation->level,
					'more_than_3_days' => (strtotime($date_to_compare) > $date_5_days && strtotime($date_to_compare) <= $date_3_days) ? 1 : null,
					'more_than_5_days' => (strtotime($date_to_compare) > $date_7_days && strtotime($date_to_compare) <= $date_5_days) ? 1 : null,
					'more_than_7_days' => (strtotime($date_to_compare) > $date_10_days && strtotime($date_to_compare) <= $date_7_days) ? 1 : null,
					'more_than_10_days' => (strtotime($date_to_compare) <= $date_10_days) ? 1 : null,
				);
			}
		}

		$_report = CArray::array_msort($_report, array('level' => SORT_ASC));


		return $_report;
	}

	/*
	 * This function returns id of the officer to whom the document was last marked
	 * @param integer document_id: document to look for
	 * @return array containing officer_id: the id officer to whom last marked and id: of the marked table
	 */
	public function LastMarking($document_id)
	{
		$tp = Yii::app()->db->tablePrefix;
		$sql = "SELECT id, officer_id, marked_date FROM " . $tp . "marked WHERE document_id=:documentID";
		$command = Yii::app()->db->createCommand($sql);
		$command->bindValue(":documentID", $document_id, PDO::PARAM_INT);
		$result = $command->queryAll();
		$sizeOfResult = count($result);
		return array(
			'id' => $result[$sizeOfResult - 1]['id'],
			'officer_id' => $result[$sizeOfResult - 1]['officer_id'],
			'marked_date' => $result[$sizeOfResult - 1]['marked_date']
		);
	}


	/**
	 * Call back function for array_walk_recursive()
	 * Formats the date to dd-mm-yyyy
	 * @param string $item from the array passed by reference
	 * @param string $key from the array
	 */
	protected function formatDate(&$item, $key)
	{
		if ($this->valid_date($item)) {
			$item = date('d-m-Y', strtotime($item));
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
		if (strlen($date) >= 8 && strlen($date) <= 10) {
			$separator_only = str_replace(array('M', 'D', 'Y'), '', $format);
			$separator = $separator_only[0];
			if ($separator) {
				$regexp = str_replace($separator, "\\" . $separator, $format);
				$regexp = str_replace('MM', '(0[1-9]|1[0-2])', $regexp);
				$regexp = str_replace('M', '(0?[1-9]|1[0-2])', $regexp);
				$regexp = str_replace('DD', '(0[1-9]|[1-2][0-9]|3[0-1])', $regexp);
				$regexp = str_replace('D', '(0?[1-9]|[1-2][0-9]|3[0-1])', $regexp);
				$regexp = str_replace('YYYY', '\d{4}', $regexp);
				$regexp = str_replace('YY', '\d{2}', $regexp);
				if ($regexp != $date && preg_match('/' . $regexp . '$/', $date)) {
					foreach (array_combine(explode($separator, $format), explode($separator, $date)) as $key => $value) {
						if ($key == 'YY')
							$year = '20' . $value;
						if ($key == 'YYYY')
							$year = $value;
						if ($key[0] == 'M')
							$month = $value;
						if ($key[0] == 'D')
							$day = $value;
					}
					if (checkdate($month, $day, $year))
						return true;
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
		foreach ($array_to_search as $outstanding) {
			if ($outstanding['officer_id'] == $officerID)
				return true;
		}
		return false;
	}

	/*
	 * Builds array for pending files report to be displayed by the officer to whom the document was last marked and received
	 * returns array
	 */
	protected function buildArrayofFilesByLastOfficer()
	{
		$_report = array();

		$date_3_days = strtotime('-3 days');
		$date_5_days = strtotime('-5 days');
		$date_7_days = strtotime('-7 days');
		$date_10_days = strtotime('-10 days');


		//SELECT all files which are enroute
		$_sql = "SELECT id
				 FROM {{files}}
				 WHERE enroute=1";

		$_pending_files = Yii::app()->db->createCommand($_sql)->queryAll();

		foreach ($_pending_files as $pf) {
			$file = File::model()->findByPk($pf["id"]);
			$lastMarking = $file->lastMarking();
			$secondLastMarking = $file->secondLastMarking();

			$date_to_compare = null;
			$marked_to = null;

			if (!empty($lastMarking) && !empty($lastMarking->received_time_stamp)) {
				$date_to_compare = $lastMarking->received_time_stamp;
				$marked_to = $lastMarking->marked_to;
			} else {
				if (!empty($secondLastMarking) && !empty($secondLastMarking->received_time_stamp)) {
					$date_to_compare = $secondLastMarking->received_time_stamp;
					$marked_to = $secondLastMarking->marked_to;
				} else {
					$date_to_compare = $file->update_time;
					$marked_to = $file->section->duty->id;
				}
			}

			$date_to_compare = strtotime($date_to_compare);

			if (array_key_exists($marked_to, $_report)) {
				if ($date_to_compare > $date_5_days && $date_to_compare < $date_3_days) {
					$_report[$marked_to]['more_than_3_days']++;
				} elseif ($date_to_compare > $date_7_days && $date_to_compare <= $date_5_days) {
					$_report[$marked_to]['more_than_5_days']++;
				} elseif ($date_to_compare > $date_10_days && $date_to_compare <= $date_7_days) {
					$_report[$marked_to]['more_than_7_days']++;
				} elseif ($date_to_compare <= $date_10_days) {
					$_report[$marked_to]['more_than_10_days']++;
				} else {
					$_report[$marked_to]['less_than_3_days']++;
				}
			} else {
				$assignment = Assignments::model()->find('duty_id=' . $marked_to);
				$_report[$marked_to] = array(
					'id' => $marked_to,
					'officer' => CHtml::link($assignment->shortAssignment, $this->createUrl('/reports/pFileview', array('id' => $marked_to))),
					'level' => $assignment->designation->level,
					'less_than_3_days' => ($date_to_compare >= $date_3_days) ? 1 : null,
					'more_than_3_days' => ($date_to_compare > $date_5_days && $date_to_compare < $date_3_days) ? 1 : null,
					'more_than_5_days' => ($date_to_compare > $date_7_days && $date_to_compare <= $date_5_days) ? 1 : null,
					'more_than_7_days' => ($date_to_compare > $date_10_days && $date_to_compare <= $date_7_days) ? 1 : null,
					'more_than_10_days' => ($date_to_compare <= $date_10_days) ? 1 : null,
				);
			}
			$marked_to = null;
		}

		$_report = CArray::array_msort($_report, array('level' => SORT_ASC));

		return $_report;
	}

	/*
	 * This function displayes pendening Files by detail
	 */
	public function actionPFileview($id)
	{
		$date_3_days = strtotime('-3 days');
		$date_5_days = strtotime('-5 days');
		$date_7_days = strtotime('-7 days');
		$date_10_days = strtotime('-10 days');

		$_less_than_3_days = array();
		$_more_than_3_days = array();
		$_more_than_5_days = array();
		$_more_than_7_days = array();
		$_more_than_10_days = array();

		//SELECT all files which are enroute
		$_sql = "SELECT id
				 FROM {{files}}
				 WHERE enroute=1";

		$_pending_files = Yii::app()->db->createCommand($_sql)->queryAll();

		foreach ($_pending_files as $_file) {
			// obtain file model
			$file = File::model()->findByPk($_file["id"]);
			$lastMarking = $file->lastMarking();
			$secondLastMarking = $file->secondLastMarking();

			$date_to_compare = null;
			$marked_to = null;

			if (!empty($lastMarking) && !empty($lastMarking->received_time_stamp)) {
				$date_to_compare = $lastMarking->received_time_stamp;
				$marked_to = $lastMarking->marked_to;
				$file->marked_id = $lastMarking->id;
			} else {
				if (!empty($secondLastMarking) && !empty($secondLastMarking->received_time_stamp)) {
					$date_to_compare = $secondLastMarking->received_time_stamp;
					$marked_to = $secondLastMarking->marked_to;
					$file->marked_id = $secondLastMarking->id;
				} else {
					$date_to_compare = $file->update_time;
					$marked_to = $file->section->duty->id;
					$file->marked_id = null;	
				}
			}

			$date_to_compare = strtotime($date_to_compare);

			if ($marked_to == $id) {
				if ($date_to_compare > $date_5_days && $date_to_compare < $date_3_days) {
					$_more_than_3_days[] = $file;
				} elseif ($date_to_compare > $date_7_days && $date_to_compare <= $date_5_days) {
					$_more_than_5_days[] = $file;
				} elseif ($date_to_compare > $date_10_days && $date_to_compare <= $date_7_days) {
					$_more_than_7_days[] = $file;
				} elseif ($date_to_compare <= $date_10_days) {
					$_more_than_10_days[] = $file;
				} else {
					$_less_than_3_days[] = $file;
				}
			}
		}

		
		$dataProviderlt3 = new CArrayDataProvider(
			$_less_than_3_days,
			array(
				'pagination' => array(
					'pageSize' => 50,
				),
			)
		);

		$dataProvider3 = new CArrayDataProvider(
			$_more_than_3_days,
			array(
				'pagination' => array(
					'pageSize' => 50,
				),
			)
		);

		$dataProvider5 = new CArrayDataProvider(
			$_more_than_5_days,
			array(
				'pagination' => array(
					'pageSize' => 50,
				),
			)
		);

		$dataProvider7 = new CArrayDataProvider(
			$_more_than_7_days,
			array(
				'pagination' => array(
					'pageSize' => 50,
				),
			)
		);

		$dataProvider10 = new CArrayDataProvider(
			$_more_than_10_days,
			array(
				'pagination' => array(
					'pageSize' => 50,
				),
			)
		);

		$this->render(
			'viewPendingFiles',
			array(
				'dataProviderlt3' => $dataProviderlt3,
				'dataProvider3' => $dataProvider3,
				'dataProvider5' => $dataProvider5,
				'dataProvider7' => $dataProvider7,
				'dataProvider10' => $dataProvider10,
				'officerTitle' => Assignments::model()->find('duty_id=' . $id)->fullAssignment,
			)
		);
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