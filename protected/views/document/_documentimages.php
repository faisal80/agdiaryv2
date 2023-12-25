<?php
/* @var $data Document */

//$model = $this->loadModel($id);
if ($number_of_images = count($data->images))
{
	echo '<div class="span-10">Document ID : ' . $data->id . '</div><div class="span-4 last">' . $number_of_images . ' page(s)</div>';
	for($i=0; $i<$number_of_images; $i++)
	{
		echo CHtml::image(Yii::app()->baseUrl.'/document_images/'.$data->images[$i]->image_path) . '<br>';  // Image shown here
	}
} else {
	echo "No attachments found!!!";
}

?>	