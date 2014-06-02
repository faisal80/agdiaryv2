<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */

$this->breadcrumbs=array(
	'User'=>array('index'),
	'Change Password',
);

?>

<h1>Change Password</h1>

<?php if(Yii::app()->user->hasFlash('PasswdChanged')):?>
    <div style="padding: 0.8em; margin-bottom: 1em; border: 2px solid rgb(198, 216, 128); background: none repeat scroll 0% 0% rgb(230, 239, 194); color: rgb(38, 68, 9);">
        <?php echo Yii::app()->user->getFlash('PasswdChanged'); ?>
    </div>
<?php endif; ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo '<label for="User_passwd" class="required">Enter your current password<span class="required">*</span></label>'; ?>
		<?php echo $form->passwordField($model,'passwd',array('size'=>60,'maxlength'=>255, 'value'=>'')); ?>
		<?php echo $form->error($model,'passwd'); ?>
	</div>

	<div class="row">
		<?php echo '<label for="User_passwd" class="required">Enter new password<span class="required">*</span></label>'; ?>
		<?php echo $form->passwordField($model,'newpasswd',array('size'=>60,'maxlength'=>255, 'value'=>'')); ?>
		<?php echo $form->error($model,'newpasswd'); ?>
	</div>

	<div class="row">
		<?php echo '<label for="User_passwd" class="required">Re-enter new password<span class="required">*</span></label>'; ?>
		<?php echo $form->passwordField($model,'newpasswd_repeat',array('size'=>60,'maxlength'=>255, 'value'=>'')); ?>
		<?php echo $form->error($model,'newpasswd_repeat'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Change'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->