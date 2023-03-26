<?php
/**
* Developed by Hung Ho (Steve): hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "SmartscreenStation".
*/
use yii\helpers\Html;
use common\components\FHtml;
use common\components\Helper;


?>
    <?= \backend\modules\smartscreen\Qms::showXml($model,
    [
        'STATION' => ['Server', 'StartPort', 'MyID'],
        'UTILITY' => ['Language', 'UserName', 'MACAddress', 'LicenseKey', 'ZeroPrefix'],
        'BASICFORM' => ['BannerEnable', 'TopBanner', 'BotBanner', 'BasicFormHeader', 'MaxLinePerPage'],
        'QINFO_THEME' =>
            '        <Caption_BackColor>-16777152</Caption_BackColor>
        <Caption_TextColor>-1</Caption_TextColor>
        <Caption_FontName>[Font: Name=Microsoft Sans Serif, Size=26, Units=3, GdiCharSet=1, GdiVerticalFont=False]</Caption_FontName>
        <Caption_FontStyle>1</Caption_FontStyle>
        <OddServiceName_BackColor>-3675397</OddServiceName_BackColor>
        <OddServiceName_TextColor>-16753750</OddServiceName_TextColor>
        <OddServiceName_FontName>[Font: Name=Microsoft Sans Serif, Size=18, Units=3, GdiCharSet=0, GdiVerticalFont=False]</OddServiceName_FontName>
        <OddServiceName_FontStyle>1</OddServiceName_FontStyle>
        <OddNumberTitle_BackColor>-1</OddNumberTitle_BackColor>
        <OddNumberTitle_TextColor>-65536</OddNumberTitle_TextColor>
        <OddNumberTitle_FontName>[Font: Name=Microsoft Sans Serif, Size=12, Units=3, GdiCharSet=0, GdiVerticalFont=False]</OddNumberTitle_FontName>
        <OddNumberTitle_FontStyle>1</OddNumberTitle_FontStyle>
        <OddNumber_BackColor>-1</OddNumber_BackColor>
        <OddNumber_TextColor>-16740396</OddNumber_TextColor>
        <OddNumber_FontName>[Font: Name=Microsoft Sans Serif, Size=31.5, Units=3, GdiCharSet=1, GdiVerticalFont=False]</OddNumber_FontName>
        <OddNumber_FontStyle>1</OddNumber_FontStyle>
        <SameAsOdd>False</SameAsOdd>
        <EvenServiceName_BackColor>-12582848</EvenServiceName_BackColor>
        <EvenServiceName_TextColor>-1</EvenServiceName_TextColor>
        <EvenServiceName_FontName>[Font: Name=Microsoft Sans Serif, Size=18, Units=3, GdiCharSet=0, GdiVerticalFont=False]</EvenServiceName_FontName>
        <EvenServiceName_FontStyle>1</EvenServiceName_FontStyle>
        <EvenNumberTitle_BackColor>-1</EvenNumberTitle_BackColor>
        <EvenNumberTitle_TextColor>-8355776</EvenNumberTitle_TextColor>
        <EvenNumberTitle_FontName>[Font: Name=Microsoft Sans Serif, Size=10.25, Units=3, GdiCharSet=1, GdiVerticalFont=False]</EvenNumberTitle_FontName>
        <EvenNumberTitle_FontStyle>1</EvenNumberTitle_FontStyle>
        <EvenNumber_BackColor>-1</EvenNumber_BackColor>
        <EvenNumber_TextColor>-12582848</EvenNumber_TextColor>
        <EvenNumber_FontName>[Font: Name=Microsoft Sans Serif, Size=31, Units=3, GdiCharSet=1, GdiVerticalFont=False]</EvenNumber_FontName>
        <EvenNumber_FontStyle>1</EvenNumber_FontStyle>',
        'WINDOWFORM' => ['Logo', 'TopBanner', 'BotBanner', 'ClipHeader', 'ClipFooter', 'BottomScrollText', 'BackgroundColor', 'TextColor', 'FontName', 'FontStyle', 'CallingTextBackgroundColor', 'CallingTextTextColor', 'CallingTextFontName', 'CallingTextFontStyle'],
        'CLIP' => ['Clipnum', 'ClipVolume', 'ClipFullScreen', 'Clip1', 'Clip2', 'Clip3', 'Clip4', 'Clip5', 'Clip6', 'Clip7', 'Clip8', 'Clip9', 'Clip10', 'Clip11', 'Clip12', 'Clip13', 'Clip14', 'Clip15', 'Clip16'],
        'COMMAND' => ['CommandNumber', 'Line1', 'Line2', 'Line3', 'Line4', 'Line5', 'Line6', 'Line7', 'Line8', 'Line9', 'Line10', 'Line11', 'Line12', 'Line13', 'Line14', 'Line15', 'Line16'],

    ],
    'SmartScreen',
    [
        'FontName' => '[Font: Name=OptimaVU, Size=27.75, Units=3, GdiCharSet=0, GdiVerticalFont=False]',
        'FontStyle' => 0
    ]
);
?>
