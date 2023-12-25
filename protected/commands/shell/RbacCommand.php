<?php
class RbacCommand extends CConsoleCommand
{
   
    private $_authManager;
 
    public function getHelp()
    {
		return <<<EOD
USAGE
	rbac

DESCRIPTION
	This command generates an initial RBAC authorization hierarchy.
EOD;
    }
    
    /**
     * Execute the action.
     * @param array command line parameters specific for this command
     */
    public function run($args)
    {
        //ensure that an authManager is defined as this is mandatory for creating an auth heirarchy
        if(($this->_authManager=Yii::app()->authManager)===null)
        {
            echo "Error: an authorization manager, named 'authManager' must be configured to use this command.\n";
            echo "If you already added 'authManager' component in application configuration,\n";
            echo "please quit and re-enter the yiic shell.\n";
            return;
        }  
        
		//provide the oportunity for the use to abort the request
        echo "This command will create three roles: Administrator, Creator, Follower and Reader and the following premissions:\n";
        echo "create, read, update and delete document\n";
        echo "create, read, update and delete marking\n";
        echo "create, read, update and delete disposal\n";
        echo "cearte, read, update and delete user\n";
        echo "Would you like to continue? [Yes|No] ";
       
		//check the input from the user and continue if they indicated yes to the above question
        if(!strncasecmp(trim(fgets(STDIN)),'y',1)) 
        {
			//first we need to remove all operations, roles, child relationship and assignments
             $this->_authManager->clearAll();
			//create the lowest level operations for users
             $this->_authManager->createOperation("createUser","create a new user"); 
             $this->_authManager->createOperation("readUser","read user profile information"); 
             $this->_authManager->createOperation("updateUser","update a users information"); 
             $this->_authManager->createOperation("deleteUser","remove a user from a document"); 
			//create the lowest level operations for documents
             $this->_authManager->createOperation("createDocument","create a new document"); 
             $this->_authManager->createOperation("readDocument","read document information"); 
             $this->_authManager->createOperation("updateDocument","update document information"); 
             $this->_authManager->createOperation("deleteDocument","delete a document"); 
			//create the lowest level operations for markings
             $this->_authManager->createOperation("createMarking","create a new marking"); 
             $this->_authManager->createOperation("readMarking","read marking information"); 
             $this->_authManager->createOperation("updateMarking","update marking information"); 
             $this->_authManager->createOperation("deleteMarking","delete a marking from a document");     
			//create the lowest level operations for disposals
             $this->_authManager->createOperation("createDisposal","create a new disposal"); 
             $this->_authManager->createOperation("readDisposal","read disposal information"); 
             $this->_authManager->createOperation("updateDisposal","update disposal information"); 
             $this->_authManager->createOperation("deleteDisposal","delete a disposal from a document");     
             //create the reader role and add the appropriate permissions as children to this role
             $role=$this->_authManager->createRole("reader"); 
             $role->addChild("readUser");
             $role->addChild("readDocument"); 
             $role->addChild("readMarking");
             $role->addChild("readDisposal"); 
			//create the follower role, and add the appropriate permissions, as well as the reader role itself, as children
             $role=$this->_authManager->createRole("follower"); 
             $role->addChild("reader"); 
             $role->addChild("createMarking"); 
             $role->addChild("createDisposal");
             $role->addChild("updateMarking");
             $role->addChild("updateDisposal"); 
             $role->addChild("deleteMarking");
             $role->addChild("deleteDisposal"); 
			//create the creator role, and add the appropriate permissions, as well as both the reader and follower roles as children
             $role=$this->_authManager->createRole("creator"); 
             $role->addChild("reader"); 
             $role->addChild("follower");    
             $role->addChild("createDocument"); 
             $role->addChild("updateDocument"); 
             $role->addChild("deleteDocument");  
			//create the administrator role, and add the appropriate permissions, as well as the reader, the follower and the creator roles as children
             $role=$this->_authManager->createRole("administrator"); 
             $role->addChild("reader"); 
             $role->addChild("follower");
             $role->addChild("creator");    
             $role->addChild("createUser"); 
             $role->addChild("updateUser"); 
             $role->addChild("deleteUser");  
        
             //provide a message indicating success
             echo "Authorization hierarchy successfully generated.";
        } 
    }
}
?>