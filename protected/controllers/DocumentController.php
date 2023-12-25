<?php

class DocumentController extends Controller
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
				'actions' => array('index', 'view', 'imagesajax', 'search', 'receivedfromarray', 'tags', 'listofdocs'),
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
		$file = null;

		if (!Yii::app()->user->canView($model))
			throw new CHttpException(403, Yii::t('yii', 'You are not authorized to view this document.'));

		$markings = new CActiveDataProvider(
			'Marked',
			array(
				'criteria' => array(
					'condition' => 'document_id=:documentID',
					'params' => array(':documentID' => $id),
				),
				'pagination' => array(
					'pageSize' => 50,
				)
			)
		);

		$file_movements = $model->file_movements;
		if (!empty($file_movements)) {
			$file = $file_movements[0]->file;
		}

		$disposals = new CActiveDataProvider(
			'Disposal',
			array(
				'criteria' => array(
					'condition' => 'document_id=:documentID',
					'params' => array(':documentID' => $id),
				),
				'pagination' => array(
					'pageSize' => 50,
				)
			)
		);

		$dispatch = new CActiveDataProvider(
			'DispatchDocs',
			array(
				'criteria' => array(
					'condition' => 'document_id=:documentID',
					'params' => array(':documentID' => $id),
				),
				'pagination' => array(
					'pageSize' => 50,
				)
			)
		);

		$count = $model->count();

		$this->render(
			'view',
			array(
				'model' => $model,
				'markings' => $markings,
				'count' => $count,
				'disposals'=>  $disposals,
				'dispatch' => $dispatch,
				'file' => $file,
			)
		);

	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		//xdebug_break();
		if (Yii::app()->user->canCreateDoc()) {
			$model = new Document;

			// Set todays date for the document_received field
			$model->date_received = date('Y-m-d');

			// Uncomment the following line if AJAX validation is needed
			// $this->performAjaxValidation($model);

			if (isset($_POST['Document'])) {
				$model->attributes = $_POST['Document'];
				$model->date_of_document = !empty($model->date_of_document) ? $model->date_of_document : null;
				// Gets the files uploaded with the document
				$uploadedFiles = CUploadedFile::getInstancesByName('image');

				if ($model->save()) {
					// Checks if there were files uploaded
					if (isset($uploadedFiles) && count($uploadedFiles) > 0) {
						// save each image and its path in DocumentImages model
						foreach ($uploadedFiles as $upfile => $image) {
							$img = Yii::app()->image->load($image->getTempName());
							if ($img->width > 900) {
								$img->resize(900, 500, Image::WIDTH);
							}
							//$img->gamma_correction(1.0, 0.50); //My addition to the extension
							//$img->sharpen(50);

							$folder = Yii::getPathOfAlias('webroot.document_images') . DIRECTORY_SEPARATOR; // folder for uploaded files

							if (!is_dir($folder . date('Ym')))
								mkdir($folder . date('Ym')); //sudo chown www-data -R document_images

							$filename = date('Ym') . '/' . $model->id . '-' . 'doc' . '[' . $upfile . '].' . $image->getExtensionName();
							if ($img->save($folder . $filename)) {
								$image_model = new DocumentImage;
								$image_model->setIsNewRecord(true);
								$image_model->document_id = $model->id;
								$image_model->image_path = $filename;
								$image_model->save();
							}
						}
					}
					$this->redirect(array('/marked/create', 'docid' => $model->id));
				}
			}

			$this->render(
				'create',
				array(
					'model' => $model,
				)
			);
		} else {
			throw new CHttpException(403, Yii::t('yii', 'You are not authorized to create new document.'));
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		// xdebug_break();
		$model = $this->loadModel($id);

		if (!Yii::app()->user->canMark($model))
			throw new CHttpException(403, Yii::t('yii', 'You are not authorized to edit this document.'));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Document'])) {
			$model->attributes = $_POST['Document'];
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

						$folder = Yii::getPathOfAlias('webroot.document_images') . DIRECTORY_SEPARATOR; // folder for uploaded files

						$month_folder = substr($model->date_received, 0, 4) . substr($model->date_received, 5, 2);
						$filename = $month_folder . $model->id . '-' . 'doc' . '[' . $idx . '].' . $image->getExtensionName();
						if ($img->save($folder . $filename)) {
							$image_model = new DocumentImage;
							$image_model->setIsNewRecord(true);
							$image_model->document_id = $model->id;
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
						$elementName = $image['id'] . '-' . $image['document_id'] . '-' . $idx;
						if (isset($_POST[$elementName]) && $_POST[$elementName] == "delete") {
							$image_model = DocumentImage::model()->findByPk($image['id']);
							if (unlink($folder . $image['image_path']))
								$image_model->delete();

						}
					}
				}

				$this->redirect(array('view', 'id' => $model->id));
			}
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
		$_model = $this->loadModel($id);
		if (Yii::app()->user->isOwner($_model)) {
			$_images = $_model->images;
			foreach ($_images as $_image) {
				$_image->delete();
			}
			$_model->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if (!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		} else {
			throw new CHttpException(403, Yii::t('yii', 'You are not authorized to delete this document.'));
		}

	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		//		$dataProvider=new CActiveDataProvider('Documents');
//		$this->render('index',array(
//			'dataProvider'=>$dataProvider,
//		));
		$this->actionAdmin();
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new Document('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['Document']))
			$model->attributes = $_GET['Document'];

		$this->render(
			'admin',
			array(
				'model' => $model,
			)
		);
	}

	/**
	 * Search documents.
	 */
	public function actionSearch()
	{
		$model = new Document('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['Document']))
			$model->attributes = $_GET['Document'];

		$this->render(
			'search',
			array(
				'model' => $model,
			)
		);
	}

	/**
	 * Views all attached images of the documents.
	 */
	public function actionImagesAjax($id)
	{
		$model = $this->loadModel($id);
		$this->renderPartial('_documentimages', $model);
	}

	/**
	 * Returns list of received_from field for ajax request.
	 */
	public function actionReceivedFromArray()
	{
		if (isset($_GET['term'])) {
			$qtxt = "SELECT received_from 
					FROM {{documents}}  
					WHERE received_from LIKE '%" . $_GET['term'] . "%' 
					GROUP BY received_from";
			$command = Yii::app()->db->createCommand($qtxt);
			$res = $command->queryColumn();

			echo CJSON::encode($res);
			Yii::app()->end();
		}
	}

	/**
	 * Returns list of tags field for ajax request.
	 */
	public function actionTags()
	{
		if (isset($_GET['term'])) {
			$qtxt = "SELECT tags 
					FROM {{documents}}  
					WHERE tags LIKE '" . $_GET['term'] . "%' 
					GROUP BY tags";
			$command = Yii::app()->db->createCommand($qtxt);
			$res = $command->queryColumn();

			echo CJSON::encode($res);
			Yii::app()->end();
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Document the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Document::model()->findByPk($id);
		if ($model == null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Document $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] == 'documents-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/**
	 * returns next index for the file to be saved
	 * @param integer $documentID to look for in the database
	 * @return integer next index for file
	 */
	protected function nextIndexForFile($documentID)
	{
		$doc_images = DocumentImage::model()->findAllByAttributes(array('document_id' => $documentID));
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

	/**
	 * This function generates list of documents for receiving.
	 */
	public function actionListofdocs()
	{
		$this->layout = '//layouts/column1';
		
		$ListofDocsForm = new ListofDocsForm;

		if (isset($_POST['ListofDocsForm'])) {
			$ListofDocsForm->attributes = $_POST['ListofDocsForm'];

			//$_report = array();
			$_user = User::model()->findByPk(Yii::app()->user->id);
			
			// get duty ids of officers attached with the user
			$d_ids = User::model()->getListOfDesigByDutyID();
			$d_ids = array_keys($d_ids);
			$d_ids = implode(',', $d_ids);
			
			//lists all documents marked by the current officer
			$_all_documents = "SELECT a.id, date_received, reference_number, date_of_document, received_from, description, b.marked_to, b.id AS marked_id 
							FROM {{documents}} AS a
							INNER JOIN {{marked}} AS b
								ON a.id=b.document_id
							WHERE b.marked_by IN (" . $d_ids . ")
								AND b.marked_to=" . $ListofDocsForm->officer_id . "  
								AND b.received_by IS NULL 
							ORDER BY a.id";

			$_all_documents = Yii::app()->db->createCommand($_all_documents)->queryAll();

			if (!empty($_all_documents)) {
				$dataProvider = new CArrayDataProvider(
					$_all_documents,
					array(
						'pagination' => array(
							'pageSize' => 5000,
						),
					)
				);
			} else {
				$dataProvider = new CArrayDataProvider(array());
			}
			$this->render(
				'listofdocuments',
				array(
					'dataProvider' => $dataProvider,
					'officer_title' => $_user->officers[0]->getFunctions(),
				)
			);
		} else {

			$this->render('listofdocs_form', array('model' => $ListofDocsForm));
		}

	}

}
