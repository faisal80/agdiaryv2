<?php
 
// this file must be stored in:
// protected/components/WebUser.php
 
class WebUser extends CWebUser {
 
  	// Store model to not repeat query.
  	private $_model;
 
  	// Return first name.
  	// access it by Yii::app()->user->fullname
  	function getFullName(){
    	$user = $this->loadUser(Yii::app()->user->id);
    	return $user->fullname;
  	}
 
  	// This is a function that checks the field 'role'
  	// in the User model to be equal to 1, that means it's admin
  	// access it by Yii::app()->user->isAdmin()
  	function isAdmin(){
			if (!Yii::app()->user->isGuest)
    	{
				$user = $this->loadUser(Yii::app()->user->id);
				return $user->username == 'admin';
			}
			return false;
  	}
 
  	// Load user model.
  	protected function loadUser($id=null)
    {
    	if($this->_model===null)
        {
           	if($id!==null)
              	$this->_model=User::model()->findByPk($id);
        }
        return $this->_model;
    }
    
    // Returns date format for this user
	public function getDateFormat($forJuiDatePicker=false)
	{
		if ($forJuiDatePicker)
		{
			if (Yii::app()->user->getState('date_format') == 'd.m.Y') {
				return 'dd.mm.yy';
			} elseif (Yii::app()->user->getState('date_format') == 'd/m/Y') {
				return 'dd/mm/yy';
			} elseif (Yii::app()->user->getState('date_format') == 'd-m-Y'){
				return 'dd-mm-yy';
			}
		} else {
			if (Yii::app()->user->getState('date_format') == 'd.m.Y') {
				return 'dd.MM.yyyy';
			} elseif (Yii::app()->user->getState('date_format') == 'd/m/Y') {
				return 'dd/MM/yyyy';
			} elseif (Yii::app()->user->getState('date_format') == 'd-m-Y'){
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
		if (Yii::app()->user->isAdmin()) return true;
		
		if (!Yii::app()->user->isGuest)
		{
			$user = $this->loadUser(Yii::app()->user->id);
			return ($user->can_create_doc === "yes") ? true : false;
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
		if (Yii::app()->user->isAdmin()) return true;
		
		if (!Yii::app()->user->isGuest)
		{
			$user = $this->loadUser(Yii::app()->user->id);
			$officers = $user->officers;
			foreach ($officers as $officer)
			{
				$assignments = $officer->assignments;
				foreach ($assignments as $assignment)
				{
					if ($assignment->duty_id === $document->owner_id)
					{
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
		if (Yii::app()->user->isAdmin()) return true;
		//if user is the owner of the document return true
		if (Yii::app()->user->isOwner($document)) return true;
		
		if (!Yii::app()->user->isGuest)
		{
			$user = $this->loadUser(Yii::app()->user->id);			
			$makings = $document->markings;
			$officers = $user->officers;
			foreach ($officers as $officer)
			{
				$assignments = $officer->assignments;
				foreach ($assignments as $assignment)
				{
					foreach ($makings as $marking)
					{
						if ($assignment->duty->id === $marking->marked_to)
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
		return $this->canMark($document);
	}
	
	/**
	 * @return boolean Can user view this document
	 */
	public function canView($document)
	{
		//xdebug_break();
		if ($document->isConfidential())
		{
			return $this->canMark($document);
		}

		return !$document->isConfidential();
	}
}
?>