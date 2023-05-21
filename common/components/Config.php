<?php

/**



 * This is the customized model class for table "<?= $generator->generateTableName($tableName) ?>".
 */

namespace common\components;


class Config
{
    public static function get($param, $default_value = null)
    {
        return FConfig::setting($param, $default_value);
    }
}
