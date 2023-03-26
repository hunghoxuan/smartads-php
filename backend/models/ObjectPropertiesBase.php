<?php

namespace backend\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "object_properties".
 *
 * @property integer $id
 * @property integer $object_id
 * @property string $object_type
 * @property string $description
 * @property string $content
 * @property string $properties
 * @property string $translations
 * @property string $code
 * @property string $title
 * @property string $image
 * @property string $type
 * @property string $status
 * @property string $category_id
 * @property string $tags
 * @property string $keywords
 * @property integer $view_count
 * @property integer $download_count
 * @property integer $purchase_count
 * @property integer $like_count
 * @property integer $comment_count
 * @property integer $edit_count
 * @property integer $favourite_count
 * @property string $created_date
 * @property integer $created_user
 * @property string $modified_date
 * @property integer $modified_user
 * @property string $application_id
 * @property string $history
 * @property string $rate
 * @property integer $rate_count
 * @property string $comments
 * @property string $address
 * @property string $address2
 * @property string $mobile
 * @property string $phone
 * @property string $email1
 * @property string $email2
 * @property string $coordinate
 * @property integer $is_top
 * @property integer $is_active
 * @property integer $is_hot
 * @property integer $is_new
 * @property integer $is_discount
 * @property integer $is_vip
 * @property integer $is_promotion
 * @property integer $is_expired
 * @property integer $is_completed
 * @property string $author_name
 * @property integer $author_id
 * @property integer $user_id
 * @property integer $product_id
 * @property integer $provider_id
 * @property string $product_name
 * @property string $provider_name
 * @property integer $publisher_id
 * @property string $publisher_name
 * @property string $banner
 * @property string $thumbnail
 * @property string $video
 * @property string $link_url
 * @property string $district
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string $zip_code
 */
