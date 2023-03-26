<?php

namespace backend\modules\smartscreen\models;

/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "settings".
 */
class Settings extends \backend\models\Settings
{
	public $cms_blogs_type;
	public $application_id;

	public function save($runValidation = true, $attributeNames = null) {
		parent::save($runValidation, $attributeNames);

		return true;
	}
}
