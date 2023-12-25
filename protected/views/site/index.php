<?php
/* @var $this SiteController */
/* @var $user User */

$this->pageTitle = Yii::app()->name;
// $this->breadcrumbs = array('Dashboard');
?>
<center>
	<h1><b><u>Dashboard</u></b></h1>
</center>
<h1>Documents</h1>

<div class="box container">
	<?php if ($user->user_type == 'dispatcher'): ?>
		<div class="portlet span-4"
			onclick="window.location.assign('<?php echo Yii::app()->createUrl('site/listTBRDispatch') ?>')"
			style="cursor:pointer;">
			<div class="portlet-decoration">
				<div class="portlet-title">
					<h6>For Receiving</h6>
				</div>
			</div>
			<div class="portlet-content large center fs-2 text-decoration-none">
				<strong>
					<div id="tbrdispatch"><img src="images/loading.gif"></div>
				</strong>
			</div>
		</div>
	<?php else: ?>
		<div class="portlet span-4" onclick="window.location.assign('<?php echo Yii::app()->createUrl('site/listTBR') ?>')"
			style="cursor:pointer;">
			<div class="portlet-decoration">
				<div class="portlet-title">
					<h6>For Receiving</h6>
				</div>
			</div>
			<div class="portlet-content large center fs-2 text-decoration-none">
				<strong>
					<div id="tbr"><img src="images/loading.gif"></div>
				</strong>
			</div>
		</div>
	<?php endif; ?>
	<?php if ($user->user_type == 'dispatcher'): ?>
		<div class="portlet span-4"
			onclick="window.location.assign('<?php echo Yii::app()->createUrl('site/listTBDispatch') ?>')"
			style="cursor:pointer;">
			<div class="portlet-decoration">
				<div class="portlet-title">
					<h6>For Dispatch</h6>
				</div>
			</div>
			<div class="portlet-content large center fs-2 text-decoration-none">
				<strong>
					<div id="tbdispatch"><img src="images/loading.gif"></div>
				</strong>
			</div>
		</div>
	<?php else: ?>
		<div class="portlet span-4" onclick="window.location.assign('<?php echo Yii::app()->createUrl('site/listTBMD') ?>')"
			style="cursor:pointer;">
			<div class="portlet-decoration">
				<div class="portlet-title">
					<h6>For Marking/Disposal</h6>
				</div>
			</div>
			<div class="portlet-content large center fs-2 text-decoration-none">
				<strong>
					<div id="tbmd"><img src="images/loading.gif"></div>
				</strong>
			</div>
		</div>
		<div class="portlet span-4"
			onclick="window.location.assign('<?php echo Yii::app()->createUrl('site/listpendingDispatch') ?>')"
			style="cursor:pointer;">
			<div class="portlet-decoration">
				<div class="portlet-title">
					<h6>Pending Dispatch</h6>
				</div>
			</div>
			<div class="portlet-content large center fs-2 text-decoration-none">
				<strong>
					<div id="pendingdispatch"><img src="images/loading.gif"></div>
				</strong>
			</div>
		</div>
		<div class="portlet span-4 red-alert"
			onclick="window.location.assign('<?php echo Yii::app()->createUrl('site/listTimeLimitCases') ?>')"
			style="cursor:pointer;">
			<div class="portlet-decoration">
				<div class="portlet-title">
					<h6>Time Limit Cases</h6>
				</div>
			</div>
			<div class="portlet-content large center fs-2 text-decoration-none">
				<strong>
					<div id="timeLimitCases"><img src="images/loading.gif"></div>
				</strong>
			</div>
		</div>
	<?php endif; ?>
</div>

<h1>Files</h1>
<div class="box container">
	<div class="portlet span-4" onclick="window.location.assign('<?php echo Yii::app()->createUrl('site/listFilesTBR') ?>')"
		style="cursor:pointer;">
		<div class="portlet-decoration">
			<div class="portlet-title">
				<h6>For Receiving</h6>
			</div>
		</div>
		<div class="portlet-content large center fs-2 text-decoration-none">
			<strong>
				<div id="filestbr"><img src="images/loading.gif"></div>
			</strong>
		</div>
	</div>

	<div class="portlet span-4" onclick="window.location.assign('<?php echo Yii::app()->createUrl('site/listFilesInHand') ?>')"
		style="cursor:pointer;">
		<div class="portlet-decoration">
			<div class="portlet-title">
				<h6>Files In Hand</h6>
			</div>
		</div>
		<div class="portlet-content large center fs-2 text-decoration-none">
			<strong>
				<div id="filesinhand"><img src="images/loading.gif"></div>
			</strong>
		</div>
	</div>
</div>

<?php

Yii::app()->clientScript->registerScript(
	'tbr',
	'
			$.ajax({
				url: "' . Yii::app()->createUrl('site/toBeReceived&countOnly=1') . '",
				success: function(data, status, xhr) {
					$("#tbr").html( parseInt(data) );
				}
			});
		',
	CClientScript::POS_END
);

Yii::app()->clientScript->registerScript(
	'tbmd',
	'
			$.ajax({
				url: "' . Yii::app()->createUrl('site/toBeMarkedDisposed&countOnly=1') . '",
				success: function(data, status, xhr) {
					$("#tbmd").html( parseInt(data) );
				}
			});
		',
	CClientScript::POS_END
);

Yii::app()->clientScript->registerScript(
	'tbrdispatch',
	'
			$.ajax({
				url: "' . Yii::app()->createUrl('site/dispatchDocsToBeReceived&countOnly=1') . '",
				success: function(data, status, xhr) {
					$("#tbrdispatch").html( parseInt(data) );
				}
			});
		',
	CClientScript::POS_END
);

Yii::app()->clientScript->registerScript(
	'tbdispatch',
	'
			$.ajax({
				url: "' . Yii::app()->createUrl('site/dispatchDocsToBeDispatched&countOnly=1') . '",
				success: function(data, status, xhr) {
					$("#tbdispatch").html( parseInt(data) );
				}
			});
		',
	CClientScript::POS_END
);

Yii::app()->clientScript->registerScript(
	'pendingdispatch',
	'
			$.ajax({
				url: "' . Yii::app()->createUrl('site/pendingDispatchDocs&countOnly=1') . '",
				success: function(data, status, xhr) {
					$("#pendingdispatch").html( parseInt(data) );
				}
			});
		',
	CClientScript::POS_END
);

Yii::app()->clientScript->registerScript(
	'timeLimitCases',
	'
			$.ajax({
				url: "' . Yii::app()->createUrl('site/timeLimitCases&countOnly=1') . '",
				success: function(data, status, xhr) {
					$("#timeLimitCases").html( parseInt(data) );
				}
			});
		',
	CClientScript::POS_END
);

Yii::app()->clientScript->registerScript(
	'filestbr',
	'
			$.ajax({
				url: "' . Yii::app()->createUrl('site/filesToBeReceived&countOnly=1') . '",
				success: function(data, status, xhr) {
					$("#filestbr").html( parseInt(data) );
				}
			});
		',
	CClientScript::POS_END
);

Yii::app()->clientScript->registerScript(
	'filesinhand',
	'
			$.ajax({
				url: "' . Yii::app()->createUrl('site/filesInHand&countOnly=1') . '",
				success: function(data, status, xhr) {
					$("#filesinhand").html( parseInt(data) );
				}
			});
		',
	CClientScript::POS_END
);


?>