<?php
/* @var $this PeriodFormController */
/* @var $model PeriodForm */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'period-form-period-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

        <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'datefrom'); ?>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'model' => $model,
            'attribute' => 'datefrom',
            'value' => $model->datefrom,
            'options' => array(
                'showAnim' => 'fold',
                'dateFormat' => 'dd-mm-yy', //save to db
                'changeMonth' => true,
                'changeYear' => true,
                'showOn' => 'both',
                'buttonText' => '...',
            ),
            'htmlOptions' => array(
                'style' => 'height:20px;',
            ),
        ));
        ?>
<?php echo $form->error($model, 'datefrom'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'dateto'); ?>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'model' => $model,
            'attribute' => 'dateto',
            'value' => $model->dateto,
            'options' => array(
                'showAnim' => 'fold',
                'dateFormat' => 'dd-mm-yy', //save to db
                'changeMonth' => true,
                'changeYear' => true,
                'showOn' => 'both',
                'buttonText' => '...',
            ),
            'htmlOptions' => array(
                'style' => 'height:20px;',
            ),
        ));
        ?>
<?php echo $form->error($model, 'dateto'); ?>
    </div>


    <div class="row buttons">
    <?php echo CHtml::submitButton('Submit'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->