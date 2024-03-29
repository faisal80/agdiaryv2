<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
    <div class="container">
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
     
          <!-- Be sure to leave the brand out there if you want it shown -->
          <a class="brand" href="#"><?php echo CHtml::encode(Yii::app()->name); ?></a>
          
          <div class="nav-collapse">
			<?php $this->widget('zii.widgets.CMenu',array(
                    'htmlOptions'=>array('class'=>'pull-right nav'),
                    'submenuHtmlOptions'=>array('class'=>'dropdown-menu'),
					'itemCssClass'=>'item-test',
                    'encodeLabel'=>false,
                    'items'=>array(
                        array('label'=>'Dashboard', 'url'=>array('/site/index')),
                        array('label'=>'Documents <span class="caret"></span>', 'url'=>'#',
                        	'itemOptions'=>array(
	                        	'class'=>'dropdown',
    	                    	'tabindex'=>"-1"
                        	),
                        	'linkOptions'=>array(
                        		'class'=>'dropdown-toggle',
                        		'data-toggle'=>"dropdown"
                        	), 
	                        'items'=>array(
	                            array('label'=>'Create', 'url'=>array('/documents/create')),
								array('label'=>'View/List', 'url'=>array('/documents/admin')),
	                        )), 
                        array('label'=>'Users <span class="caret"></span>', 'url'=>'#', 'visible'=>!Yii::app()->user->isGuest,
                        	'itemOptions'=>array(
	                        	'class'=>'dropdown',
    	                    	'tabindex'=>"-1"
                        	),
                        	'linkOptions'=>array(
                        		'class'=>'dropdown-toggle',
                        		'data-toggle'=>"dropdown"
                        	), 
	                        'items'=>array(
	                            array('label'=>'Create', 'url'=>array('/users/create')),
								array('label'=>'View/List', 'url'=>array('/users/admin')),
	                        )),
                        /*array('label'=>'Gii generated', 'url'=>array('customer/index')),*/
                        array('label'=>'My Account <span class="caret"></span>', 'url'=>'#', 'visible'=>!Yii::app()->user->isGuest, 'itemOptions'=>array('class'=>'dropdown','tabindex'=>"-1"),'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
                        'items'=>array(
                            array('label'=>'My Messages <span class="badge badge-warning pull-right">26</span>', 'url'=>'#'),
							array('label'=>'My Tasks <span class="badge badge-important pull-right">112</span>', 'url'=>'#'),
							array('label'=>'My Invoices <span class="badge badge-info pull-right">12</span>', 'url'=>'#'),
							array('label'=>'Separated link', 'url'=>'#'),
							array('label'=>'One more separated link', 'url'=>'#'),
                        )),
                        array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                        array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
                    ),
                )); ?>
    	</div>
    </div>
	</div>
</div>
