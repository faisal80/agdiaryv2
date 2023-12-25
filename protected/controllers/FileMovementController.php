<?php

class FileMovementController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

	/**
	 * @var private property containing the associated File model instance.
	 */
	private $_file = null;
	private $_marked_by = null; //gets from file model in case of new marking.
	private $_marked_to = null; //get from file model
	private $_document_id = null; //gets from file's last marking
	private $_movement_count = 0; //gets from files's last marking
	
	private $_file_page_no = null;
	/**
	 * Protected method to load the associated File model class
	 * @file_id the primary identifier of the associated File
	 * @return object the File data model based on the primary key 
	 */
	protected function loadFile($file_id)
	{
		//if the file property is null, create it based on input id
		if ($this->_file == null) {
			$this->_file = File::model()->findbyPk($file_id);
			if ($this->_file == null) {
				throw new CHttpException(404, 'The requested file does not exist.');
			}
			// $_duty = Duty::model()->findByPk($this->_file->owner_id);
			$lastMarking = $this->_file->LastMarking();
			if ($lastMarking) {
				$this->_marked_by = $lastMarking->marked_by;
				$this->_marked_to = $lastMarking->marked_to;
				$this->_document_id = !empty($lastMarking->document_id) ? $lastMarking->document_id : null;
				$this->_movement_count = $lastMarking->movement_count;
				if ($this->_document_id)
					$this->_file_page_no = Document::model()->findByPk($this->_document_id)->page_no_in_file;
			}
		}
		return $this->_file;
	}


	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl',
			// perform access control for CRUD operations
			'postOnly + delete',
			// we only allow deletion via POST request
			'fileContext + create',
			//check to ensure valid file context
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
				'actions' => array('index', 'view', 'receive'),
				'users' => array('@'),
			),
			array(
				'allow',
				// allow authenticated user to perform 'create' and 'update' actions
				'actions' => array('create', 'update', 'admin'),
				'users' => array('@'),
			),
			array(
				'allow',
				// allow admin user to perform 'admin' and 'delete' actions
				'actions' => array('delete'),
				'users' => array('admin'),
			),
			array(
				'deny',
				// deny all users
				'users' => array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render(
			'view',
			array(
				'model' => $this->loadModel($id),
			)
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$lastMarking = null;

		if ($this->_file->enroute) {
			$lastMarking = $this->_file->LastMarking();
			if (empty($lastMarking->received_by)) {
				throw new CHttpException(412, 'You have to receive this file first.');
			}
		}

		if (Yii::app()->user->canMarkFile($this->_file)) {
			$model = new FileMovement;
			// assign document id
			if ($this->_file->enroute) {
				$model->document_id = $this->_document_id;
				$model->marked_by = $this->_marked_to;
				$model->file_page_no = $this->_file_page_no;		
			}
			// if being routed freshly increase movement_count
			// else assign previous movement_count
			if (!$this->_file->enroute) {
				$this->_movement_count = $this->_movement_count+1;
			}
				$model->movement_count = $this->_movement_count;

			$model->file_id = $this->_file->id;	
			//$model->marked_by = $this->_marked_by;
			//$model->time_limit = $this->_time_limit;
			// Uncomment the following line if AJAX validation is needed
			// $this->performAjaxValidation($model);

			if (isset($_POST['FileMovement'])) {
				$model->attributes = $_POST['FileMovement'];
				if ($model->save()) {
					$file = File::model()->findByPk($model->file_id);
					$file->enroute = 1;
					$file->save(false);
					$document = Document::model()->findByPk($model->document_id);
					if (!empty($document)){
						// if (!is_null($model->file_page_no))
						$document->page_no_in_file = $model->file_page_no;
						$document->save(false);
					}
					$this->redirect(array('file/view', 'id' => $model->file_id));
				}
			}

			$this->render(
				'create',
				array(
					'model' => $model,
					'file_number' => $this->_file->number,
				)
			);
		} else {
			throw new CHttpException(403, Yii::t('yii', 'You are not authorized to mark this file.'));
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['FileMovement'])) {
			$model->attributes = $_POST['FileMovement'];
			if ($model->save())
				$this->redirect(array('view', 'id' => $model->id));
		}

		$this->render(
			'update',
			array(
				'model' => $model,
			)
		);
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('FileMovement');
		$this->render(
			'index',
			array(
				'dataProvider' => $dataProvider,
			)
		);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new FileMovement('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['FileMovement']))
			$model->attributes = $_GET['FileMovement'];

		$this->render(
			'admin',
			array(
				'model' => $model,
			)
		);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return FileMovement the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = FileMovement::model()->findByPk($id);
		if ($model == null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param FileMovement $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] == 'file-movement-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/**
	 * Receive the file by populating the received_by and received_time_stamp fields
	 * if the file is marked to the duty attached with this user.
	 */
	public function actionReceive($fm_id)
	{
		// Load Marked model
		$model = $this->loadModel($fm_id);
		$this->loadFile($model->file_id);

		if (!$model->isReceived()) { // if model is not received already
			// if the duty to which file is marked is attached to user->officer
			if ($model->marked_to_duty->isAttachedToUser(Yii::app()->user->id)) {

				// if the officer_id attached to current user has already marked this file then throw exception
				if ($this->_file->LastMarking()->marked_by == $model->marked_to_duty->id) {
					throw new CHttpException(409, 'This file has already been marked by ' . $model->marked_by_duty->designation->title . ' (' . $model->marked_by_duty->short_form . ')');
				}

				//if the file is not marked to this officer(duty) then throw exception
				if ($this->_file->LastMarking()->marked_to !== $model->marked_to_duty->id) {
					throw new CHttpException(409, 'This file is not marked to ' . $model->marked_to_duty->title);
				}
			}

			$model->received_by = Yii::app()->user->id;
			$model->received_time_stamp = new CDbExpression('NOW()');

			if ($model->save(false)) {
				$this->redirect(Yii::app()->request->UrlReferrer);
			} else {
				throw new CHttpException(404, 'Unable to receive this file.');
			}

		} else {
			throw new CHttpException(409, 'The file has already been received.');
		}
	}

	/**
	 * In-class defined filter method, configured for use in the above filters() method
	 * It is called before the actionCreate() action method is run in order to ensure a proper file context
	 */
	public function filterFileContext($filterChain)
	{
		//set the file identifier based on either the GET or POST input 
		//request variables, since we allow both types for our actions   
		$fileId = null;
		if (isset($_GET['fileid']))
			$fileId = $_GET['fileid'];
		else
			if (isset($_POST['fileid']))
				$fileId = $_POST['fileid'];
		$this->loadFile($fileId);
		//complete the running of other filters and execute the requested action
		$filterChain->run();
	}

}