class ObjectPropertiesBase extends \common\models\BaseModel //\yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $tableName = 'object_properties';

    public static function tableName()
    {
        return 'object_properties';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'object_id', 'object_type', 'description', 'content', 'properties', 'translations', 'code', 'title', 'image', 'type', 'status', 'category_id', 'tags', 'keywords', 'view_count', 'download_count', 'purchase_count', 'like_count', 'comment_count', 'edit_count', 'favourite_count', 'created_date', 'created_user', 'modified_date', 'modified_user', 'application_id', 'history', 'rate', 'rate_count', 'comments', 'address', 'address2', 'mobile', 'phone', 'email1', 'email2', 'coordinate', 'is_top', 'is_active', 'is_hot', 'is_new', 'is_discount', 'is_vip', 'is_promotion', 'is_expired', 'is_completed', 'author_name', 'author_id', 'user_id', 'product_id', 'provider_id', 'product_name', 'provider_name', 'publisher_id', 'publisher_name', 'banner', 'thumbnail', 'video', 'link_url', 'district', 'city', 'state', 'country', 'zip_code'], 'filter', 'filter' => 'trim'],
            [['object_id', 'object_type'], 'required'],
            [['object_id', 'view_count', 'download_count', 'purchase_count', 'like_count', 'comment_count', 'edit_count', 'favourite_count', 'created_user', 'modified_user', 'rate_count', 'is_top', 'is_active', 'is_hot', 'is_new', 'is_discount', 'is_vip', 'is_promotion', 'is_expired', 'is_completed', 'author_id', 'user_id', 'product_id', 'provider_id', 'publisher_id'], 'integer'],
            [['content', 'properties', 'translations', 'history', 'comments'], 'string'],
            [['created_date', 'modified_date'], 'safe'],
            [['rate'], 'number'],
            [['object_type', 'type', 'status', 'category_id', 'application_id', 'district', 'city', 'state', 'country'], 'string', 'max' => 100],
            [['description', 'tags', 'keywords', 'address', 'address2'], 'string', 'max' => 2000],
            [['code', 'mobile', 'phone', 'email1', 'email2', 'coordinate', 'author_name', 'product_name', 'provider_name', 'publisher_name', 'zip_code'], 'string', 'max' => 255],
            [['title'], 'string', 'max' => 555],
            [['image', 'banner', 'thumbnail', 'video', 'link_url'], 'string', 'max' => 300],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('ObjectProperties', 'ID'),
            'object_id' => FHtml::t('ObjectProperties', 'Object ID'),
            'object_type' => FHtml::t('ObjectProperties', 'Object Type'),
            'description' => FHtml::t('ObjectProperties', 'Description'),
            'content' => FHtml::t('ObjectProperties', 'Content'),
            'properties' => FHtml::t('ObjectProperties', 'Properties'),
            'translations' => FHtml::t('ObjectProperties', 'Translations'),
            'code' => FHtml::t('ObjectProperties', 'Code'),
            'title' => FHtml::t('ObjectProperties', 'Title'),
            'image' => FHtml::t('ObjectProperties', 'Image'),
            'type' => FHtml::t('ObjectProperties', 'Type'),
            'status' => FHtml::t('ObjectProperties', 'Status'),
            'category_id' => FHtml::t('ObjectProperties', 'Category ID'),
            'tags' => FHtml::t('ObjectProperties', 'Tags'),
            'keywords' => FHtml::t('ObjectProperties', 'Keywords'),
            'view_count' => FHtml::t('ObjectProperties', 'View Count'),
            'download_count' => FHtml::t('ObjectProperties', 'Download Count'),
            'purchase_count' => FHtml::t('ObjectProperties', 'Purchase Count'),
            'like_count' => FHtml::t('ObjectProperties', 'Like Count'),
            'comment_count' => FHtml::t('ObjectProperties', 'Comment Count'),
            'edit_count' => FHtml::t('ObjectProperties', 'Edit Count'),
            'favourite_count' => FHtml::t('ObjectProperties', 'Favourite Count'),
            'created_date' => FHtml::t('ObjectProperties', 'Created Date'),
            'created_user' => FHtml::t('ObjectProperties', 'Created User'),
            'modified_date' => FHtml::t('ObjectProperties', 'Modified Date'),
            'modified_user' => FHtml::t('ObjectProperties', 'Modified User'),
            'application_id' => FHtml::t('ObjectProperties', 'Application ID'),
            'history' => FHtml::t('ObjectProperties', 'History'),
            'rate' => FHtml::t('ObjectProperties', 'Rate'),
            'rate_count' => FHtml::t('ObjectProperties', 'Rate Count'),
            'comments' => FHtml::t('ObjectProperties', 'Comments'),
            'address' => FHtml::t('ObjectProperties', 'Address'),
            'address2' => FHtml::t('ObjectProperties', 'Address2'),
            'mobile' => FHtml::t('ObjectProperties', 'Mobile'),
            'phone' => FHtml::t('ObjectProperties', 'Phone'),
            'email1' => FHtml::t('ObjectProperties', 'Email1'),
            'email2' => FHtml::t('ObjectProperties', 'Email2'),
            'coordinate' => FHtml::t('ObjectProperties', 'Coordinate'),
            'is_top' => FHtml::t('ObjectProperties', 'Is Top'),
            'is_active' => FHtml::t('ObjectProperties', 'Is Active'),
            'is_hot' => FHtml::t('ObjectProperties', 'Is Hot'),
            'is_new' => FHtml::t('ObjectProperties', 'Is New'),
            'is_discount' => FHtml::t('ObjectProperties', 'Is Discount'),
            'is_vip' => FHtml::t('ObjectProperties', 'Is Vip'),
            'is_promotion' => FHtml::t('ObjectProperties', 'Is Promotion'),
            'is_expired' => FHtml::t('ObjectProperties', 'Is Expired'),
            'is_completed' => FHtml::t('ObjectProperties', 'Is Completed'),
            'author_name' => FHtml::t('ObjectProperties', 'Author Name'),
            'author_id' => FHtml::t('ObjectProperties', 'Author ID'),
            'user_id' => FHtml::t('ObjectProperties', 'User ID'),
            'product_id' => FHtml::t('ObjectProperties', 'Product ID'),
            'provider_id' => FHtml::t('ObjectProperties', 'Provider ID'),
            'product_name' => FHtml::t('ObjectProperties', 'Product Name'),
            'provider_name' => FHtml::t('ObjectProperties', 'Provider Name'),
            'publisher_id' => FHtml::t('ObjectProperties', 'Publisher ID'),
            'publisher_name' => FHtml::t('ObjectProperties', 'Publisher Name'),
            'banner' => FHtml::t('ObjectProperties', 'Banner'),
            'thumbnail' => FHtml::t('ObjectProperties', 'Thumbnail'),
            'video' => FHtml::t('ObjectProperties', 'Video'),
            'link_url' => FHtml::t('ObjectProperties', 'Link Url'),
            'district' => FHtml::t('ObjectProperties', 'District'),
            'city' => FHtml::t('ObjectProperties', 'City'),
            'state' => FHtml::t('ObjectProperties', 'State'),
            'country' => FHtml::t('ObjectProperties', 'Country'),
            'zip_code' => FHtml::t('ObjectProperties', 'Zip Code'),
        ];
    }


}