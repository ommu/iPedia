<?php
/**
 * IpediaCompanyIndustry
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 12 February 2019, 11:34 WIB
 * @link https://github.com/ommu/mod-ipedia
 *
 * This is the model class for table "ommu_ipedia_company_industry".
 *
 * The followings are the available columns in table "ommu_ipedia_company_industry":
 * @property integer $id
 * @property integer $publish
 * @property integer $company_id
 * @property integer $industry_id
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property IpediaCompanies $company
 * @property IpediaIndustries $industry
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\ipedia\models;

use Yii;
use yii\helpers\Url;
use ommu\users\models\Users;

class IpediaCompanyIndustry extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = [];

	public $companyName;
	public $industryTagId;
	public $creationDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_ipedia_company_industry';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['company_id', 'industry_id'], 'required'],
			[['publish', 'company_id', 'industry_id', 'creation_id', 'modified_id'], 'integer'],
			[['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => IpediaCompanies::className(), 'targetAttribute' => ['company_id' => 'company_id']],
			[['industry_id'], 'exist', 'skipOnError' => true, 'targetClass' => IpediaIndustries::className(), 'targetAttribute' => ['industry_id' => 'industry_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'publish' => Yii::t('app', 'Publish'),
			'company_id' => Yii::t('app', 'Company'),
			'industry_id' => Yii::t('app', 'Industry'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'companyName' => Yii::t('app', 'Company'),
			'industryTagId' => Yii::t('app', 'Industry'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCompany()
	{
		return $this->hasOne(IpediaCompanies::className(), ['company_id' => 'company_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getIndustry()
	{
		return $this->hasOne(IpediaIndustries::className(), ['industry_id' => 'industry_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\ipedia\models\query\IpediaCompanyIndustry the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\ipedia\models\query\IpediaCompanyIndustry(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class'  => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('company')) {
			$this->templateColumns['companyName'] = [
				'attribute' => 'companyName',
				'value' => function($model, $key, $index, $column) {
					return isset($model->company) ? $model->company->company_name : '-';
				},
			];
		}
		if(!Yii::$app->request->get('industry')) {
			$this->templateColumns['industryTagId'] = [
				'attribute' => 'industryTagId',
				'value' => function($model, $key, $index, $column) {
					return isset($model->industry) ? $model->industry->tag->body : '-';
				},
			];
		}
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creationDisplayname'] = [
				'attribute' => 'creationDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation) ? $model->creation->displayname : '-';
					// return $model->creationDisplayname;
				},
			];
		}
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modifiedDisplayname'] = [
				'attribute' => 'modifiedDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
					// return $model->modifiedDisplayname;
				},
			];
		}
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'filter' => $this->filterYesNo(),
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id'=>$model->primaryKey]);
					return $this->quickAction($url, $model->publish, '0=unpublish, 1=publish, 2=trash, 3=admin_checked');
				},
				'contentOptions' => ['class'=>'center'],
				'format' => 'raw',
			];
		}
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->companyName = isset($this->company) ? $this->company->company_name : '-';
		// $this->industryTagId = isset($this->industry) ? $this->industry->tag->body : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				if($this->creation_id == null)
					$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			} else {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
		}
		return true;
	}
}
