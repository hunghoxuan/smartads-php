<?php

namespace backend\modules\smartscreen\models;


/**



 * This is the customized model class for table "smartscreen_layouts".
 */
class SmartscreenLayoutsAPI extends SmartscreenLayoutsSearch
{
    //Customize fields to be displayed in API
    const COLUMNS_API = ['id', 'name', 'description', 'is_active', 'appilication_id',];

    public function fields()
    {
        $fields = $this::COLUMNS_API;

        return $fields;
    }

    public function rules()
    {
        //No Rules required for API object
        return [];
    }

    public function getFrameQuery()
    {
        $query = SmartscreenFrame::find();
        $query->multiple = true;
        $query->innerJoin('smartscreen_layouts_frame', 'smartscreen_layouts_frame.frame_id = smartscreen_frame.id');
        $query->andWhere(['smartscreen_layouts_frame.layout_id' => $this->id]);
        $query->orderBy(['smartscreen_layouts_frame.sort_order' => SORT_ASC]);
        return $query;
    }
}
