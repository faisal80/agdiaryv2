<?php

class AssignmentsController extends Controller
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
				'actions' => array('index', 'view', 'chart'),
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
		$model = new Assignments;

		if (isset($_GET['oid']))
			$model->officer_id = $_GET['oid'];


		if (isset($_POST['oid']))
			$model->officer_id = $_POST['oid'];

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Assignments'])) {
			$model->attributes = $_POST['Assignments'];

			$rec = Assignments::model()->find('designation_id=:desigID AND duty_id=:dutyID AND state=1', array(
				':desigID' => $model->designation_id,
				':dutyID' => $model->duty_id,
			)
			);

			if ($rec) {
				throw new CHttpException(Yii::t('yii', 'This duty has aready been assigned to ' . $rec->officer->officer_name . ' (' . $rec->designation->title . ')'));
				Yii::app()->end();
			}


			if ($model->save())
				$this->redirect(array('/officer/view', 'id' => $model->officer_id));
		}

		$this->render('create', array(
			'model' => $model,
			'excludeAssigned' => true,
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Assignments'])) {
			$model->attributes = $_POST['Assignments'];
			if ($model->save())
				$this->redirect(array('view', 'id' => $model->id));
		}

		$this->render('update', array(
			'model' => $model,
			'excludeAssigned' => false,
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
		$dataProvider = new CActiveDataProvider('Assignments');
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
		$model = new Assignments('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['Assignments']))
			$model->attributes = $_GET['Assignments'];

		$this->render('admin', array(
			'model' => $model,
		)
		);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Assignments the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Assignments::model()->findByPk($id);
		if ($model == null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Assignments $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] == 'officer-desig-duty-assignment-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/**
	 * Renders organization chart
	 */
	public function actionChart()
	{
		// $this->layout = '\\layout\chart';
		$result = array();
		$prev_oid = array(); //ids of the officers which will be ignored during loop
		//Find all active officers
		$officers = Assignments::model()->findAll('state=1');
		foreach ($officers as $officer) {
			if (!in_array($officer->officer_id, $prev_oid)) {
				$_officer = Officer::model()->findByPk($officer->officer_id);
				$result[] = array(
					'id' => $_officer->id,
					'name' => $_officer->officer_name,
					'designation' => $officer->designation->short_form,
					'level' => $officer->designation->level,
					'duties' => $this->add_duties($_officer->id),
					'parent' => $this->parentOfficer($_officer->id),
				);
				$prev_oid[] = $officer->officer_id;
			}
		}

		usort($result, $this->build_sorter('level'));
		//		var_dump($result);
		$this->arrangeArray($result, $data, true);
		$this->render('chart', array(
			'dataProvider' => $data,
		)
		);
	}

	/**
	 * This function renders organization chart <ul>
	 */
	public function orgChart($data, $isFirst = true)
	{
		if ($isFirst) {
			echo '<li><a href="' . Yii::app()->createUrl('/officer/view', array('id' => $data['id'])) . '">' .
				$data['name'] . '<br>' .
				$data['designation'] . 
				(empty($data['duties']) ? '' : ' (' . $data['duties'] . ')' ) .
				'</a>';
			if (isset($data['child']) && is_array($data['child'])) {
				echo '<ul>';
				$this->orgChart($data['child'], false);
				echo '</ul>';
			}
			echo '</li>';
		} else {
			foreach ($data as $officer) {
				echo '<li><a href="' . Yii::app()->createUrl('/officer/view', array('id' => $officer['id'])) . '">' .
					$officer['name'] . '<br>' .
					$officer['designation'] . 
					(empty($officer['duties']) ? '' : ' (' . $officer['duties'] . ')' ) .
					'</a>';
				if (isset($officer['child']) && is_array($officer['child'])) {
					echo '<ul>';
					$this->orgChart($officer['child'], false);
					echo '</ul>';
				}
				echo '</li>';
			}
		}
	}

	/*
	 * Sorts array as per key, keeps keys with values. Its multi-diamensional
	 */

	public function build_sorter($key)
	{
		return function ($a, $b) use ($key) {
			return strnatcmp($a[$key], $b[$key]);
		};
	}

	/**
	 * 
	 * @param string $officer_id
	 * @return string the duties seperated by comma
	 */
	public function add_duties($officer_id)
	{
		$officer = Officer::model()->findByPk($officer_id);
		$duties = $officer->assignments;
		
		if (empty($duties)) return '';

		$result = null;
		foreach ($duties as $duty) {
			$result .= ', ' . $duty->duty->short_form;
		}

		return trim($result, ', ');
	}

	/**
	 * @param string $officer_id for which the parent officer id should be located
	 * @return string Officer id of the parent officer
	 */
	public function parentOfficer($officer_id)
	{
		//		xdebug_break();
		$officer = Officer::model()->findByPk($officer_id);
		$assignments = $officer->assignments;
		if (!empty($assignments)) {
			foreach ($assignments as $assignment) {
				$parent_duty = $assignment->duty->parent_duty;
			}
		}
		if (isset($parent_duty)){
			if (is_object($parent_duty->assigned_to)) {
				return $parent_duty->assigned_to->officer_id;
			}
		}
		return null;
	}

	/**
	 * This function arranges the array in hierarichal manner
	 * @param array $array by reference. This array contains a list of all officers.
	 * @param array $output by ref. This is the output array.
	 * @param boolean $shift_first Determines whether it is first element in array or not for self calling.
	 */
	public function arrangeArray(&$array, &$output, $shift_first = false)
	{
		if ($shift_first) {
			$output = array_shift($array);
			$output['child'] = array();
		}
		//$ckey = key($output);
		foreach ($array as $key => $value) {
			if ($value['parent'] == $output['id']) {
				$output['child'][] = $value;
				unset($array[$key]);
				if (isset($output['child']) && is_array($output['child'])) {
					foreach ($output['child'] as &$child) {
						$this->arrangeArray($array, $child);
					}
				}
			}
		}
	}

	/**
	 * @return array of Duties alongwith info field concatenated, indexed by IDs, which are not already assigned.
	 */
	public static function getListofDuties($all = false)
	{
		$result = array();
		//First of all find assigned duties
		// if ($all) {
		// 	$assigned = $assigned = Assignments::model()->findAll();
		// } else {
			$assigned = Assignments::model()->findAll('state=1');
		// }
		$assigned = CHtml::listData($assigned, 'duty_id', 'duty_id');
		$criteria = new CDbCriteria();
		$criteria->addNotInCondition('id', $assigned);

		//Then lists all Un-assigned Duties 
		$duties = Duty::model()->findAll($criteria);
		foreach ($duties as $duty) {
			$result = $result + array($duty->id => $duty->duty . ' (' . $duty->info . ')');
		}
		return $result;
	}

}