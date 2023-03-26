
<?php
session_start();
?>

<!-- Copyright (c) 2015, Fujana Solutions - Moritz Maleck. All rights reserved. -->
<!-- For licensing, see LICENSE.md -->

<?php

// Don't remove the following two rows
$link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$root = "http://$_SERVER[HTTP_HOST]";

// checking lang value
if(isset($_COOKIE['sy_lang'])) {
    $load_lang_code = $_COOKIE['sy_lang'];
} else {
    $load_lang_code = "en";
}

// including lang files
switch ($load_lang_code) {
    case "en":
        require(__DIR__ . '/lang/en.php');
        break;
    case "pl":
        require(__DIR__ . '/lang/pl.php');
        break;
}

// Including the plugin config file, don't delete the following row!
require(__DIR__ . '/pluginconfig.php');
// Including the functions file, don't delete the following row!
require(__DIR__ . '/function.php');
// Including the check_permission file, don't delete the following row!
require(__DIR__ . '/check_permission.php');

//MOZA START

$link = "$useruploadroot$_SERVER[REQUEST_URI]";
$root = $useruploadroot;

//if ($username == "" and $password == "") {
//    if(!isset($_SESSION['username'])){
//        include(__DIR__ . '/new.php');
//        exit;
//    }
//} else {
//    if(!isset($_SESSION['username'])){
//        include(__DIR__ . '/loginindex.php');
//        exit;
//    }
//}
$_SESSION["username"] = "disabled_pw"; //disable password
$news_sction = 'no';
$show_settings = false;
//MOZA END

?>

<!DOCTYPE html>
<html lang="en"
      ondragover="toggleDropzone('show')"
      ondragleave="toggleDropzone('hide')">
<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title><?php echo $imagebrowser1; ?></title>
    <meta name="author" content="Moritz Maleck">
    <link rel="icon" href="img/cd-ico-browser.ico">
    
    <link rel="stylesheet" href="styles.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="dist/jquery.lazyload.min.js"></script>
    <script src="dist/js.cookie-2.0.3.min.js"></script>
    
    <script src="dist/sweetalert.min.js"></script>
    <link rel="stylesheet" type="text/css" href="dist/sweetalert.css">
    
    <script src="function.js"></script>

</head>
<body ontouchstart="" style="margin:0px !important; max-width: none !important; padding: 20px !important; padding-top: 50px !important;">
    
<div id="header">
    <img onclick="uploadImg();" src="img/cd-icon-upload-grey.png" class="headerIconCenter iconHover">
    <div class="headerIconCenter" onclick="uploadImg();" style="padding-top:5px">
        UPLOAD
    </div>
    <img onclick="Cookies.remove('qEditMode');window.close();" src="img/cd-icon-close-grey.png" class="headerIconRight iconHover">
    <img onclick="reloadImages();" src="img/cd-icon-refresh.png" class="headerIconRight iconHover">

    <?php if($show_settings): ?>
    <img onclick="pluginSettings();" src="img/cd-icon-settings.png" class="headerIconRight iconHover">
    <?php endif; ?>
</div>
<br/>
<div id="editbar">
    <div id="editbarView" onclick="#" class="editbarDiv"><img src="img/cd-icon-images.png" class="editbarIcon editbarIconLeft"><p class="editbarText"><?php echo $buttons1; ?></p></div>
    <a href="#" id="editbarDownload" download><div class="editbarDiv"><img src="img/cd-icon-download.png" class="editbarIcon editbarIconLeft"><p class="editbarText"><?php echo $buttons2; ?></p></div></a>
    <div id="editbarUse" onclick="#" class="editbarDiv"><img src="img/cd-icon-use.png" class="editbarIcon editbarIconLeft"><p class="editbarText"><?php echo $buttons3; ?></p></div>
    <div id="editbarDelete" onclick="#" class="editbarDiv"><img src="img/cd-icon-qtrash.png" class="editbarIcon editbarIconLeft"><p class="editbarText"><?php echo $buttons4; ?></p></div>
    <img onclick="hideEditBar();" src="img/cd-icon-close-black.png" class="editbarIcon editbarIconRight">
</div>

<div id="updates" class="popout"></div>

<div id="dropzone" class="dropzone" 
     ondragenter="return false;"
     ondragover="return false;"
     ondrop="drop(event)">
    <p>
        <img src="img/cd-icon-upload-big.png"><br>
        <?php echo $imagebrowser4; ?>
    </p>
</div>

<p class="folderInfo"><?php echo $imagebrowser2; ?> <span id="finalcount"></span> <?php echo $imagebrowser3; ?> - <span id="finalsize"></span>
    <?php if($file_style == "block") { ?>
        <img title="List" src="img/cd-icon-list.png" class="headerIcon floatRight" onclick="window.location.href = 'pluginconfig.php?file_style=list';">
    <?php } elseif($file_style == "list") { ?>
        <img title="Block" src="img/cd-icon-block.png" class="headerIcon floatRight" onclick="window.location.href = 'pluginconfig.php?file_style=block';">
        <img title="Quick Edit" id="qEditBtnOpen" src="img/cd-icon-qedit.png" class="headerIcon floatRight" onclick="toogleQEditMode();">
        <img title="Quick Edit" id="qEditBtnDone" src="img/cd-icon-done.png" class="headerIcon floatRight" onclick="toogleQEditMode();">
    <?php } ?>
</p>

<div id="files">
    <?php
    loadImages();
    ?>
</div>


<div id="imageFullSreen" class="lightbox popout">
    <img id="imageFSimg" src="#" style="#"><br>
    <hr/>
    <div class="buttonBar">
        <a href="#" id="imgActionDownload" download><button class="headerBtn"><img src="img/cd-icon-download.png" class="headerIcon"></button></a>
        <button class="headerBtn greenBtn" id="imgActionUse" onclick="#" class="imgActionP"><img src="img/cd-icon-use.png" class="headerIcon"> <?php echo $buttons3; ?></button>
        <button id="imageFullSreenClose" class="headerBtn" onclick="$('#imageFullSreen').hide(); $('#background').slideUp(250, 'swing');"><img src="img/cd-icon-close.png" class="headerIcon"></button>
    </div><br><br><br><br>
</div>
    
<div id="uploadImgDiv" class="lightbox popout" style="text-align: left">
    <h3>SELECT A FILE</h3>
    <form action="imgupload.php" method="post" enctype="multipart/form-data" id="uploadImgForm" onsubmit="return checkUpload();">
        <input type="file" name="upload" id="upload" style="border: solid 1px grey; padding: 20px">
    </form>
    <hr/>
    <div class="buttonBar">
        <button class="headerBtn greenBtn btn-lg" name="submit" onclick="$('#uploadImgForm').submit();"><img src="img/cd-icon-upload.png" class="headerIcon"> <?php echo $buttons7; ?></button>
        <button class="headerBtn" onclick="$('#uploadImgDiv').hide(); $('#background2').slideUp(250, 'swing');"><img src="img/cd-icon-close.png" class="headerIcon"></button>

    </div><br><br><br>
</div>


<?php   
// Including the language file, don't delete the following row!
require(__DIR__ . '/lang/lang.php');
?>

</body>
</html>