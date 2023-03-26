<?php

if (!function_exists('seo_friendly')) {
	function seo_friendly($string) {
		//    // 1) convert á ô => a o
		$utf8 = array(
			'/[áàâãªäáàảãạăắặằẳẵâấầẩẫậ]/u' => 'a',
			'/[ÁÀÂÃÄÁÀẢÃẠĂẮẶẰẲẴÂẤẦẨẪẬ]/u'  => 'A',
			'/[ÍÌÎÏÍÌỈĨỊ]/u'               => 'I',
			'/[íìîïíìỉĩị]/u'               => 'i',
			'/[éèêëéèẻẽẹêếềểễệ]/u'         => 'e',
			'/[ÉÈÊËÉÈẺẼẸÊẾỀỂỄỆ]/u'         => 'E',
			'/[óòôõºöóòỏõọôốồổỗộơớờởỡợ]/u' => 'o',
			'/[ÓÒÔÕÖÓÒỎÕỌÔỐỒỔỖỘƠỚỜỞỠỢ]/u'  => 'O',
			'/[ÝỲỶỸỴ]/u'                   => 'Y',
			'/[ýỳỷỹỵ]/u'                   => 'y',

			'/[Đ]/u' => 'D',
			'/[đ]/u' => 'd',

			'/[úùûüúùủũụưứừửữự]/u' => 'u',
			'/[ÚÙÛÜÚÙỦŨỤƯỨỪỬỮỰ]/u' => 'U',
			'/ç/'                  => 'c',
			'/Ç/'                  => 'C',
			'/ñ/'                  => 'n',
			'/Ñ/'                  => 'N',
			'/–/'                  => '-', // UTF-8 hyphen to "normal" hyphen
			'/[’‘‹›‚]/u'           => ' ', // Literally a single quote
			'/[“”«»„]/u'           => ' ', // Double quote
			'/ /'                  => ' ', // nonbreaking space (equiv. to 0x160)
		);

		$string = preg_replace(array_keys($utf8), array_values($utf8), $string);

		//2) Translation CP1252. &ndash; => -
		$trans           = get_html_translation_table(HTML_ENTITIES);
		$trans[chr(130)] = '&sbquo;';    // Single Low-9 Quotation Mark
		$trans[chr(131)] = '&fnof;';    // Latin Small Letter F With Hook
		$trans[chr(132)] = '&bdquo;';    // Double Low-9 Quotation Mark
		$trans[chr(133)] = '&hellip;';    // Horizontal Ellipsis
		$trans[chr(134)] = '&dagger;';    // Dagger
		$trans[chr(135)] = '&Dagger;';    // Double Dagger
		$trans[chr(136)] = '&circ;';    // Modifier Letter Circumflex Accent
		$trans[chr(137)] = '&permil;';    // Per Mille Sign
		$trans[chr(138)] = '&Scaron;';    // Latin Capital Letter S With Caron
		$trans[chr(139)] = '&lsaquo;';    // Single Left-Pointing Angle Quotation Mark
		$trans[chr(140)] = '&OElig;';    // Latin Capital Ligature OE
		$trans[chr(145)] = '&lsquo;';    // Left Single Quotation Mark
		$trans[chr(146)] = '&rsquo;';    // Right Single Quotation Mark
		$trans[chr(147)] = '&ldquo;';    // Left Double Quotation Mark
		$trans[chr(148)] = '&rdquo;';    // Right Double Quotation Mark
		$trans[chr(149)] = '&bull;';    // Bullet
		$trans[chr(150)] = '&ndash;';    // En Dash
		$trans[chr(151)] = '&mdash;';    // Em Dash
		$trans[chr(152)] = '&tilde;';    // Small Tilde
		$trans[chr(153)] = '&trade;';    // Trade Mark Sign
		$trans[chr(154)] = '&scaron;';    // Latin Small Letter S With Caron
		$trans[chr(155)] = '&rsaquo;';    // Single Right-Pointing Angle Quotation Mark
		$trans[chr(156)] = '&oelig;';    // Latin Small Ligature OE
		$trans[chr(159)] = '&Yuml;';    // Latin Capital Letter Y With Diaeresis
		$trans['euro']   = '&euro;';    // euro currency symbol
		ksort($trans);

		foreach ($trans as $k => $v) {
			$string = str_replace($v, $k, $string);
		}

		//    // 3) remove <p>, <br/> ...
		$string = strip_tags($string);
		//
		//    // 4) &amp; => & &quot; => '
		$string = html_entity_decode($string);
		//
		//    // 5) remove Windows-1252 symbols like "TradeMark", "Euro"...
		//    $text = preg_replace('/[^(\x20-\x7F)]*/', '', $text);

		$targets = array('\r\n', '\n', '\r', '\t', '&', '<', '>', "\\", "/", ' ', '[\', \']');
		$results = array(" ", " ", " ", "", 'and', '.', '.', '-', '-', '-', '', '');
		$string  = str_replace($targets, $results, $string);

		$string = preg_replace('/\[.*\]/U', '', $string);
		$string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
		$string = htmlentities($string, ENT_COMPAT, 'utf-8');
		$string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string);
		$string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/'), '-', $string);

		return strtolower(trim($string, '-'));
	}
}

if (!function_exists('remove_utf8')) {
	function remove_utf8($str) {

	}
}

if (!function_exists('is_utf8')) {

	function is_utf8($Str) {
		for ($i = 0; $i < strlen($Str); $i++) {
			if (ord($Str[$i]) < 0x80) {
				$n = 0;
			} # 0bbbbbbb
			elseif ((ord($Str[$i]) & 0xE0) == 0xC0) {
				$n = 1;
			} # 110bbbbb
			elseif ((ord($Str[$i]) & 0xF0) == 0xE0) {
				$n = 2;
			} # 1110bbbb
			elseif ((ord($Str[$i]) & 0xF0) == 0xF0) {
				$n = 3;
			} # 1111bbbb
			else {
				return false;
			} # Does not match any model
			for ($j = 0; $j < $n; $j++) { # n octets that match 10bbbbbb follow ?
				if ((++$i == strlen($Str)) || ((ord($Str[$i]) & 0xC0) != 0x80)) {
					return false;
				}
			}
		}

		return true;
	}
}

if (!function_exists('console_log')) {

    function console_log($data)
    {
        if (is_array($data) || is_object($data)) {
            $data = json_encode($data);
        }

        echo(@"<script>
            if(console.debug!='undefined'){
                console.log('PHP: $data');
            }</script>");

    }
}
