<?php

// this file must be stored in:
// protected/components/WebUser.php

class WebUser extends CWebUser
{

	// Store model to not repeat query.
	private $_model;

	private $user;

	// Return first name.
	// access it by Yii::app()->user->fullname
	function getFullName()
	{
		$this->loadUser(Yii::app()->user->id);
		return $this->user->fullname;
	}

	// This is a function that checks the field 'role'
	// in the User model to be equal to 1, that means it's admin
	// access it by Yii::app()->user->isAdmin()
	function isAdmin()
	{
		if (!Yii::app()->user->isGuest) {
			$this->loadUser(Yii::app()->user->id);
			return $this->user->username == 'admin';
		}
		return false;
	}

	// Load user model.
	protected function loadUser($id = null)
	{
		if ($this->_model === null) {
			if ($id !== null)
				$this->_model = User::model()->findByPk($id);
			$this->user = $this->_model;
		}
		return $this->_model;
	}

	// Returns date format for this user
	public function getDateFormat($forJuiDatePicker = false)
	{
		if ($forJuiDatePicker) {
			if (Yii::app()->user->getState('date_format') == 'd.m.Y') {
				return 'dd.mm.yy';
			} elseif (Yii::app()->user->getState('date_format') == 'd/m/Y') {
				return 'dd/mm/yy';
			} elseif (Yii::app()->user->getState('date_format') == 'd-m-Y') {
				return 'dd-mm-yy';
			}
		} else {
			if (Yii::app()->user->getState('date_format') == 'd.m.Y') {
				return 'dd.MM.yyyy';
			} elseif (Yii::app()->user->getState('date_format') == 'd/m/Y') {
				return 'dd/MM/yyyy';
			} elseif (Yii::app()->user->getState('date_format') == 'd-m-Y') {
				return 'dd-MM-yyyy';
			}
		}
	}

	// Returns date separator for this user
	public function getDateSeparator()
	{
		return substr(Yii::app()->user->getState('date_format'), 1, 1);
	}

	/**
	 * @return boolean If user can create document
	 */
	public function canCreateDoc()
	{
		if (Yii::app()->user->isAdmin())
			return true;

		if (!Yii::app()->user->isGuest) {
			$this->loadUser(Yii::app()->user->id);
			return $this->user->can_create_doc;
		}
		return false;
	}

	/**
	 * @return boolean If user can create blank document
	 */
	public function canCreateBlankDoc()
	{
		if (Yii::app()->user->isAdmin())
			return true;

		if (!Yii::app()->user->isGuest) {
			$this->loadUser(Yii::app()->user->id);
			return $this->user->can_create_blank_doc;
		}
		return false;
	}

	/**
	 * @param document model for which the ownership is checked.
	 * @return boolean Is current user is owner of the document. 
	 * It checks duty id of the officers attached to current user
	 */
	public function isOwner($document)
	{
		if (Yii::app()->user->isAdmin())
			return true;

		if (!Yii::app()->user->isGuest) {
			$this->loadUser(Yii::app()->user->id);
			$officers = $this->user->officers;
			foreach ($officers as $officer) {
				$assignments = $officer->assignments;
				foreach ($assignments as $assignment) {
					if ($assignment->duty_id == $document->owner_id) {
						return true;
					}
				}
			}
		}
		return false;
	}

	/**
	 * @return boolean Can user mark the document
	 */
	public function canMark($document)
	{
		//if the user is admin return true
		if (Yii::app()->user->isAdmin())
			return true;
		//if user is the owner of the document return true
		if (Yii::app()->user->isOwner($document))
			return true;

		if (!Yii::app()->user->isGuest) {
			$this->loadUser(Yii::app()->user->id);
			$lastMarking = $document->LastMarking();
			if (empty($lastMarking))
				return false;
			$officers = $this->user->officers;
			foreach ($officers as $officer) {
				$assignments = $officer->assignments;
				foreach ($assignments as $assignment) {
					//foreach ($lastMarking as $marking) {
					if ($assignment->duty->id == $lastMarking->marked_to)
						return true;
					//}
				}
			}
		}
		return false;
	}

