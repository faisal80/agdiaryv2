<?php

class DisposalController extends Controller
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
			//$_duty = Duties::model()->findByPk($this->_document->owner_id);
			//$this->_marked_by= $_duty->assigned_to->id;
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
				'actions' => array('index', 'view', 'imagesAjax'),
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
		if ($this->_document->is_case_disposed)
			throw new CHttpException(403, 'The case has already been disposed.');
		
		$lastMarking = $this->_document->LastMarking();

		if (Yii::app()->user->canDispose($this->_document) && empty($lastMarking->received_by))
			throw new CHttpException(403, 'You have to receive this document first.');

		if (Yii::app()->user->canDispose($this->_document)) {
			if (is_null($this->_document->LastMarking()))
				throw new CHttpException(409, 'The document is not marked to anyone. This document needs to be marked first.');

			$model = new Disposal;

			// Set todays date for the disposal_date field
			$model->disposal_date = date('Y-m-d');

			//assign document id
			$model->document_id = $this->_document->id;

			$model->officer_id = User::model()->findByPk(Yii::app()->user->id)->getAttachedDutyIDs()[0];
			//$model->
			// Uncomment the following line if AJAX validation is needed
			// $this->performAjaxValidation($model);

			if (isset($_POST['Disposal'])) {
				$model->attributes = $_POST['Disposal'];
				if ($model->disposal_type == 'other')
					$model->reference_dated = null;

				// Gets the files uploaded with the disposal
				$uploadedFiles = CUploadedFile::getInstancesByName('image');

				if ($model->save()) {
					// Checks if there were files uploaded
					if (isset($uploadedFiles) && count($uploadedFiles) > 0) {
						// save each image and its path in DisposalImage model
						foreach ($uploadedFiles as $upfile => $image) {
							$img = Yii::app()->image->load($image->getTempName());
							if ($img->width > 900) {
								$img->resize(900, 500, Image::WIDTH);
							}
							//$img->gamma_correction(1.0, 0.50); //My addition to the extension
							//$img->sharpen(50);

							$folder = Yii::getPathOfAlias('webroot.disposal_images') . DIRECTORY_SEPARATOR; // folder for uploaded files

							if (!is_dir($folder . date('Ym')))
								mkdir($folder . date('Ym')); //sudo chown www-data -R disposal_images

							$filename = date('Ym') . '/' . $model->id . '-' . 'doc' . '[' . $upfile . '].' . $image->getExtensionName();
							if ($img->save($folder . $filename)) {
								$image_model = new DisposalImage;
								$image_model->setIsNewRecord(true);
								$image_model->disposal_id = $model->id;
								$image_model->image_path = $filename;
								$image_model->save();
							}
						}
					}

					$document = $model->document;
					$document->is_case_disposed = $model->isDisposed;
					$document->save(false);

					$this->redirect(array('/document/view', 'id' => $model->document_id));
				}
			}

			$this->render('create', array(
				'model' => $model,
			)
			);
		} else {
			throw new CHttpException(403, Yii::t('yii', 'You are not authorized to dispose off this document.'));
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
		$model->isDisposed = $model->document->is_case_disposed;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Disposal'])) {
			$model->attributes = $_POST['Disposal'];
			if ($model->save()) {
				$uploadedFiles = CUploadedFile::getInstancesByName('image');
				if (isset($uploadedFiles) && count($uploadedFiles) > 0) {
					foreach ($uploadedFiles as $upfile => $image) {
						$img = Yii::app()->image->load($image->getTempName());
						if ($img->width > 900) {
							$img->resize(900, 500, Image::WIDTH);
						}

						//$img->gamma_correction(1.0, 0.50); //My addition to the extension
						//$img->sharpen(50);

						$idx = $this->nextIndexForFile($model->id);

						$folder = Yii::getPathOfAlias('webroot.disposal_images') . DIRECTORY_SEPARATOR; // folder for uploaded files

						$month_folder = substr($model->disposal_date, 0, 4) . substr($model->disposal_date, 5, 2);
						$filename = $month_folder . $model->id . '-' . 'doc' . '[' . $idx . '].' . $image->getExtensionName();
						if ($img->save($folder . $filename)) {
							$image_model = new DisposalImage;
							$image_model->setIsNewRecord(true);
							$image_model->disposal_id = $model->id;
							$image_model->image_path = $filename;
							$image_model->save();
							unset($image_model);
						}
						unset($uploadedFiles);
					}
				}

				$images = $model->images;
				if (!empty($images)) {
					foreach ($images as $idx => $image) {
						$elementName = $image['id'] . '-' . $image['disposal_id'] . '-' . $idx;
						if (isset($_POST[$elementName]) && $_POST[$elementName] == "delete") {
							$image_model = DisposalImage::model()->findByPk($image['id']);
							if (unlink($folder . $image['image_path']))
								$image_model->delete();

						}
					}
				}

				$document = $model->document;
				$document->is_case_disposed = $model->isDisposed;
				$document->save(false);
				$this->redirect(array('view', 'id' => $model->id));
			}
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
		$dataProvider = new CActiveDataProvider('Disposal');
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
		$model = new Disposal('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['Disposal']))
			$model->attributes = $_GET['Disposal'];

		$this->render('admin', array(
			'model' => $model,
		)
		);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Disposal the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Disposal::model()->findByPk($id);
		if ($model == null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Disposal $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] == 'disposal-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/**
	 * returns next index for the file to be saved
	 * @param integer $disposalID to look for in the database
	 * @return integer next index for file
	 */
	protected function nextIndexForFile($disposalID)
	{
		$disposal_images = DisposalImage::model()->findAllByAttributes(array('disposal_id' => $disposalID));
		$i = 0;
		foreach ($disposal_images as $img) {
			$_o_position = strpos($img['image_path'], '[');
			$_c_position = strpos($img['image_path'], ']');
			$num = substr($img['image_path'], $_o_position + 1, $_c_position - $_o_position - 1);
			if ($i < $num)
				$i = $num;
		}
		return ++$i;
	}

	/**
	 * Views all attached images of the disposals.
	 */
	public function actionImagesAjax($id)
	{
		$model = $this->loadModel($id);
		$this->renderPartial('_disposalimages', $model);
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
