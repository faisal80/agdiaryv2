<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/my.css" />

	<link rel="shortcut icon" type="image/vnd.microsoft.icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" />
	
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo">
			<div class="span-2"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo.png" height="55px"/></div>
			<div style="padding-top: 12px;padding-bottom: 12px; font-size: 200%">&nbsp;<?php echo CHtml::encode(Yii::app()->name); ?></div>
		</div>
	</div><!-- header -->

	<div id="mbmenu">
		<?php $this->widget('ext.mbmenu.MbMenu',array(
			'items'=>array(
				array('label'=>'Dashboard', 'url'=>array('/')),
				array('label'=>'Documents', 'url'=>array('/document'), 'items'=>array(
						array('label'=>'Create', 'url'=>array('/document/create'), 'visible'=>Yii::app()->user->canCreateDoc()),
						array('label'=>'Receiving List', 'url'=>array('/document/listofdocs')),
					)
				),
				array('label'=>'Dispatch', 'url'=>array('/dispatchDocs')),
				array('label'=>'Files', 'url'=>array('/file/admin')),
				array('label'=>'Reports', 'url'=>'#', 'items' => array(
					array('label' => 'Diary Report', 'url'=>array('/reports/diaryReport')),
					array('label' => 'Dispatch Report', 'url'=>array('/reports/dispatchReport')),
					array('label' => 'Un-attended Documents', 'url'=>array('/reports/unAttendedDocs')),
				)),
				array('label'=>'Org. Chart', 'url'=>array('/assignments/chart')),
				array('label'=>'Admin', 'url'=> array('/admin'), 'visible'=>Yii::app()->user->isAdmin(), 'items'=>array(
						array('label'=>'Users', 'url'=>array('/user'), 'items'=>array(
								array('label'=>'Create', 'url'=>array('/user/create')),
								array('label'=>'Attach Officers', 'url'=>array('/userofficerassignment/create')),
						)),
						array('label'=>'Officers', 'url'=>array('/officer'), 'items'=>array(
								array('label'=>'Create', 'url'=>array('/officer/create')),
								array('label'=>'Organization Chart', 'url'=>array('/assignments/chart')),
								array('label'=>'Assign Duties', 'url'=>array('/assignments/create')),
						)),
						array('label'=>'Designations', 'url'=>array('/designation'), 'items'=>array(
								array('label'=>'Create', 'url'=>array('/designation/create')),
						)),
						array('label'=>'Duties', 'url'=>array('/duty'), 'items'=>array(
								array('label'=>'Create', 'url'=>array('/duty/create')),
								array('label'=>'Chart', 'url'=>array('/duty/chart')),
						)),
						array('label'=>'Sections', 'url'=>array('/section'), 'items'=>array(
								array('label'=>'Create', 'url'=>array('/section/create')),
						)),
						array('label'=>'Files', 'url'=>array('/file'), 'items'=>array(
								array('label'=>'Create', 'url'=>array('/file/create')),
						)),
				)),
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest, 'items'=>array(
						array('label'=>'Change Password', 'url'=>array('/user/changepwd')),
				)),
			),
		)); 
		?>
	</div><!--mbmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>
<?php echo '<div id="date" style="text-align:right">' . date('F d, Y', time()) . '</div>';?>
	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by Accountant General Khyber Pakhtunkhwa.<br/>
		All Rights Reserved.<br/>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
