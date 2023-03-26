<?php

namespace backend\modules\system\actions;

use backend\actions\BaseAction;
use common\components\FApi;
use common\components\FConstant;
use common\components\FHtml;

class SocialListAction extends BaseAction
{
    public function run()
    {
        $folder = 'www/social';
        $now = FHtml::Now();
        $source = [
            [
                'key' => 'facebook',
                'name' => 'Facebook',
                'image' => 'facebook.png',
            ],
            [
                'key' => 'twitter',
                'name' => 'Twitter',
                'image' => 'twitter.png',
            ],
            [
                'key' => 'google',
                'name' => 'Gooole',
                'image' => 'google-plus.png',
            ],
            [
                'key' => 'instagram',
                'name' => 'Instagram',
                'image' => 'instagram.png',
            ],
            [
                'key' => 'skype',
                'name' => 'Skype',
                'image' => 'skype.png',
            ],
            [
                'key' => 'telegram',
                'name' => 'Telegram',
                'image' => 'telegram.png',
            ]
        ];

        $data = array();
        foreach ($source as $item) {
            $image = '';
            if (isset($item['image']) && strlen($item['image']) != 0) {
                $image = FApi::getImageUrlForAPI($item['image'], $folder);

            }
            $data[] = [
                'key' => $item['key'],
                'name' => $item['name'],
                'image' => $image,
            ];
        }
        return FApi::getOutputForAPI($data, FConstant::SUCCESS, 'OK', [
            'code' => 200,
            'time' => $now,
            'object_type' => ''
        ]);
    }
}