	/**
	 * @return boolean Can user mark the file
	 */
	public function canMarkFile($file)
	{
		//if the user is admin return true
		if (Yii::app()->user->isAdmin())
			return true;

		if (!Yii::app()->user->isGuest) {
			$this->loadUser(Yii::app()->user->id); // load user model
			if ($file->enroute) { // if file is already en-route
				$lastMarking = $file->LastMarking(); // get the last marking model of the file
				$officers = $this->user->officers; // select the officers attached to the user
				foreach ($officers as $officer) {
					$assignments = $officer->assignments; // get the assignments of each officer
					foreach ($assignments as $assignment) {
						//check each assignment section with the file's section
						//if the file does not pertain to the section attached with the
						//duty of the officer he cannot mark the file
						if ($assignment->duty->id == $lastMarking->marked_to)
							return true;
					}
				}
			} else {
				$officers = $this->user->officers;
				foreach ($officers as $officer) {
					$assignments = $officer->assignments;
					foreach ($assignments as $assignment) {
						if ($assignment->duty->section_id == $file->section_id)
							return true;
					}
				}
			}

		}
		return false;
	}

	/**
	 * @return boolean Can user enter the disposal for the document
	 */
	public function canDispose($document)
	{
		// if the user can mark the document, he can dispose off too
		return $this->canMark($document) && $document->isComplete();
	}

	/**
	 * @return boolean Can user view this document
	 */
	public function canView($document)
	{
		//if the user is admin return true
		if (Yii::app()->user->isAdmin())
			return true;
		//if user is the owner of the document return true
		if (Yii::app()->user->isOwner($document))
			return true;

		if (!Yii::app()->user->isGuest) {
			$this->loadUser(Yii::app()->user->id);
			$markings = $document->markings;
			if (empty($markings))
				return false;
			$officers = $this->user->officers;
			foreach ($officers as $officer) {
				$assignments = $officer->assignments;
				foreach ($assignments as $assignment) {
					foreach ($markings as $marking) {
						if ($assignment->duty->id == $marking->marked_to)
							return true;
					}
				}
			}
		}
		return false;
	}

	/**
	 * @return boolean Can user view this file
	 */
	public function canViewFile($file)
	{
		//if the user is admin return true
		if (Yii::app()->user->isAdmin())
			return true;

		if (!Yii::app()->user->isGuest) {
			$this->loadUser(Yii::app()->user->id);
			$markings = $file->markings;
			if (empty($markings))
				return false;
			$officers = $this->user->officers;
			foreach ($officers as $officer) {
				$assignments = $officer->assignments;
				foreach ($assignments as $assignment) {
					foreach ($markings as $marking) {
						if ($assignment->duty->id == $marking->marked_to)
							return true;
					}
				}
			}
		}
		return false;
	}

	public function isInitiatorOfFile($file)
	{
		if (!Yii::app()->user->isGuest) {
			$this->loadUser(Yii::app()->user->id);
			$initiator = $file->initiator();
			if ($initiator) {
				$officers = $this->user->officers;
				foreach ($officers as $officer) {
					$assignments = $officer->assignments;
					foreach ($assignments as $assignment) {
						if ($assignment->duty->id == $initiator->marked_by)
							return true;
					}
				}
			}
		}
		return false;
	}

	public function canListFile($file)
	{
		if (Yii::app()->user->isAdmin())
			return true;

		if (Yii::app()->user->canViewFile($file))
			return true;

		if (!Yii::app()->user->isGuest) {
			$this->loadUser(Yii::app()->user->id);
			$officers = $this->user->officers;
			foreach ($officers as $officer) {
				$assignments = $officer->assignments;
				foreach ($assignments as $assignment) {
					if ($assignment->duty->section_id == $file->section_id)
						return true;
				}
			}
		}
		return false;
	}
}
?>
