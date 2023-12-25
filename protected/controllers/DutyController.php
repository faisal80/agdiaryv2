<?php

class DutyController extends Controller
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
				'actions'=>array('index','view', 'chart'),
				'users'=>array('admin'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','admin'),
				'users'=>array('admin'),
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
		$assignments = new CActiveDataProvider('Assignments', array(
            'criteria'=>array(
                'condition'=>'duty_id=:dutyID',
                'params'=>array(':dutyID'=>$id),
			),
			'pagination'=>array(
                'pageSize'=>50,
			),
		));
        
		$model = $this->loadModel($id);
		$count = $model->count();
		
		$this->render('view',array(
			'model'=>$model,
            'assignments'=>$assignments,
			'count'=>$count,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		// xdebug_break();
		
		$model=new Duty;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Duty']))
		{
			$model->attributes=$_POST['Duty'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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

		if(isset($_POST['Duty']))
		{
			$model->attributes=$_POST['Duty'];
			// if ($_POST['Duty']['null_value_for_duty_id'] == 1)
			// {
			// 	$model->setAttributes(array('duty_id'=> NULL));
			// }
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
//		$dataProvider=new CActiveDataProvider('Duties');
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
		$model=new Duty('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Duty']))
			$model->attributes=$_GET['Duty'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Duty the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Duty::model()->findByPk($id);
		if($model==null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Duty $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']=='duties-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * Display chart of all models.
	 */
	public function actionChart()
	{
		//Select model with id 1 to start from
		$dataProvider = Duty::model()->findAllByPk('1');
		$this->render('chart',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	/*
	 * prints nested unordered list <UL> of all duties
	 */
	public function dutiesforChart($duties)
	{		
		echo '<ul>';
		foreach ($duties as $duty)
		{
			echo '<li><a href="' . $this->createUrl('view', array('id'=>$duty->id)) . '">' . $duty->short_form;
			$child_duties = $duty->child_duties;
			if (!empty($child_duties))
				$this->dutiesforChart ($child_duties);
			echo '</a></li>';
		}
		echo '</ul>';
	}
	
	/**
	 * @return array List of Sections for dropdownlist
	 */
	public function getListofSections()
	{
		//get list of section_id which are already allotted
		$allotted = Duty::model()->findAll('section_id IS NOT NULL');
		$allotted = chtml::listData($allotted, 'section_id', 'section_id');
		//Lists all sections
		$criteria = new CDbCriteria();
		$criteria->addNotInCondition('id', $allotted);
		$sections = Section::model()->findAll($criteria);
		return CHtml::listData($sections, 'id', 'name');
		
	}
}

