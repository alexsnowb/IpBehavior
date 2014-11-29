<?php

namespace app\components\behaviors;

use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

/**
 * IpBehavior automatically fills the specified attributes with the current user request ip address.
 *
 * To use IpBehavior, insert the following code to your ActiveRecord class:
 *
 * ```php
 * use yii\behaviors\IpBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         IpBehavior::className(),
 *     ];
 * }
 * ```
 *
 * By default, IpBehavior will fill the `ip` attributes with the current request ip address
 *
 * If your attribute names are different or you want to use a different way of set the ip address,
 * you may configure the [[ipAttribute]] and [[value]] properties like the following:
 *
 * ```php
 * use yii\db\Expression;
 *
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => IpBehavior::className(),
 *             'ipAttribute' => 'ip',
 *             'value' => \Yii::$app->request->userIP,
 *         ],
 *     ];
 * }
 * ```
 *
 *
 * @author AlexSnowb <mne@lexa.in>
 * @since 2.0
 */
class IpBehavior extends AttributeBehavior
{
	/**
	 * @var string the attribute that will receive ip address value
	 * Set this property to false if you do not want to record the ip address.
	 */
	public $ipAttribute = 'ip';

	/**
	 * @var string The value that will be used for generating the request ip address.
	 *
	 * Default \Yii::$app->request->userIP
	 */
	public $value;


	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		if (empty($this->attributes)) {
			$this->attributes = [
				BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->ipAttribute],
			];
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function getValue($event)
	{
			return $this->value !== null ? $this->value : \Yii::$app->request->userIP;
	}

	/**
	 * Updates a ip attribute.
	 *
	 * ```php
	 * $model->touch('ip');
	 * ```
	 * @param string $attribute the name of the attribute to update.
	 */
	public function touch($attribute)
	{
		$this->owner->updateAttributes(array_fill_keys((array) $attribute, $this->getValue(null)));
	}
}
