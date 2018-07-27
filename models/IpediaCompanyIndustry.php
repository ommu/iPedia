<?php
/**
 * IpediaCompanyIndustry
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 2 March 2017, 14:32 WIB
 * @link https://github.com/ommu/mod-ipedia
 *
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 *
 * --------------------------------------------------------------------------------------
 *
 * This is the model class for table "ommu_ipedia_company_industry".
 *
 * The followings are the available columns in table 'ommu_ipedia_company_industry':
 * @property string $id
 * @property integer $publish
 * @property string $company_id
 * @property string $industry_id
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 *
 * The followings are the available model relations:
 * @property IpediaCompanies $company
 * @property IpediaIndustries $industry
 */
class IpediaCompanyIndustry extends CActiveRecord
{
	use GridViewTrait;

	public $defaultColumns = array();
	public $company_name_i;
	public $industry_name_i;
	
	// Variable Search
	public $creation_search;
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return IpediaCompanyIndustry the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ommu_ipedia_company_industry';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_id, industry_id', 'required'),
			array('publish', 'numerical', 'integerOnly'=>true),
			array('company_id, industry_id, creation_id, modified_id', 'length', 'max'=>11),
			array('
				company_name_i, industry_name_i', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, publish, company_id, industry_id, creation_date, creation_id, modified_date, modified_id,
				company_name_i, industry_name_i, creation_search, modified_search', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'company' => array(self::BELONGS_TO, 'IpediaCompanies', 'company_id'),
			'industry' => array(self::BELONGS_TO, 'IpediaIndustries', 'industry_id'),
			'creation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('attribute', 'ID'),
			'publish' => Yii::t('attribute', 'Publish'),
			'company_id' => Yii::t('attribute', 'Company'),
			'industry_id' => Yii::t('attribute', 'Industry'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'company_name_i' => Yii::t('attribute', 'Company'),
			'industry_name_i' => Yii::t('attribute', 'Industry'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'modified_search' => Yii::t('attribute', 'Modified'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		
		// Custom Search		
		$criteria->with = array(
			'company.view' => array(
				'alias' => 'company_v',
				'select' => 'company_name'
			),
			'industry.view' => array(
				'alias' => 'industry_v',
				'select' => 'industry_name'
			),
			'creation' => array(
				'alias' => 'creation',
				'select' => 'displayname'
			),
			'modified' => array(
				'alias' => 'modified',
				'select' => 'displayname'
			),
		);

		$criteria->compare('t.id', $this->id);
		if(Yii::app()->getRequest()->getParam('type') == 'publish')
			$criteria->compare('t.publish', 1);
		elseif(Yii::app()->getRequest()->getParam('type') == 'unpublish')
			$criteria->compare('t.publish', 0);
		elseif(Yii::app()->getRequest()->getParam('type') == 'trash')
			$criteria->compare('t.publish', 2);
		else {
			$criteria->addInCondition('t.publish', array(0,1));
			$criteria->compare('t.publish', $this->publish);
		}
		if(Yii::app()->getRequest()->getParam('company'))
			$criteria->compare('t.company_id', Yii::app()->getRequest()->getParam('company'));
		else
			$criteria->compare('t.company_id', $this->company_id);
		if(Yii::app()->getRequest()->getParam('industry'))
			$criteria->compare('t.industry_id', Yii::app()->getRequest()->getParam('industry'));
		else
			$criteria->compare('t.industry_id', $this->industry_id);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.creation_date)', date('Y-m-d', strtotime($this->creation_date)));
		if(Yii::app()->getRequest()->getParam('creation'))
			$criteria->compare('t.creation_id', Yii::app()->getRequest()->getParam('creation'));
		else
			$criteria->compare('t.creation_id', $this->creation_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.modified_date)', date('Y-m-d', strtotime($this->modified_date)));
		if(Yii::app()->getRequest()->getParam('modified'))
			$criteria->compare('t.modified_id', Yii::app()->getRequest()->getParam('modified'));
		else
			$criteria->compare('t.modified_id', $this->modified_id);
		
		$criteria->compare('company_v.company_name', strtolower($this->company_name_i), true);
		$criteria->compare('industry_v.industry_name', strtolower($this->industry_name_i), true);
		$criteria->compare('creation.displayname', strtolower($this->creation_search), true);
		$criteria->compare('modified.displayname', strtolower($this->modified_search), true);

		if(!Yii::app()->getRequest()->getParam('IpediaCompanyIndustry_sort'))
			$criteria->order = 't.id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>30,
			),
		));
	}


	/**
	 * Get column for CGrid View
	 */
	public function getGridColumn($columns=null) {
		if($columns !== null) {
			foreach($columns as $val) {
				/*
				if(trim($val) == 'enabled') {
					$this->defaultColumns[] = array(
						'name'  => 'enabled',
						'value' => '$data->enabled == 1? "Ya": "Tidak"',
					);
				}
				*/
				$this->defaultColumns[] = $val;
			}
		} else {
			//$this->defaultColumns[] = 'id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'company_id';
			$this->defaultColumns[] = 'industry_id';
			$this->defaultColumns[] = 'creation_date';
			$this->defaultColumns[] = 'creation_id';
			$this->defaultColumns[] = 'modified_date';
			$this->defaultColumns[] = 'modified_id';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			/*
			$this->defaultColumns[] = array(
				'class' => 'CCheckBoxColumn',
				'name' => 'id',
				'selectableRows' => 2,
				'checkBoxHtmlOptions' => array('name' => 'trash_id[]')
			);
			*/
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			if(!Yii::app()->getRequest()->getParam('company')) {
				$this->defaultColumns[] = array(
					'name' => 'company_name_i',
					'value' => '$data->company->view->company_name',
				);
			}
			if(!Yii::app()->getRequest()->getParam('industry')) {
				$this->defaultColumns[] = array(
					'name' => 'industry_name_i',
					'value' => '$data->industry->view->industry_name',
				);
			}
			$this->defaultColumns[] = array(
				'name' => 'creation_search',
				'value' => '$data->creation->displayname',
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_date',
				'value' => 'Yii::app()->dateFormatter->formatDateTime($data->creation_date, \'medium\', false)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'creation_date'),
			);
			if(!Yii::app()->getRequest()->getParam('type')) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("publish", array("id"=>$data->id)), $data->publish, 1)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter' => $this->filterYesNo(),
					'type' => 'raw',
				);
			}
		}
		parent::afterConstruct();
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id, array(
				'select' => $column,
			));
			if(count(explode(',', $column)) == 1)
				return $model->$column;
			else
				return $model;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;
		}
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {		
			if($this->isNewRecord)
				$this->creation_id = Yii::app()->user->id;	
			else
				$this->modified_id = Yii::app()->user->id;
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() {
		if(parent::beforeSave()) {
			if($this->isNewRecord) {
				if($this->company_id == 0) {
					$company = IpediaCompanies::model()->with('view')->find(array(
						'select' => 't.company_id',
						'condition' => 'view.company_name = :company',
						'params' => array(
							':company' => strtolower(trim($this->company_name_i)),
						),
					));
					if($company != null)
						$this->company_id = $company->company_id;
					else {
						$data = new IpediaCompanies;
						$data->company_name_i = $this->company_name_i;
						if($data->save())
							$this->company_id = $data->company_id;
					}					
				}
				
				if($this->industry_id == 0) {
					$industry = IpediaIndustries::model()->with('view')->find(array(
						'select' => 't.industry_id',
						'condition' => 'view.industry_name = :industry',
						'params' => array(
							':industry' => strtolower(trim($this->industry_name_i)),
						),
					));
					if($industry != null)
						$this->industry_id = $industry->industry_id;
					else {
						$data = new IpediaIndustries;
						$data->industry_name_i = $this->industry_name_i;
						if($data->save())
							$this->industry_id = $data->industry_id;
					}					
				}
			}
		}
		return true;
	}

}