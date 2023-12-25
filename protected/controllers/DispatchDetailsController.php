<?php

class DispatchDetailsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'admin', 'imagesAjax'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new DispatchDetails;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['disp_doc_id'])) $disp_doc_id = $_POST['disp_doc_id'];
		if (isset($_GET['disp_doc_id'])) $disp_doc_id = $_GET['disp_doc_id'];

		if (!empty($disp_doc_id)) {
			$dispatchDoc = DispatchDocs::model()->findByPk($disp_doc_id);
			$model->disp_doc_id = $dispatchDoc->id;
			$model->dispatch_date = date('Y-m-d');
		}

		if(isset($_POST['DispatchDetails']))
		{
			$model->attributes=$_POST['DispatchDetails'];

			// Gets the files uploaded with the dispatch details
			$uploadedFiles = CUploadedFile::getInstancesByName('image');

			if($model->save()){
				// Checks if there were files uploaded
				if (isset($uploadedFiles) && count($uploadedFiles) > 0) {
					// save each image and its path in DispDetImages model
					foreach ($uploadedFiles as $upfile => $image) {
						$img = Yii::app()->image->load($image->getTempName());
						if ($img->width > 900) {
							$img->resize(900, 500, Image::WIDTH);
						}
						//$img->gamma_correction(1.0, 0.50); //My addition to the extension
						//$img->sharpen(50);

						$folder = Yii::getPathOfAlias('webroot.dispatch_det_images') . DIRECTORY_SEPARATOR; // folder for uploaded files

						if (!is_dir($folder . date('Ym')))
							mkdir($folder . date('Ym')); //sudo chown www-data -R disposal_images

						$filename = date('Ym') . '/' . $model->id . '-' . 'doc' . '[' . $upfile . '].' . $image->getExtensionName();
						if ($img->save($folder . $filename)) {
							$image_model = new DispDetImages;
							$image_model->setIsNewRecord(true);
							$image_model->disp_det_id = $model->id;
							$image_model->image_path = $filename;
							$image_model->save();
						}
					}
				}
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DispatchDetails']))
		{
			$model->attributes=$_POST['DispatchDetails'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
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
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('DispatchDetails');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new DispatchDetails('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DispatchDetails']))
			$model->attributes=$_GET['DispatchDetails'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return DispatchDetails the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=DispatchDetails::model()->findByPk($id);
		if($model==null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param DispatchDetails $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']=='dispatch-details-form')
		{
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
		$this->renderPartial('_dispdetimages', $model);
	}
}
