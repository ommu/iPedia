<?php
/**
 * Ipedia Companies (ipedia-companies)
 * @var $this app\components\View
 * @var $this ommu\ipedia\controllers\CompanyController
 * @var $model ommu\ipedia\models\IpediaCompanies
 * @var $searchModel ommu\ipedia\models\search\IpediaCompanies
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 12 February 2019, 11:16 WIB
 * @link https://github.com/ommu/mod-ipedia
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use ommu\ipedia\models\IpediaDirectories;
use ommu\member\models\Members;

$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Add Company'), 'url' => Url::to(['create']), 'icon' => 'plus-square'],
];
$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="ipedia-companies-manage">
<?php Pjax::begin(); ?>

<?php if($directory != null) {
$model = $directories;
echo DetailView::widget([
	'model' => $directories,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		[
			'attribute' => 'directory_name',
			'value' => $model->directory_name ? $model->directory_name : '-',
		],
		[
			'attribute' => 'creation_date',
			'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		],
		[
			'attribute' => 'creationDisplayname',
			'value' => isset($model->creation) ? $model->creation->displayname : '-',
		],
	],
]);
}?>

<?php if($member != null) {
$model = $members;
echo DetailView::widget([
	'model' => $members,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		[
			'attribute' => 'profileName',
			'value' => function ($model) {
				$profileName = isset($model->profile) ? $model->profile->title->message : '-';
				if($profileName != '-')
					return Html::a($profileName, ['profile/view', 'id'=>$model->profile_id], ['title'=>$profileName]);
				return $profileName;
			},
			'format' => 'html',
		],
		'username',
		'displayname',
		[
			'attribute' => 'photo_header',
			'value' => function ($model) {
				$uploadPath = Members::getUploadPath(false);
				return $model->photo_header ? Html::img(join('/', [Url::Base(), $uploadPath, $model->photo_header]), ['width' => '100%']).'<br/><br/>'.$model->photo_header : '-';
			},
			'format' => 'html',
		],
		[
			'attribute' => 'photo_profile',
			'value' => function ($model) {
				$uploadPath = Members::getUploadPath(false);
				return $model->photo_profile ? Html::img(join('/', [Url::Base(), $uploadPath, $model->photo_profile]), ['width' => '100%']).'<br/><br/>'.$model->photo_profile : '-';
			},
			'format' => 'html',
		],
		[
			'attribute' => 'short_biography',
			'value' => $model->short_biography ? $model->short_biography : '-',
		],
		[
			'attribute' => 'approved_date',
			'value' => Yii::$app->formatter->asDatetime($model->approved_date, 'medium'),
		],
		[
			'attribute' => 'approvedDisplayname',
			'value' => isset($model->approvedRltn) ? $model->approvedRltn->displayname : '-',
		],
		[
			'attribute' => 'creation_date',
			'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		],
		[
			'attribute' => 'creationDisplayname',
			'value' => isset($model->creation) ? $model->creation->displayname : '-',
		],
	],
]);
}?>

<?php //echo $this->render('_search', ['model'=>$searchModel]); ?>

<?php echo $this->render('_option_form', ['model'=>$searchModel, 'gridColumns'=>$this->activeDefaultColumns($columns), 'route'=>$this->context->route]); ?>

<?php 
$columnData = $columns;
array_push($columnData, [
	'class' => 'yii\grid\ActionColumn',
	'header' => Yii::t('app', 'Option'),
	'contentOptions' => [
		'class'=>'action-column',
	],
	'buttons' => [
		'view' => function ($url, $model, $key) {
			$url = Url::to(ArrayHelper::merge(['view', 'id'=>$model->primaryKey], Yii::$app->request->get()));
			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'Detail Company')]);
		},
		'update' => function ($url, $model, $key) {
			$url = Url::to(ArrayHelper::merge(['update', 'id'=>$model->primaryKey], Yii::$app->request->get()));
			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update Company')]);
		},
		'delete' => function ($url, $model, $key) {
			$url = Url::to(['delete', 'id'=>$model->primaryKey]);
			return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				'title' => Yii::t('app', 'Delete Company'),
				'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
				'data-method'  => 'post',
			]);
		},
	],
	'template' => '{view}{update}{delete}',
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'layout' => '<div class="row"><div class="col-sm-12">{items}</div></div><div class="row sum-page"><div class="col-sm-5">{summary}</div><div class="col-sm-7">{pager}</div></div>',
	'columns' => $columnData,
]); ?>

<?php Pjax::end(); ?>
</div>