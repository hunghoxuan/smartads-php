<?php

namespace backend\modules\smartscreen\models;

/**



 * This is the customized model class for table "settings".
 */
class Settings extends \backend\models\Settings
{
	public $cms_blogs_type;
	public $application_id;

	public function save($runValidation = true, $attributeNames = null)
	{
		parent::save($runValidation, $attributeNames);

		return true;
	}
}
