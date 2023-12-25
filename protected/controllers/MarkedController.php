<?php

class MarkedController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

	/**
	 * @var private property containing the associated Document model instance.
	 */
	private $_document = null;
	private $_marked_by = null; //gets from document model in case of new marking.
	private $_time_limit = null; //gets from document's last marking

	/**
	 * Protected method to load the associated Document model class
	 * @document_id the primary identifier of the associated Document
	 * @return object the Document data model based on the primary key 
	 */
	protected function loadDocument($document_id)
	{
		//if the document property is null, create it based on input id
		if ($this->_document == null) {
			$this->_document = Document::model()->findbyPk($document_id);
			if ($this->_document == null) {
				throw new CHttpException(404, 'The requested document does not exist.');
			}
			$_duty = Duty::model()->findByPk($this->_document->owner_id);
			$this->_marked_by = $_duty->assigned_to->id;
			$this->_time_limit = !empty($this->_document->LastMarking()->time_limit) ? $this->_document->LastMarking()->time_limit : null;
		}
		return $this->_document;
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
			'documentContext + create', //check to ensure valid document context
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
				'actions' => array('index', 'view'),
				'users' => array('*'),
			),
			array(
				'allow',
				// allow authenticated user to perform 'create' and 'update' actions
				'actions' => array('create', 'update', 'receive', 'admin'),
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
		$model = $this->loadModel($id);
		$count = $model->count();

		$this->render('view', array(
			'model' => $model,
			'count' => $count,
		)
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$lastMarking = $this->_document->LastMarking();

		if (Yii::app()->user->canMark($this->_document) && (empty($lastMarking->received_by) && !Yii::app()->user->isOwner($this->_document)))
			throw new CHttpException(403, 'You have to receive this document first.');

		if (Yii::app()->user->canMark($this->_document)) {
			$model = new Marked;
			//assign document id
			$model->document_id = $this->_document->id;
			$model->marked_by = $this->_marked_by;
			$model->time_limit = $this->_time_limit;
			// Uncomment the following line if AJAX validation is needed
			// $this->performAjaxValidation($model);

			if (isset($_POST['Marked'])) {
				$model->attributes = $_POST['Marked'];
				if ($model->save())
					$this->redirect(array('/document/view', 'id' => $model->document_id));
			}

			$this->render('create', array(
				'model' => $model,
			)
			);
		} else {
			throw new CHttpException(403, Yii::t('yii', 'You are not authorized to mark this document.'));
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

		if (isset($_POST['Marked'])) {
			$model->attributes = $_POST['Marked'];
			if ($model->save())
				$this->redirect(array('/document/view', 'id' => $model->document_id));
		}

		$this->render('update', array(
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
		$dataProvider = new CActiveDataProvider('Marked');
		$this->render('index', array(
			'dataProvider' => $dataProvider,
		)
		);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new Marked('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['Marked']))
			$model->attributes = $_GET['Marked'];

		$this->render('admin', array(
			'model' => $model,
		)
		);
	}

	/**
	 * Receive the document by populating the received_by and received_time_stamp fields
	 * if the document is marked to the duty attached with this user.
	 */
	public function actionReceive($m_id)
	{
		// Load Marked model
		$model = $this->loadModel($m_id);
		$this->loadDocument($model->document_id);

		if (!$model->isReceived()) { // if model is not received already
			// if the duty to which document is marked is attached to user->officer
			if ($model->marked_to_duty->isAttachedToUser(Yii::app()->user->id)) {

				// if the officer_id attached to current user has already marked this document then throw exception
				if ($this->_document->LastMarking()->marked_by == $model->marked_to_duty->id) {
					throw new CHttpException(409, 'This document has already been marked by ' . $model->marked_by_duty->designation->title . ' (' . $model->marked_by_duty->short_form . ')');
				}

				//if the document is not marked to this officer(duty) then throw exception
				if ($this->_document->LastMarking()->marked_to !== $model->marked_to_duty->id) {
					throw new CHttpException(409, 'This document is not marked to ' . $model->marked_to_duty->title);
				}
			}

			$model->received_by = Yii::app()->user->id;
			$model->received_time_stamp = new CDbExpression('NOW()');

			if ($model->save(false)) {
				$this->redirect(Yii::app()->request->UrlReferrer);
			} else {
				throw new CHttpException(404, 'Unable to receive this document.');
			}

		} else {
			throw new CHttpException(409, 'The document has already been received.');
		}
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Marked the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Marked::model()->findByPk($id);
		if ($model == null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Marked $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] == 'marked-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/**
	 * In-class defined filter method, configured for use in the above filters() method
	 * It is called before the actionCreate() action method is run in order to ensure a proper document context
	 */
	public function filterDocumentContext($filterChain)
	{
		//set the document identifier based on either the GET or POST input 
		//request variables, since we allow both types for our actions   
		$documentId = null;
		if (isset($_GET['docid']))
			$documentId = $_GET['docid'];
		else
			if (isset($_POST['docid']))
				$documentId = $_POST['docid'];
		$this->loadDocument($documentId);
		//complete the running of other filters and execute the requested action
		$filterChain->run();
	}

	/**
	 * Returns the Document model instance to which this issue belongs
	 */
	public function getDocument()
	{
		return $this->_document;
	}

}