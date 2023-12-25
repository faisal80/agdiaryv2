<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha' => array(
				'class' => 'CCaptchaAction',
				'backColor' => 0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page' => array(
				'class' => 'CViewAction',
			),
		);
	}

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
			array(
				'allow',
				// allow authenticated users to perform 'index', 'view', 'create', 'update' and 'admin' actions
				'actions' => array(
					'index',
					'toBeReceived',
					'FilesToBeReceived',
					'listTBR',
					'listFilesTBR',
					'toBeMarkedDisposed',
					'listTBMD',
					'dispatchDocsToBeReceived',
					'pendingDispatchDocs',
					'listpendingDispatch',
					'timeLimitCases',
					'listTimeLimitCases',
					'filesInHand',
					'listFileInHand',
					'toast'
				),
				'users' => array('@'),
			),
			array(
				'deny',
				// deny all users
				'actions' => array('index'),
				'users' => array('*'),
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'

		// $this->toBeReceived();

		$user = User::model()->findByPk(Yii::app()->user->id);

		$this->render(
			'index',
			array(
				'user' => $user,
			)
		);
	}

	public function actionListTBR()
	{
		$this->render(
			'listTBR',
			array(
				'tbr' => $this->actionToBeReceived()
			)
		);
	}

	public function actionListTBRDispatch()
	{
		$this->render(
			'listTBRDispatch',
			array(
				'tbrdispatch' => $this->actionDispatchDocsToBeReceived()
			)
		);
	}

	public function actionListTBDispatch()
	{
		$this->render(
			'listTBDispatch',
			array(
				'tbdispatch' => $this->actionDispatchDocsToBeDispatched()
			)
		);
	}

	public function actionListTBMD()
	{
		$this->render(
			'listTBMD',
			array(
				'tbmd' => $this->actionToBeMarkedDisposed()
			)
		);
	}

	public function actionListTimeLimitCases()
	{
		$this->render(
			'listTimeLimitCases',
			array(
				'timeLimitCases' => $this->actionTimeLimitCases()
			)
		);
	}

	public function actionListpendingDispatch()
	{
		$this->render(
			'listpendingDispatch',
			array(
				'pendingDispatch' => $this->actionPendingDispatchDocs()
			)
		);
	}

	public function actionToBeReceived($countOnly = false)
	{

		$d_ids = User::model()->getListOfDesigByDutyID();
		$d_ids = array_keys($d_ids);
		$d_ids = implode(',', $d_ids);

		if ($countOnly) {
			$sql = "SELECT COUNT(mar.`id`)
					FROM `{{marked}}` AS mar
					JOIN `{{documents}}` AS docs ON mar.`document_id`=docs.`id` 
					WHERE `received_by` IS NULL
						AND `marked_to` IN (" . $d_ids . ")";
			$result = Yii::app()->db->createCommand($sql)->queryScalar();
			echo CJSON::encode(($result ? (int)$result : 0));
		} else {
			$sql = "SELECT mar.`id`, `document_id`, `marked_to`, `received_by`, `marked_date`, `reference_number`, `date_of_document`, `received_from`, `description`
					FROM `{{marked}}` AS mar
					JOIN `{{documents}}` AS docs ON mar.`document_id`=docs.`id` 
					WHERE `received_by` IS NULL
						AND `marked_to` IN (" . $d_ids . ")";

			$result = Yii::app()->db->createCommand($sql)->queryAll();

			return new CArrayDataProvider($result);
		}
	}

	public function actionToBeMarkedDisposed($countOnly = false)
	{
		$d_ids = User::model()->getListOfDesigByDutyID();
		$d_ids = array_keys($d_ids);
		$d_ids = implode(',', $d_ids);

		if ($countOnly) {
			$sql = "SELECT COUNT(a.`id`)
					FROM `{{marked}}` AS a
					INNER JOIN (
							SELECT 	`document_id`,
									`marked_to`,
									MAX(c.`create_time`) AS ct,
									`is_case_disposed`
							FROM `{{marked}}` AS c
								INNER JOIN `{{documents}}` AS d
									ON c.`document_id`=d.`id`
							GROUP BY c.`document_id`
							) AS b
						ON a.`document_id`=b.`document_id`
						AND a.`create_time`=b.`ct`
					WHERE `is_case_disposed`=0
						AND a.`received_by` IS NOT NULL
						AND a.`marked_to` IN (" . $d_ids . ")";
			$result = Yii::app()->db->createCommand($sql)->queryScalar();
			echo CJSON::encode(($result ? (int)$result : 0));
		} else {
			$sql = "SELECT a.`id`, a.`document_id`, a.`marked_to`, `received_by`, `marked_date`, b.`reference_number`, b.`date_of_document`, b.`received_from`, b.`description`
					FROM `{{marked}}` AS a
					INNER JOIN (
							SELECT 	`document_id`,
									`marked_to`,
									MAX(c.`create_time`) AS ct,
									`is_case_disposed`,
									`reference_number`,
									`date_of_document`,
									`received_from`,
									`description`
							FROM `{{marked}}` AS c
								INNER JOIN `{{documents}}` AS d
									ON c.`document_id`=d.`id`
							GROUP BY c.`document_id`
							) AS b
						ON a.`document_id`=b.`document_id`
						AND a.`create_time`=b.`ct`
					WHERE `is_case_disposed`=0
						AND a.`received_by` IS NOT NULL
						AND a.`marked_to` IN (" . $d_ids . ")";

			$result = Yii::app()->db->createCommand($sql)->queryAll();

			return new CArrayDataProvider($result);
		}
	}

	public function actionDispatchDocsToBeReceived($countOnly = false)
	{
		if ($countOnly) {
			$sql = "SELECT COUNT(`id`)
					FROM `{{dispatch_docs}}`
					WHERE `received_by` IS NULL";
			$result = Yii::app()->db->createCommand($sql)->queryScalar();
			echo CJSON::encode(($result ? (int)$result : 0));
		} else {
			$sql = "SELECT `id`, `reference_number`, `reference_dated`, `description`, `received_by`, `signed_by`, `addressed_to`, `copy_to` 
					FROM `{{dispatch_docs}}`
					WHERE `received_by` IS NULL";

			$result = Yii::app()->db->createCommand($sql)->queryAll();

			return new CArrayDataProvider($result);
		}
	}

	public function actionDispatchDocsToBeDispatched($countOnly = false)
	{
		if ($countOnly) {
			$sql = "SELECT COUNT(a.`id`)
					FROM `{{dispatch_docs}}` AS a
					LEFT JOIN `{{dispatch_details}}` AS b
						ON a.`id`=b.`disp_doc_id`
					WHERE b.`id` IS NULL
					AND a.`received_by` IS NOT NULL";
			$result = Yii::app()->db->createCommand($sql)->queryScalar();
			echo CJSON::encode(($result ? (int)$result : 0));
		} else {
			$sql = "SELECT a.`id`, `reference_number`, `reference_dated`, `description`, `received_by`, `signed_by`, `addressed_to`, `copy_to` 
					FROM `{{dispatch_docs}}` AS a
					LEFT JOIN `{{dispatch_details}}` AS b
						ON a.`id`=b.`disp_doc_id`
					WHERE b.`id` IS NULL
					AND a.`received_by` IS NOT NULL";

			$result = Yii::app()->db->createCommand($sql)->queryAll();

			return new CArrayDataProvider($result);
		}
	}

	public function actionPendingDispatchDocs($countOnly = false)
	{
		if ($countOnly) {
			$sql = "SELECT COUNT(a.`id`)
					FROM `{{dispatch_docs}}` AS a
					LEFT JOIN `{{dispatch_details}}` AS b
						ON a.`id`=b.`disp_doc_id`
					WHERE b.`id` IS NULL
					AND a.`create_user`=" . Yii::app()->user->id;
			$result = Yii::app()->db->createCommand($sql)->queryScalar();
			echo CJSON::encode(($result ? (int)$result : 0));
		} else {
			$sql = "SELECT a.`id`, `reference_number`, `reference_dated`, `description`, `received_by`, `signed_by`, `addressed_to`, `copy_to` 
					FROM `{{dispatch_docs}}` AS a
					LEFT JOIN `{{dispatch_details}}` AS b
						ON a.`id`=b.`disp_doc_id`
					WHERE b.`id` IS NULL
					AND a.`create_user`=" . Yii::app()->user->id;

			$result = Yii::app()->db->createCommand($sql)->queryAll();

			return new CArrayDataProvider($result);
		}
	}

	public function actionTimeLimitCases($countOnly = false)
	{
		$d_ids = User::model()->getListOfDesigByDutyID();
		$d_ids = array_keys($d_ids);
		$d_ids = implode(',', $d_ids);

		if ($countOnly) {
			$sql = 'SELECT COUNT(a.id)
					FROM {{documents}} AS a
					JOIN {{marked}} AS b
						ON a.id=b.document_id
					WHERE a.is_case_disposed=FALSE
						AND b.time_limit>0
						AND b.marked_to IN (' . $d_ids . ')';
			$result = Yii::app()->db->createCommand($sql)->queryScalar();
			echo CJSON::encode(($result ? (int)$result : 0));
		} else {
			$sql = 'SELECT a.id, b.marked_to, b.received_by, b.marked_date, a.reference_number, a.date_of_document, a.received_from, a.description, b.time_limit
					FROM {{documents}} AS a
					JOIN {{marked}} AS b
						ON a.id=b.document_id
					WHERE a.is_case_disposed=FALSE
						AND b.time_limit>0
						AND b.marked_to IN (' . $d_ids . ')';
			$result = Yii::app()->db->createCommand($sql)->queryAll();

			return new CArrayDataProvider($result);

		}
	}

	public function actionListFilesTBR()
	{
		$this->render(
			'listFilesTBR',
			array(
				'filestbr' => $this->actionFilesToBeReceived()
			)
		);
	}

	public function actionFilesToBeReceived($countOnly = false)
	{
		// select ids of duties assigned to this officer
		$d_ids = User::model()->getListOfDesigByDutyID();
		$d_ids = array_keys($d_ids);
		$d_ids = implode(',', $d_ids);

		if ($countOnly) {
			$sql = "SELECT COUNT(fm.`id`)
					FROM `{{file_movement}}` AS fm
					JOIN `{{files}}` AS files ON fm.`file_id`=files.`id` 
					WHERE `received_by` IS NULL
						AND `marked_to` IN (" . $d_ids . ")
						AND files.`enroute`=1";
			$result = Yii::app()->db->createCommand($sql)->queryScalar();
			echo CJSON::encode(($result ? (int)$result : 0));
		} else {
			$sql = "SELECT fm.`id`, `file_id`, `marked_to`, `received_by`, `marked_date`, `number`, `section_id`, `description`
					FROM `{{file_movement}}` AS fm
					JOIN `{{files}}` AS files ON fm.`file_id`=files.`id` 
					WHERE `received_by` IS NULL
						AND `marked_to` IN (" . $d_ids . ")
						AND files.`enroute`=1";

			$result = Yii::app()->db->createCommand($sql)->queryAll();

			return new CArrayDataProvider($result);
		}
	}

	public function actionListFilesInHand()
	{
		$this->render(
			'listFilesInHand',
			array(
				'filesInHand' => $this->actionFilesInHand()
			)
		);
	}

	public function actionFilesInHand($countOnly = false)
	{
		// list duty ids attached with the officer
		$d_ids = User::model()->getListOfDesigByDutyID();
		$d_ids = array_keys($d_ids);
		$d_ids = implode(',', $d_ids);

		if ($countOnly) {
			$sql = "SELECT COUNT(a.`id`)
					FROM `{{files}}` AS a
					INNER JOIN (
							SELECT 	c.`id` fm_id,
									c.`file_id`,
								  	c.`marked_to`,
									c.`received_by`
							FROM `{{file_movement}}` AS c
							JOIN (SELECT max(x.id) as id, x.file_id FROM {{file_movement}} as x Group by x.file_id) as d
							ON c.id=d.id
							WHERE c.`received_by` IS NOT NULL
							GROUP BY c.`file_id`
							ORDER BY c.`id` DESC
							
							) AS b
						ON a.`id`=b.`file_id`
					WHERE `enroute`=1
						AND b.`marked_to` IN (" . $d_ids . ")";
			$result = Yii::app()->db->createCommand($sql)->queryScalar();
			echo CJSON::encode((!empty($result) ? (int)$result : 0));
		} else {
			$sql = "SELECT a.`id`, a.`number`, a.`description`, b.`marked_date`, b.`marked_by`, b.fm_id
			FROM `{{files}}` AS a
			INNER JOIN (
					SELECT 	c.id fm_id,
							c.`file_id`,
							c.`marked_to`,
							c.`received_by`,
							c.`marked_date`,
							c.`marked_by`
					FROM `{{file_movement}}` AS c
					JOIN (SELECT max(x.id) as id, x.file_id FROM {{file_movement}} AS x GROUP BY x.file_id) AS d
					ON c.id=d.id
					WHERE c.`received_by` IS NOT NULL
					GROUP BY c.`file_id`
					ORDER BY c.`id` DESC
					
					) AS b
				ON a.`id`=b.`file_id`
			WHERE `enroute`=1
				AND b.`marked_to` IN (" . $d_ids . ")";

			$result = Yii::app()->db->createCommand($sql)->queryAll();

			return new CArrayDataProvider($result);
		}

	}

	public function actionToast() {
		// list duty ids attached with the officer
		$d_ids = User::model()->getListOfDesigByDutyID();
		$d_ids = array_keys($d_ids);
		$d_ids = implode(',', $d_ids);

		$sql = "SELECT COUNT(a.`id`)
					FROM `{{files}}` AS a
					INNER JOIN (
							SELECT 	c.`id` fm_id,
									c.`file_id`,
								  	c.`marked_to`,
									c.`received_by`
							FROM `{{file_movement}}` AS c
							WHERE c.`received_by` IS NOT NULL
							GROUP BY c.`file_id`
							ORDER BY c.`id` DESC
							
							) AS b
						ON a.`id`=b.`file_id`
					WHERE `enroute`=1
						AND b.`marked_to` IN (" . $d_ids . ")";
		$filesInHand = Yii::app()->db->createCommand($sql)->queryScalar();
		
		$sql = "SELECT COUNT(a.`id`)
					FROM `{{marked}}` AS a
					INNER JOIN (
							SELECT 	`document_id`,
									`marked_to`,
									MAX(c.`create_time`) AS ct,
									`is_case_disposed`
							FROM `{{marked}}` AS c
								INNER JOIN `{{documents}}` AS d
									ON c.`document_id`=d.`id`
							GROUP BY c.`document_id`
							) AS b
						ON a.`document_id`=b.`document_id`
						AND a.`create_time`=b.`ct`
					WHERE `is_case_disposed`=0
						AND a.`received_by` IS NOT NULL
						AND a.`marked_to` IN (" . $d_ids . ")";
		$docsInHand = Yii::app()->db->createCommand($sql)->queryScalar();

		echo CJSON::encode(
						array(
							'docs' => $docsInHand, 
							'files'=> $filesInHand
						)
					);
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if ($error = Yii::app()->errorHandler->error) {
			if (Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model = new ContactForm;
		if (isset($_POST['ContactForm'])) {
			$model->attributes = $_POST['ContactForm'];
			if ($model->validate()) {
				$name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
				$subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
				$headers = "From: $name <{$model->email}>\r\n" .
					"Reply-To: {$model->email}\r\n" .
					"MIME-Version: 1.0\r\n" .
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
				Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact', array('model' => $model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model = new LoginForm;

		// if it is ajax validation request
		if (isset($_POST['ajax']) && $_POST['ajax'] == 'login-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if (isset($_POST['LoginForm'])) {
			$model->attributes = $_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if ($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login', array('model' => $model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
