<?php
/**
 * Ipedia Faculties (ipedia-faculties)
 * @var $this FacultyController
 * @var $model IpediaFaculties
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 19 April 2017, 09:51 WIB
 * @link https://github.com/ommu/mod-ipedia
 * @contact (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Ipedia Faculties'=>array('manage'),
		'Create',
	);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>