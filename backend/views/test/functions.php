<?php 
set_time_limit(0);
define('DIR_NAME', dirname(__DIR__));
header('Content-type:text/plain; charset=utf8');

define('DOMAIN', 'http://thuviendongtay.com');
$url = DOMAIN . '/thu-vien/danh-muc/';

function connect() {
    $conn2 = mysqli_connect("localhost", "root", "dongtaycf", "dongtaycf") or die(mysqli_error());
    mysqli_query($conn2, "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
    return $conn2;
}

function copyFile($url, $host, $name, $folder) {
    $url = $host .$url;
    $extension = getExtensionWithHeaders($url);    
    $name      = $name. "." . $extension;
    $output_filename = DIR_NAME . "/" . $folder . "/" . $name;
    echo $output_filename;
    copy($url, $output_filename);
    return $name;
}

function getExtensionWithHeaders($url) {
    $header = get_headers($url, 1);
    $content_type = [];
    if (!empty($header)) {
        $content_type = $header['Content-Type'];
    }
    $image_mime = explode("/", $content_type);
    $extension  = str_replace("e", "", end($image_mime));
    return $extension;
}

function vn2latin($cs, $tolower = false) {
    /*Mảng chứa tất cả ký tự có dấu trong Tiếng Việt*/
    $marTViet = array(
        "à", "á", "ạ", "ả", "ã", "â", "ầ", "ấ", "ậ", "ẩ", "ẫ", "ă",
        "ằ", "ắ", "ặ", "ẳ", "ẵ", "è", "é", "ẹ", "ẻ", "ẽ", "ê", "ề",
        "ế", "ệ", "ể", "ễ",
        "ì", "í", "ị", "ỉ", "ĩ",
        "ò", "ó", "ọ", "ỏ", "õ", "ô", "ồ", "ố", "ộ", "ổ", "ỗ", "ơ",
        "ờ", "ớ", "ợ", "ở", "ỡ",
        "ù", "ú", "ụ", "ủ", "ũ", "ư", "ừ", "ứ", "ự", "ử", "ữ",
        "ỳ", "ý", "ỵ", "ỷ", "ỹ",
        "đ",
        "À", "Á", "Ạ", "Ả", "Ã", "Â", "Ầ", "Ấ", "Ậ", "Ẩ", "Ẫ", "Ă",
        "Ằ", "Ắ", "Ặ", "Ẳ", "Ẵ",
        "È", "É", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ề", "Ế", "Ệ", "Ể", "Ễ",
        "Ì", "Í", "Ị", "Ỉ", "Ĩ",
        "Ò", "Ó", "Ọ", "Ỏ", "Õ", "Ô", "Ồ", "Ố", "Ộ", "Ổ", "Ỗ", "Ơ", "Ờ", "Ớ", "Ợ", "Ở", "Ỡ",
        "Ù", "Ú", "Ụ", "Ủ", "Ũ", "Ư", "Ừ", "Ứ", "Ự", "Ử", "Ữ",
        "Ỳ", "Ý", "Ỵ", "Ỷ", "Ỹ",
        "Đ", " ", "_", "--", ",", ":", '"', ".", "'", "&", "%", "*", "@", "!", "#", "$", "^", "(", ")", "/", "\\", ")", "(", "{", "}", "[", "]", "?", "<", ">", "|", "+", "=", "`", "’");
    /*Mảng chứa tất cả ký tự không dấu tương ứng với mảng $marTViet bên trên*/
    $marKoDau = array("a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a",
        "a", "a", "a", "a", "a", "a",
        "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e",
        "i", "i", "i", "i", "i",
        "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o",
        "o", "o", "o", "o", "o",
        "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u",
        "y", "y", "y", "y", "y",
        "d",
        "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A",
        "A", "A", "A", "A", "A",
        "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E",
        "I", "I", "I", "I", "I",
        "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O",
        "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U",
        "Y", "Y", "Y", "Y", "Y",
        "D", "-");

    if ($tolower) {
        return strtolower(str_replace($marTViet,$marKoDau,$cs));
    }
    return str_replace($marTViet,$marKoDau,$cs);
}


function cloneAndUpdate($url) {
    $content = file_get_contents($url);
    preg_match_all('/<div class="item">(.*?)<\/div><\/div>/', $content, $matches);
    if (isset($matches) && !empty($matches)) {
        if (isset($matches[1]) && !empty($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                if (!empty($value)) {
                    preg_match('/<a class="wp-image"(.*?)>/', $value, $match_a);
                    preg_match('/href="(.+?)"/', $match_a[0], $match_href);
                    $href_array = explode("/", trim($match_href[1]));
                    $id = end($href_array);

                    preg_match('/<img(.+?)\/>/', $value, $match_img);
                    preg_match('/data-original="(.+?)"/', $match_img[0], $match_src);
                    $src = $match_src[1];
                    $image_array = explode("/", $match_src[1]);
                    $image = end($image_array);

                    $name = "";
                    preg_match('/<a class="book-title"(.*?)\/a>/', $value, $match_name);
                    preg_match('/>(.+?)</', $match_name[0], $name);

                    if ($image == "d816d472737b02d4a627e2b2c1bcebf595e18cc9" || empty($image)) {
                        $name = "no_image";
                    }
                    else {
                        $name = vn2latin($name[1], true) . $id . "_book";
                    }
                    $name = copyFile($src, DOMAIN . "/", $name, 'ecommerce-product');
                    $sql = "UPDATE ecommerce_product SET image = '$name' WHERE id=$id";
                    mysqli_query(connect(), $sql);
                }
                else 
                    continue;
            } // end foreach matches[1]
        }
    }
}

function getPage($url) {
    $content = file_get_contents($url);
    $page = 100;
    preg_match_all('/<a class="page-number" href=\"(.*?)\">/', $content, $matches);
    if (isset($matches) && !empty($matches)) {
        if (!empty($matches[1])) {
            $href = end($matches[1]);
            $page = explode("=", $href);
            $page = end($page);
        }
    }
    return $page;
}

function handle($url) {
	$page = getPage($url);
	for ($index_page = 1; $index_page <= $page; $index_page++) {
		$url_page = $url . "?p=" . $index_page;
		cloneAndUpdate($url_page);
	}
}
 ?>