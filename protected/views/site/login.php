<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>
<center>
<h1>Diary System of Accountant General</h1>
<div style="background-image: url('../images/pak_bg.png'); height:250px; background-repeat:no-repeat; background-position:center;">
	<h2>Khyber Pakhtunkhwa</h2>
</div>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
	<h3>Login</h3>
	<table align="center" style="width:200px;"><tr><td>
		<div class="row">
			<?php echo $form->labelEx($model,'username'); ?>
			<?php echo $form->textField($model,'username'); ?>
		</div>
	</td><td>
		<div class="row">
			<?php echo $form->labelEx($model,'password'); ?>
			<?php echo $form->passwordField($model,'password'); ?>
			
		</div>
	</td></tr>
	<tr><td>
			<?php echo $form->error($model,'username'); ?></td>
	<td>
			<?php echo $form->error($model,'password'); ?>
	</td></tr>
	<tr><td>
	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>
	</td><td>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Login'); ?>
	</div>
	</td></tr></table>
<?php $this->endWidget(); ?>
</div><!-- form -->
</center>
