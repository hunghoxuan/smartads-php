<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 9/7/2016
 * Time: 10:31 AM
 */

namespace common\models;

use backend\models\ObjectCategory;
use common\Base\BaseDataObject;
use common\components\FHtml;
use yii\base\Model;

/*
 * @property string $id;
 * @property string $description;
 * @property string $css;
 * @property string $notes;
 * @property string $lang;
 * @property string $linkurl;
 * @property string $id;
 * @property string $icon;
 * @property string $color;
 * @property string $thumbnail;
 * @property string $image;
 * @property string $barcode;
 * @property string $qrcode;
 * @property string $code;
 * @property string $name;
 * @property string $title;
 * @property string $overview;
 * @property string $content;
 * @property string $cost;
 * @property string $price;
 * @property string $unit;
 * @property string $currency;
 * @property string $type;
 * @property string $status;
 * @property string $brand;
 * @property string $category_id;
 * @property string $is_active;
 * @property string $is_featured;
 * @property string $is_popular;
 * @property string $is_promotion;
 * @property string $promotion_id;
 * @property string $is_featured;
 * @property string $tags;
 * @property string $quantity;
 * @property string $discount;
 * @property string $tax;
 * @property string $is_tax_included;
 * @property string $count_views;
 * @property string $count_comments;
 * @property string $count_purchase;
 * @property string $count_likes;
 * @property string $count_rates;
  * @property string $banner;
 * @property string $rates;
 * @property string $created_date;
 * @property string $created_user;
 * @property string $modified_date;
 * @property string $modified_user;
 * @property string $application_id;
 * @property string $category;
 * @property string $rate;
 * @property string $galleries;
 *
 *
 */

