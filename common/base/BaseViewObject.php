<?php

namespace common\base;

use backend\models\ObjectAttributes;
use backend\models\ObjectCategory;
use backend\models\ObjectTranslation;
use backend\models\ObjectProperties;
use backend\modules\wp\models\WpPosts;
use common\components\FActiveDataProvider;
use common\components\FActiveQuery;
use common\components\FActiveQueryPHPFile;
use common\components\FActiveQueryWordpress;
use common\components\FConfig;
use common\components\FConstant;
use common\components\FFrontend;
use common\components\FHtml;
use common\components\FModel;
use common\components\FSecurity;
use common\models\BaseModel;
use frontend\models\ViewModel;
use kcfinder\path;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\base\UnknownMethodException;
use yii\base\UnknownPropertyException;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

class BaseViewObject extends BaseModelObject
{
    public static function tableName()
    {
        $arr = explode('\\', get_called_class());
        $name = $arr[count($arr) - 1];

        if (StringHelper::endsWith($name, 'API'))
            $name = str_replace('API', '', $name);

        if (StringHelper::endsWith($name, 'Search'))
            $name = str_replace("Search", "", $name);

        $name = BaseInflector::camel2id($name);
        $name = str_replace('-', '_', $name);
        return $name;
    }

    /**
     * @var
     */
    protected $tableName;

    public function getTableName()
    {
        if (!empty($this->tableName))
            return $this->tableName;
        else {
            $this->tableName = static::tableName();
            return $this->tableName;
        }
    }

    public function createListUrl($controller = '', $params = []) {
        return FFrontend::createListUrl($controller, $this, $params);
    }

    public function createViewUrl($controller = '', $params = []) {
        return FFrontend::createViewUrl($controller, $this, $params);
    }

    public function createViewDetailUrl($controller = '', $params = []) {
        return FFrontend::createViewDetailUrl($controller, $this, $params);
    }

    #WORDPRESS START
    public function getWpPostId()
    {
        return $this->getCustomAttribute('wp_post_id');
    }

    public function setWpPostId($value)
    {
        $this->setCustomFieldValue('wp_post_id', $value);
    }

    public function getCustomAttribute($field, $default_value = null) {
        return $default_value;
    }

    public function setCustomFieldValue($field, $value) {
        return;
    }

    public function getWPPost($post_id = null) {

        if (empty($post_id))
            $post_id = $this->wp_post_id;

        $db = $this->getWPDb();

        if (empty($post_id) || !isset($db) || !FModel::isTableExisted(WpPosts::tableName()))
            return null;

        return WpPosts::find()->where(['ID' => $post_id])->one();
    }

    public function getWPDb() {
        return WpPosts::getDb();
    }

    public function getWPContent($post_id = null) {
        $model = $this->getWPPost($post_id);
        if (empty($model))
            return '';
        return $model->post_content;
    }
    #WORDPRESS END

    public function toViewModel()
    {
        $model = new ViewModel();
        $model->name = FHtml::getFieldValue($this, ['name', 'title']);
        $model->overview = FHtml::getFieldValue($this, ['overview', 'description']);
        $model->content = FHtml::getFieldValue($this, ['content', 'text', 'comment']);
        $model->thumbnail = FHtml::getFieldValue($this, ['thumbnail', 'image', 'icon', 'avatar']);
        $model->image = FHtml::getFieldValue($this, ['image', 'file', 'thumbnail', 'icon', 'avatar']);
        $model->is_active = FHtml::getFieldValue($this, ['is_active', 'active', 'isActive', 'status']);
        $model->is_hot = FHtml::getFieldValue($this, ['is_hot', 'is_popular', 'isHot']);
        $model->is_top = FHtml::getFieldValue($this, ['is_top', 'is_featured', 'isTop']);
        $model->is_featured = FHtml::getFieldValue($this, ['is_featured', 'is_top', 'isTop']);
        $model->category_id = trim(FHtml::getFieldValue($this, ['category_id', 'categoryid']), ',');
        $model->type = FHtml::getFieldValue($this, ['type']);
        $model->status = FHtml::getFieldValue($this, ['status']);
        $model->linkurl = FHtml::getFieldValue($this, ['linkurl']);
        $model->id = FHtml::getFieldValue($this, ['id', 'product_id']);
        $model->price = FHtml::getFieldValue($this, ['price', 'cost']);
        $model->created_date = FHtml::getFieldValue($this, ['created_date', 'created_at', 'createdDate', 'date_created']);
        $model->created_user = FHtml::getFieldValue($this, ['created_user', 'created_by', 'created_userid', 'createduser']);

        $model->tablename = Inflector::id2camel(StringHelper::basename(static::className()));
        return $model;
    }

//    //common fields
//    public function getName()
//    {
//        return $this->getCustomAttribute('name');
//    }
//
//    public function setName($value)
//    {
//        $this->setCustomFieldValue('name', $value);
//    }
//
//    public function getCode()
//    {
//        return $this->getCustomAttribute('code');
//    }
//
//    public function setCode($value)
//    {
//        $this->setCustomFieldValue('code', $value);
//    }
//
//    public function getTitle()
//    {
//        return $this->getCustomAttribute('title');
//    }
//
//    public function setTitle($value)
//    {
//        $this->setCustomFieldValue('title', $value);
//    }
//
//    public function getDescription()
//    {
//        return $this->getCustomAttribute('description');
//    }
//
//    public function setDescription($value)
//    {
//        $this->setCustomFieldValue('description', $value);
//    }
//
//    public function getImage()
//    {
//        return $this->getCustomAttribute('image');
//    }
//
//    public function setImage($value)
//    {
//        $this->setCustomFieldValue('image', $value);
//    }
//
//    public function getSlug()
//    {
//        return $this->getCustomAttribute('slug');
//    }
//
//    public function setSlug($value)
//    {
//        $this->setCustomFieldValue('slug', $value);
//    }
//
//    public function getKeywords()
//    {
//        return $this->getCustomAttribute('keywords');
//    }
//
//    public function setKeywords($value)
//    {
//        $this->setCustomFieldValue('keywords', $value);
//    }

}
