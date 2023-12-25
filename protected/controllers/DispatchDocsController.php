<?php

class DispatchDocsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl',
			// perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
				'actions' => array('index', 'view', 'receive', 'imagesAjax'),
				'users' => array('@'),
			),
			array(
				'allow',
				// allow authenticated user to perform 'create' and 'update' actions
				'actions' => array('create', 'update','admin'),
				'users' => array('@'),
			),
			array(
				'allow',
				// allow admin user to perform 'admin' and 'delete' actions
				'actions' => array( 'delete'),
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

		$dispatch_details = new CActiveDataProvider(
			'DispatchDetails',
			array(
				'criteria' => array(
					'condition' => 'disp_doc_id=:documentID',
					'params' => array(':documentID' => $id),
				),
				'pagination' => array(
					'pageSize' => 50,
				)
			)
		);

		$this->render('view', array(
			'model' => $this->loadModel($id),
			'dispatch_details' => $dispatch_details,
		)
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new DispatchDocs;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['disposal_id']))
			$disposal_id = $_POST['disposal_id'];
		else if (isset($_GET['disposal_id']))
			$disposal_id = $_GET['disposal_id'];

		if (!empty($disposal_id)) {
			$disposal = Disposal::model()->findByPk($disposal_id);
			$model->disposal_id = $disposal->id;
			$model->document_id = $disposal->document_id;
			$model->description = $disposal->document->description;
			$model->signed_by = $disposal->officer_id;
			$model->reference_number = $disposal->disposal_ref_number;
			$model->reference_dated = $disposal->reference_dated;
			$model->addressed_to = $disposal->document->received_from;
		}

		if (isset($_POST['DispatchDocs'])) {
			$model->attributes = $_POST['DispatchDocs'];

			// Gets the files uploaded with the dispatch
			$uploadedFiles = CUploadedFile::getInstancesByName('image');

			if ($model->save()) {
				// Checks if there were files uploaded
				if (isset($uploadedFiles) && count($uploadedFiles) > 0) {
					// save each image and its path in DispDocsImages model
					foreach ($uploadedFiles as $upfile => $image) {
						$img = Yii::app()->image->load($image->getTempName());
						if ($img->width > 900) {
							$img->resize(900, 500, Image::WIDTH);
						}
						//$img->gamma_correction(1.0, 0.50); //My addition to the extension
						//$img->sharpen(50);

						$folder = Yii::getPathOfAlias('webroot.dispatch_doc_images') . DIRECTORY_SEPARATOR; // folder for uploaded files

						if (!is_dir($folder . date('Ym')))
							mkdir($folder . date('Ym')); //sudo chown www-data -R disposal_images

						$filename = date('Ym') . '/' . $model->id . '-' . 'doc' . '[' . $upfile . '].' . $image->getExtensionName();
						if ($img->save($folder . $filename)) {
							$image_model = new DispDocsImages;
							$image_model->setIsNewRecord(true);
							$image_model->disp_doc_id = $model->id;
							$image_model->image_path = $filename;
							$image_model->save();
						}
					}
				}

				$this->redirect(array('view', 'id' => $model->id));
			}
		}

		$this->render('create', array(
			'model' => $model,
		)
		);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		if (!$model->isOwner(Yii::app()->user->id))
			throw new CHttpException(403, 'You are not authorized to edit this Dispatch Document.');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['DispatchDocs'])) {
			$model->attributes = $_POST['DispatchDocs'];
			if ($model->save()){
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

						$folder = Yii::getPathOfAlias('webroot.dispatch_doc_images') . DIRECTORY_SEPARATOR; // folder for uploaded files

						// $month_folder = substr($model->disposal_date, 0, 4) . substr($model->disposal_date, 5, 2);
						$month_folder = date('Ym', strtotime($model->create_time));
						$filename = $month_folder . $model->id . '-' . 'doc' . '[' . $idx . '].' . $image->getExtensionName();
						if ($img->save($folder . $filename)) {
							$image_model = new DispDocsImages;
							$image_model->setIsNewRecord(true);
							$image_model->disp_doc_id = $model->id;
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
						$elementName = $image['id'] . '-' . $image['disp_doc_id'] . '-' . $idx;
						if (isset($_POST[$elementName]) && $_POST[$elementName] == "delete") {
							$image_model = DispDocsImages::model()->findByPk($image['id']);
							if (unlink($folder . $image['image_path']))
								$image_model->delete();

						}
					}
				}
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
		// $dataProvider = new CActiveDataProvider('DispatchDocs');
		// $this->render('index', array(
		// 	'dataProvider' => $dataProvider,
		// )
		// );
		$this->actionAdmin();
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new DispatchDocs('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['DispatchDocs']))
			$model->attributes = $_GET['DispatchDocs'];

		$this->render('admin', array(
			'model' => $model,
		)
		);
	}

	public function actionReceive($id)
	{
		$model = $this->loadModel($id);
		$user = User::model()->findByPk(Yii::app()->user->id);

		if ($model->isReceived())
			throw new CHttpException(403, 'This dispatch document has already been received.');

		if (!$user->user_type=='dispatcher')
			throw new CHttpException(403, 'You are not authorized to receive this dispatch document.');

		$model->received_by = $user->id;
		$model->received_timestamp = new CDbExpression('NOW()');

		if ($model->save(false)) {
			$this->redirect(Yii::app()->request->UrlReferrer);
		} else {
			throw new CHttpException(404, 'Unable to receive this document.');
		}
		
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return DispatchDocs the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = DispatchDocs::model()->findByPk($id);
		if ($model == null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param DispatchDocs $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] == 'dispatch-docs-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/**
	 * Views all attached images of the documents.
	 */
	public function actionImagesAjax($id)
	{
		$model = $this->loadModel($id);
		$this->renderPartial('_dispdocimages', $model);
	}

	/**
	 * returns next index for the file to be saved
	 * @param integer $documentID to look for in the database
	 * @return integer next index for file
	 */
	protected function nextIndexForFile($documentID)
	{
		$doc_images = DispDocsImages::model()->findAllByAttributes(array('disp_doc_id' => $documentID));
		$i = 0;
		foreach ($doc_images as $img) {
			$_o_position = strpos($img['image_path'], '[');
			$_c_position = strpos($img['image_path'], ']');
			$num = substr($img['image_path'], $_o_position + 1, $_c_position - $_o_position - 1);
			if ($i < $num)
				$i = $num;
		}
		return ++$i;
	}	
}