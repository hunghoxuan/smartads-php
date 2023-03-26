/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    config.extraPlugins = 'imageuploader';
    var firstDot = location.href.indexOf('backend/web/index.php');
    if (firstDot == -1)
        firstDot = location.href.indexOf('admin');

    domain = location.href.substring(0, firstDot);
    config.filebrowserBrowseUrl =  domain + 'apps/imageuploader/imgbrowser.php';
    //config.filebrowserBrowseUrl =  domain + '/vendor/2amigos/yii2-ckeditor-widget/src/assets/ckeditor/plugins/imageuploader/imgbrowser.php';

    config.uploadUrl = domain + '/applications/mozaweb/upload';
};
