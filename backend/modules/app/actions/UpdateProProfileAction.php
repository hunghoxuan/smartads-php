<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppUserAPI;
use backend\modules\app\models\AppUserProAPI;
use backend\modules\transport\models\TransportDriverAPI;
use backend\modules\transport\models\TransportVehicleAPI;
use common\components\Response;
use Imagine\Image\Box;
use Yii;
use yii\imagine\Image;

class UpdateProProfileAction extends BaseAction
{

    public $is_secured = true;

    public function run()
    {
        if (($re = $this->isAuthorized())!== true)
            return $re;

        $user_id = $this->user_id;
        //SHIT BEGINS
        $avatar_file = isset($_FILES['avatar']) ? $_FILES['avatar'] : null;
        //SHIT END
        //pro data

        $business_description = isset($_REQUEST['business_description']) ? $_REQUEST['business_description'] : '';
        $business_name = isset($_REQUEST['business_name']) ? $_REQUEST['business_name'] : '';
        $business_email = isset($_REQUEST['business_email']) ? $_REQUEST['business_email'] : '';
        $business_address = isset($_REQUEST['business_address']) ? $_REQUEST['business_address'] : '';
        $business_website = isset($_REQUEST['business_website']) ? $_REQUEST['business_website'] : '';
        $business_phone = isset($_REQUEST['business_phone']) ? $_REQUEST['business_phone'] : '';

        //driver data


        $driver_experience = isset($_REQUEST['driver_experience']) ? $_REQUEST['driver_experience'] : '';

        $driver_license = isset($_FILES['driver_license']) ? $_FILES['driver_license'] : null;

        $fare = isset($_REQUEST['fare']) ? $_REQUEST['fare'] : '';
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
        $is_delivery = isset($_REQUEST['is_delivery']) ? $_REQUEST['is_delivery'] : '';  //1 / 0


        //vehicle data
        $image_file = isset($_FILES['image']) ? $_FILES['image'] : null;
        $permit_file = isset($_FILES['permit']) ? $_FILES['permit'] : null;
        $insurance_file = isset($_FILES['insurance']) ? $_FILES['insurance'] : null;

        $yearly_km = isset($_REQUEST['yearly_km']) ? $_REQUEST['yearly_km'] : '';
        $yearly_insurance = isset($_REQUEST['yearly_insurance']) ? $_REQUEST['yearly_insurance'] : '';
        $yearly_maintenance = isset($_REQUEST['yearly_maintenance']) ? $_REQUEST['yearly_maintenance'] : '';
        $yearly_tax = isset($_REQUEST['yearly_tax']) ? $_REQUEST['yearly_tax'] : '';
        $yearly_gara = isset($_REQUEST['yearly_gara']) ? $_REQUEST['yearly_gara'] : '';
        $yearly_unexpected = isset($_REQUEST['yearly_unexpected']) ? $_REQUEST['yearly_unexpected'] : '';
        $year_intend = isset($_REQUEST['year_intend']) ? $_REQUEST['year_intend'] : '';
        $price_4_new_tyres = isset($_REQUEST['price_4_new_tyres']) ? $_REQUEST['price_4_new_tyres'] : '';
        $average_consumption = isset($_REQUEST['average_consumption']) ? $_REQUEST['average_consumption'] : '';
        $fuel_unit_price = isset($_REQUEST['fuel_unit_price']) ? $_REQUEST['fuel_unit_price'] : '';
        $fuel_type = isset($_REQUEST['fuel_type']) ? $_REQUEST['fuel_type'] : '';
        $sold_value = isset($_REQUEST['sold_value']) ? $_REQUEST['sold_value'] : '';
        $bought_value = isset($_REQUEST['bought_value']) ? $_REQUEST['bought_value'] : '';

        $plate = isset($_REQUEST['plate']) ? $_REQUEST['plate'] : '';
        $brand = isset($_REQUEST['brand']) ? $_REQUEST['brand'] : '';
        $model = isset($_REQUEST['model']) ? $_REQUEST['model'] : '';
        $color = isset($_REQUEST['color']) ? $_REQUEST['color'] : '';
        $year = isset($_REQUEST['year']) ? $_REQUEST['year'] : '';
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
        $description = isset($_REQUEST['description']) ? $_REQUEST['description'] : '';

        $driver_path = Yii::getAlias('@' . UPLOAD_DIR) . '/' . TRANSPORT_DRIVER_DIR . '/';
        $vehicle_path = Yii::getAlias('@' . UPLOAD_DIR) . '/' . TRANSPORT_VEHICLE_DIR . '/';
        $user_path = Yii::getAlias('@' . UPLOAD_DIR) . '/' . APP_USER_DIR . '/';

        $update_pro = false;
        $update_driver = false;
        $update_vehicle = false;


        if (
            strlen($business_description) != 0
            || strlen($business_name) != 0
            || strlen($business_email) != 0
            || strlen($business_phone) != 0
            || strlen($business_website) != 0
            || strlen($business_address) != 0
        ) {
            $update_pro = true;
        }

        if (
            strlen($driver_experience) != 0
            || strlen($fare) != 0
            || strlen($type) != 0
            || strlen($is_delivery) != 0
            || isset($driver_license)
        ) {
            $update_driver = true;
        }


        if (
            isset($image_file)
            || isset($permit_file)
            || isset($insurance_file)
            || strlen($yearly_km) != 0
            || strlen($yearly_insurance) != 0
            || strlen($yearly_maintenance) != 0
            || strlen($yearly_tax) != 0
            || strlen($yearly_gara) != 0
            || strlen($yearly_unexpected) != 0
            || strlen($year_intend) != 0
            || strlen($price_4_new_tyres) != 0
            || strlen($average_consumption) != 0
            || strlen($fuel_unit_price) != 0
            || strlen($fuel_type) != 0
            || strlen($sold_value) != 0
            || strlen($bought_value) != 0
            || strlen($plate) != 0
            || strlen($brand) != 0
            || strlen($model) != 0
            || strlen($color) != 0
            || strlen($year) != 0
            || strlen($status) != 0
            || strlen($description) != 0
        ) {
            $update_vehicle = true;
        }

        $check = AppUserAPI::findOne($user_id);

        $upload_driver_license = false;
        $upload_vehicle_image = false;
        $upload_vehicle_permit = false;
        $upload_vehicle_insurance = false;
        //$upload_avatar = false;


        if (isset($check)) {


            $now = time();
            $today = date('Y-m-d H:i:s', $now);

            $old_pro = $check->pro;
            $old_driver = $check->driver;
            $old_vehicle = $check->vehicle;

            $oldLicense = null;
            $oldPermit = null;
            $oldImage = null;
            $oldInsurance = null;

            if (isset($old_pro)) {
                if ($update_pro) {
                    $old_pro->user_id = $user_id;
                    $old_pro->description = $business_description;
                    $old_pro->business_name = $business_name;
                    $old_pro->business_email = $business_email;
                    $old_pro->business_phone = $business_phone;
                    $old_pro->business_address = $business_address;
                    $old_pro->business_website = $business_website;
                    $old_pro->modified_date = $today;
                }
            } else {
                if ($update_pro) {
                    $old_pro = new AppUserProAPI();
                    $old_pro->user_id = $user_id;
                    $old_pro->description = $business_description;
                    $old_pro->business_name = $business_name;
                    $old_pro->business_email = $business_email;
                    $old_pro->business_phone = $business_phone;
                    $old_pro->business_address = $business_address;
                    $old_pro->business_website = $business_website;
                    $old_pro->rate_count = 0;
                    $old_pro->is_active = \Globals::STATE_ACTIVE;
                    $old_pro->created_date = $today;
                }
            }
            $x = $yearly_km/$average_consumption;
            $y = $x * $fuel_unit_price;
            $z = ($price_4_new_tyres / 350000)*$yearly_km;
            $w = ($bought_value - $sold_value)/$year_intend;

            $q = ($y + $z + $w + $yearly_tax + $yearly_gara + $yearly_insurance + $yearly_maintenance + $yearly_unexpected) / $yearly_km;

            if (isset($old_driver)) {
                $oldLicense = $old_driver->driver_license;
                if ($update_driver) {
                    $old_driver->user_id = $user_id;
                    $old_driver->driver_experience = $driver_experience;
                    $old_driver->fare = $q;
                    $old_driver->type = $type;
                    $old_driver->is_delivery = $is_delivery;
                    $old_driver->modified_date = $today;
                }
            } else {
                if ($update_driver) {
                    $old_driver = new TransportDriverAPI();
                    $old_driver->user_id = $user_id;
                    $old_driver->driver_experience = $driver_experience;
                    $old_driver->fare = $q;
                    $old_driver->type = $type;
                    $old_driver->is_delivery = $is_delivery;
                    //$old_driver->is_active = \Globals::STATE_INACTIVE;
                    $old_driver->is_active = \Globals::STATE_ACTIVE;
                    $old_driver->is_online = \Globals::STATE_INACTIVE;
                    $old_driver->created_date = $today;
                }
            }

            if (isset($old_vehicle)) {
                $oldImage = $old_vehicle->image;
                $oldPermit = $old_vehicle->permit;
                $oldInsurance = $old_vehicle->insurance;
                if ($update_vehicle) {
                    $old_vehicle->user_id = $user_id;
                    $old_vehicle->yearly_km = $yearly_km;
                    $old_vehicle->yearly_insurance = $yearly_insurance;
                    $old_vehicle->yearly_maintenance = $yearly_maintenance;
                    $old_vehicle->yearly_tax = $yearly_tax;
                    $old_vehicle->yearly_gara = $yearly_gara;
                    $old_vehicle->yearly_unexpected = $yearly_unexpected;
                    $old_vehicle->year_intend = $year_intend;
                    $old_vehicle->price_4_new_tyres = $price_4_new_tyres;
                    $old_vehicle->average_consumption = $average_consumption;
                    $old_vehicle->fuel_unit_price = $fuel_unit_price;
                    $old_vehicle->fuel_type = $fuel_type;
                    $old_vehicle->sold_value = $sold_value;
                    $old_vehicle->bought_value = $bought_value;
                    $old_vehicle->plate = $plate;
                    $old_vehicle->brand = $brand;
                    $old_vehicle->model = $model;
                    $old_vehicle->color = $color;
                    $old_vehicle->year = $year;
                    $old_vehicle->status = $status;
                    $old_vehicle->description = $description;
                    $old_vehicle->modified_date = $today;
                }
            } else {
                if ($update_vehicle) {
                    $old_vehicle = new TransportVehicleAPI();
                    $old_vehicle->user_id = $user_id;
                    $old_vehicle->yearly_km = $yearly_km;
                    $old_vehicle->yearly_insurance = $yearly_insurance;
                    $old_vehicle->yearly_maintenance = $yearly_maintenance;
                    $old_vehicle->yearly_tax = $yearly_tax;
                    $old_vehicle->yearly_gara = $yearly_gara;
                    $old_vehicle->yearly_unexpected = $yearly_unexpected;
                    $old_vehicle->year_intend = $year_intend;
                    $old_vehicle->price_4_new_tyres = $price_4_new_tyres;
                    $old_vehicle->average_consumption = $average_consumption;
                    $old_vehicle->fuel_unit_price = $fuel_unit_price;
                    $old_vehicle->fuel_type = $fuel_type;
                    $old_vehicle->sold_value = $sold_value;
                    $old_vehicle->bought_value = $bought_value;
                    $old_vehicle->plate = $plate;
                    $old_vehicle->brand = $brand;
                    $old_vehicle->model = $model;
                    $old_vehicle->color = $color;
                    $old_vehicle->year = $year;
                    $old_vehicle->status = $status;
                    $old_vehicle->description = $description;
                    $old_vehicle->created_date = $today;
                }
            }


            if (isset($driver_license)) {
                $licenseExt = pathinfo($driver_license['name'], PATHINFO_EXTENSION);
                $licenseName = $now . 'license.' . $licenseExt;

                $license_file_path = $driver_path . $licenseName;
                $upload_driver_license = move_uploaded_file($driver_license['tmp_name'], $license_file_path);

                if ($upload_driver_license) {
                    $old_driver->driver_license = $licenseName;
                }
            }

            if (isset($image_file)) {
                $imageExt = pathinfo($image_file['name'], PATHINFO_EXTENSION);
                $imageName = $now . 'image.' . $imageExt;

                $image_file_path = $vehicle_path . $imageName;
                $upload_vehicle_image = move_uploaded_file($image_file['tmp_name'], $image_file_path);

                if ($upload_vehicle_image) {
                    $old_vehicle->image = $imageName;
                }
            }

            if (isset($permit_file)) {
                $permitExt = pathinfo($permit_file['name'], PATHINFO_EXTENSION);
                $permitName = $now . 'image.' . $permitExt;

                $permit_file_path = $vehicle_path . $permitName;
                $upload_vehicle_permit = move_uploaded_file($permit_file['tmp_name'], $permit_file_path);

                if ($upload_vehicle_permit) {
                    $old_vehicle->permit = $permitName;
                }
            }

            if (isset($insurance_file)) {
                $insuranceExt = pathinfo($insurance_file['name'], PATHINFO_EXTENSION);
                $insuranceName = $now . 'image.' . $insuranceExt;

                $insurance_file_path = $vehicle_path . $insuranceName;
                $upload_vehicle_insurance = move_uploaded_file($insurance_file['tmp_name'], $insurance_file_path);

                if ($upload_vehicle_insurance) {
                    $old_vehicle->insurance = $insuranceName;
                }
            }

            if(isset($avatar_file)){
                $ext = pathinfo($avatar_file['name'], PATHINFO_EXTENSION);
                $avatarName = $now . 'avatar.'. $ext;

                $avatar_path = $user_path . $avatarName;
                $upload_avatar = move_uploaded_file($avatar_file['tmp_name'], $avatar_path);

                if($upload_avatar){
                    $check->avatar = $avatarName;
                    Image::getImagine()->open($avatar_path)
                        ->thumbnail(new Box(100, 100))
                        ->save($user_path . 'thumb' . $avatarName , ['quality' => 100]);
                    $check->save();
                }
            }

            if (isset($old_pro) && $update_pro) {
                $old_pro->save();
            }
            if (isset($old_driver) && $update_driver) {
                if ($old_driver->save()) {
                    if (!is_null($oldLicense) && $upload_driver_license) {
                        if (is_file($driver_path . '/' . $oldLicense)) {
                            unlink($driver_path . '/' . $oldLicense);
                        }
                    }
                }
            }

            if (isset($old_vehicle) && $update_vehicle) {
                if ($old_vehicle->save()) {
                    if (isset($oldImage) && $upload_vehicle_image) {
                        if (is_file($vehicle_path . '/' . $oldImage)) {
                            unlink($vehicle_path . '/' . $oldImage);
                        }
                    }

                    if (isset($oldPermit) && $upload_vehicle_permit) {
                        if (is_file($vehicle_path . '/' . $oldPermit)) {
                            unlink($vehicle_path . '/' . $oldPermit);
                        }
                    }

                    if (isset($oldInsurance) && $upload_vehicle_insurance) {
                        if (is_file($vehicle_path . '/' . $oldInsurance)) {
                            unlink($vehicle_path . '/' . $oldInsurance);
                        }
                    }
                }
            }

            $check = AppUserAPI::findOne($user_id);

            $vehicle_data = $check->vehicle;
            $driver_data = $check->driver;
            $pro_data = $check->pro;

            $data = $check;
            $data->pro_data = $pro_data;
            $data->driver_data = $driver_data;
            $data->vehicle_data = $vehicle_data;

            return Response::getOutputForAPI($data, \Globals::SUCCESS, 'OK', ['code'=> 200]);

        } else {
            return Response::getOutputForAPI('', \Globals::ERROR, \Globals::USER_NOT_FOUND, ['code'=> 221]);

        }
    }
}