class ViewModel extends BaseModel
{
    public $id = '';
    public $description = '';
    public $css = '';
    public $notes = '';
    public $icon = '';
    public $color = '';
    public $lang = '';
    public $linkurl = '';
    public $thumbnail = '';
    public $image = '';
    public $banner = '';
    public $barcode = '';
    public $qrcode = '';
    public $code = '';
    public $name = '';
    public $title = '';
    public $overview = '';
    public $content = '';
    public $cost = '';
    public $author = '';
    public $price = '';
    public $unit = '';
    public $currency = '';
    public $type = '';
    public $status = '';
    public $brand = '';
    public $category_id = '';
    public $is_active = '';
    public $is_featured = '';
    public $is_top = '';
    public $is_new = '';
    public $is_hot = '';
    public $is_popular = '';
    public $is_promotion = '';
    public $promotion_id = '';
    public $tags = '';
    public $rate;
    public $quantity = '';
    public $discount = '';
    public $tax = '';
    public $is_tax_included = '';
    public $count_views = '';
    public $count_comments = '';
    public $count_purchase = '';
    public $count_likes = '';
    public $count_rates = '';
    public $rates = '';
    public $created_date = '';
    public $created_user = '';
    public $modified_date = '';
    public $modified_user = '';
    public $application_id = '';
    public $tableName;
    public $object_id;
    public $object_type;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('common', 'ID'),
            'description' => FHtml::t('common', 'Description'),
            'css' => FHtml::t('common', 'CSS'),
            'notes' => FHtml::t('common', 'Notes'),
            'lang' => FHtml::t('common', 'Lang'),
            'linkurl' => FHtml::t('common', 'Linkurl'),
            'is_popular' => FHtml::t('common', 'Is Popular'),
            'thumbnail' => FHtml::t('common', 'Thumbnail'),
            'image' => FHtml::t('common', 'Image'),
            'barcode' => FHtml::t('common', 'Barcode'),
            'qrcode' => FHtml::t('common', 'Qrcode'),
            'code' => FHtml::t('common', 'Code'),
            'name' => FHtml::t('common', 'Name'),
            'overview' => FHtml::t('common', 'Overview'),
            'content' => FHtml::t('common', 'Content'),
            'cost' => FHtml::t('common', 'Cost'),
            'price' => FHtml::t('common', 'Price'),
            'unit' => FHtml::t('common', 'Unit'),
            'currency' => FHtml::t('common', 'Currency'),
            'type' => FHtml::t('common', 'Type'),
            'status' => FHtml::t('common', 'Status'),
            'brand' => FHtml::t('common', 'Brand'),
            'category_id' => FHtml::t('common', 'Category ID'),
            'is_active' => FHtml::t('common', 'Is Active'),
            'is_hot' => FHtml::t('common', 'Is Hot'),
            'is_top' => FHtml::t('common', 'Is Top'),
            'is_featured' => FHtml::t('common', 'Is Hot'),
            'is_promotion' => FHtml::t('common', 'Is Promotion'),
            'promotion_id' => FHtml::t('common', 'Promotion ID'),
            'tags' => FHtml::t('common', 'Tags'),
            'quantity' => FHtml::t('common', 'Quantity'),
            'discount' => FHtml::t('common', 'Discount'),
            'tax' => FHtml::t('common', 'Tax'),
            'is_tax_included' => FHtml::t('common', 'Is Tax Included'),
            'count_views' => FHtml::t('common', 'Count Views'),
            'count_comments' => FHtml::t('common', 'Count Comments'),
            'count_purchase' => FHtml::t('common', 'Count Purchase'),
            'count_likes' => FHtml::t('common', 'Count Likes'),
            'count_rates' => FHtml::t('common', 'Count Rates'),
            'rates' => FHtml::t('common', 'Rates'),
            'created_date' => FHtml::t('common', 'Created Date'),
            'created_user' => FHtml::t('common', 'Created User'),
            'modified_date' => FHtml::t('common', 'Modified Date'),
            'modified_user' => FHtml::t('common', 'Modified User'),
            'application_id' => FHtml::t('common', 'Application ID')
        ];
    }

    public static function dummy()
    {
        $model = new ViewModel();
        $model->generateDummy();
        return $model;
    }

    public function generateDummy()
    {
        $this->id = FHtml::generateRandomInArray([10, 3, 4, 6, 102]);
        $this->description = FHtml::generateRandomInArray(['Donec id elit non mi porta gravida at eget metus. Fusce dapibus, justo sit amet risus etiam porta sem...', 'This is dummy text', 'Hello, how are you']);
        $this->css = '';
        $this->notes = FHtml::generateRandomInArray(['Donec id elit non mi porta gravida at eget metus. Fusce dapibus, justo sit amet risus etiam porta sem...', 'Love you', 'CEO ...']);;
        $this->lang = '';
        $this->linkurl = '';
        $this->thumbnail = '';
        $this->image = FHtml::generateRandomInArray(['/hostagent/avatar.jpg', '/hostagent/avatar2.jpg', '/gallery/1.png', '/gallery/2.png', '/gallery/3.png', '/gallery/4.png', '/gallery/5.png', '/gallery/6.png']);
        $this->barcode = '';
        $this->qrcode = '';
        $this->code = FHtml::generateRandomInArray(['001', '109830', 'ABC', 'AT002', 'Moza007', 'PT008']);
        $this->color = FHtml::generateRandomInArray(['default', 'sea', 'warning', 'purple', 'blue', 'green']);
        $this->icon = FHtml::generateRandomInArray(['fa fa-lightbulb-o', 'icon-line icon-fire', 'icon-line icon-rocket']);
        $this->name = FHtml::generateRandomInArray(['David Beckham', 'Brad Pitt', 'Angela Jolie']);
        $this->overview = FHtml::generateRandomInArray(['Donec id elit non mi porta gravida at eget metus. Fusce dapibus, justo sit amet risus etiam porta sem...', 'Love you', 'CEO ...']);;
        $this->content = FHtml::generateRandomInArray(['Donec id elit non mi porta gravida at eget metus. Fusce dapibus, justo sit amet risus etiam porta sem...', 'Love you', 'CEO ...']);;
        $this->cost = rand(10, 1000);
        $this->price = rand(10, 1000);
        $this->unit = '';
        $this->currency = '$';
        $this->type = FHtml::generateRandomInArray(['Post', 'New', 'Featured']);
        $this->status = FHtml::generateRandomInArray(['Post', 'New', 'Featured']);
        $this->brand = '';
        $this->category_id = FHtml::generateRandomInArray([10, 9, 4, 6, 102]);
        $this->is_active = FHtml::generateRandomInArray([0, 1]);
        $this->is_featured = FHtml::generateRandomInArray([0, 1]);
        $this->is_popular = FHtml::generateRandomInArray([0, 1]);
        $this->is_promotion = FHtml::generateRandomInArray([0, 1]);
        $this->promotion_id = FHtml::generateRandomInArray([0, 1]);
        $this->tags = '';
        $this->quantity = rand(10, 1000);
        $this->discount = rand(10, 100);
        $this->tax = rand(10, 50);
        $this->is_tax_included = rand(10, 1000);
        $this->count_views = rand(10, 1000);
        $this->count_comments = rand(10, 1000);
        $this->count_purchase = rand(10, 1000);
        $this->count_likes = rand(10, 1000);
        $this->count_rates = rand(10, 1000);
        $this->rates = '';
        $this->created_date = date('php:Y-m-d');
        $this->created_user = FHtml::generateRandomInArray(['David Beckham', 'Brad Pitt', 'Angela Jolie']);
        $this->modified_date = date('php:Y-m-d');
        $this->modified_user = FHtml::generateRandomInArray(['David Beckham', 'Brad Pitt', 'Angela Jolie']);
        $this->application_id = '';
    }

    public function getEmployee()
    {
        return self::dummy();
    }

    public $employees;
    public $classes;
    public $tablename = '';
    public $products;
    public $galleries;
    public $category_id_array = '';

    public function loadModel($model = false, $type)
    {

        if ($model) {

            /* @var ViewModel $model */
//            $this->id = FHtml::getMo;
            $image = '';
            foreach ($model->galleries as $item) {
                $image = $item->image;
                continue;
            }
            $this->image = $image;
            $this->name = $model->name;
            $this->description = $model->description;
            $this->content = $model->content;
            $this->category_id = $model->categoryId;
            // $this->category = $model->category;
            $this->galleries = $model->galleries;
            $this->employees = $model->employees;
            $this->classes = $model->classes;
            $this->price = $model->price;
            $this->status = $model->status;
            $this->is_featured = $model->isFeatured;

        } else {
            $this->generateDummy();
        }
    }

    public function getCategories()
    {
        return FHtml::getCategories($this->category_id);
    }

    public function getCategory()
    {
        $arr = FHtml::getCategories($this->category_id);
        if (count($arr) > 0)
            return $arr[0];
        return new ObjectCategory();
    }

    public function getGalleries()
    {
        if (!isset($this->galleries))
            $this->galleries = FHtml::getGalleries($this->tablename, $this->id);
        return $this->galleries;
    }
    public function showImage($width = '', $height = '', $fields = [],  $css = '', $showEmptyImage = true)
    {
        if (empty($fields))
            $fields = ['image', 'avatar', 'banner'];
        return FHtml::showImage($this->getFieldValue($fields, '', true), str_replace('_', '-', $this->tableName), $width, $height, $css, strip_tags($this->getFieldValue(['tags', 'overview', 'description', 'name', 'title'])), $showEmptyImage);
    }
}