<?php
session_start();
if (!isset($_SESSION['done'])) {
	// echo "string";
	header('Location:' . '../');
}

include 'simple_html_dom.php';
header('Content-Type: text/html; charset=utf8');

function phatammy($html, $check = false) {
	$ret = $html->find('span[class=speaker amefile fa fa-volume-up hideOnAmp]');
	if ($check) {
		$str = (string) $ret[2];
	} else {
		$str = (string) $ret[0];
	}
	$temp = explode('"', $str);
	$temp1 = explode('?', $temp[1]);
	$pamy = $temp1[0];
	return $pamy;
}

function phatamanh($html, $check = false) {
	$ret = $html->find('span[class=speaker brefile fa fa-volume-up hideOnAmp]');
	if ($check) {
		$str = (string) $ret[2];
	} else {
		$str = (string) $ret[0];
	}

	$temp = explode('"', $str);
	$temp1 = explode('?', $temp[1]);
	$paanh = $temp1[0];
	return $paanh;
}

function phienam($word) {
	if ($word === "a") {
		$backup = $word;
		$url = 'https://www.ldoceonline.com/dictionary/' . $word;
		$html = file_get_html($url);
		$pa = $html->find('span[class=PronCodes]', 1);
		$count = 0;
		foreach ($pa->find('span[class=PRON],span[class=AMEVARPRON],span[class=STRONG]') as $element) {
			$temp[$count] = (string) $element->innertext;
			$count++;
		}
		$pa = implode(" ", $temp);
		$pamy = phatammy($html, true);
		$paanh = phatamanh($html, true);
		$myarr = array("word" => $backup, "pa" => $pa, "pamy" => $pamy, "paanh" => $paanh);
		return $myarr;
	}
	$backup = $word;
	$url = 'https://www.ldoceonline.com/dictionary/' . $word;
	$html = file_get_html($url);
	$pa = $html->find('span[class=PronCodes]', 0);
	if ($pa) {
		$count = 0;
		foreach ($pa->find('span[class=PRON],span[class=AMEVARPRON],span[class=STRONG]') as $element) {
			$temp[$count] = (string) $element->innertext;
			$count++;
		}
		$pa = implode(" ", $temp);
		$pamy = phatammy($html);
		$paanh = phatamanh($html);
		$myarr = array("word" => $backup, "pa" => $pa, "pamy" => $pamy, "paanh" => $paanh);
		return $myarr;
	} else {
		if (substr($word, strlen($word) - 1, 1) == "s") {
			echo "Duoi s/es" . " ; ";
			$count = 0;
			do {
				$url = 'https://www.ldoceonline.com/dictionary/' . $word;
				$html = file_get_html($url);
				$pa = $html->find('span[class=PronCodes]', 0);
				if ($pa) {
					break;
				} else if (substr($word, -1) === "i") {
					$word[strlen($word) - 1] = "y";
					continue;
				}
				$word = substr($word, 0, -1);
				echo $word . "<br/>";
			} while (!$pa && $count < 3);
		} else {
			echo "Dang dac biet" . " ; ";
			$url = 'https://www.ldoceonline.com/search/english/direct/?q=' . $word;
			$html = file_get_html($url);
			$pa = $html->find('span[class=PronCodes]', 0);
		}
	}
	if ($pa) {
		$count = 0;
		foreach ($pa->find('span[class=PRON],span[class=AMEVARPRON],span[class=STRONG]') as $element) {
			$temp[$count] = (string) $element->innertext;
			$count++;
		}
		$pa = implode(" ", $temp);
		$pamy = phatammy($html);
		$paanh = phatamanh($html);
		$myarr = array("word" => $backup, "pa" => $pa, "pamy" => $pamy, "paanh" => $paanh);
		return $myarr;
	} else {
		$none = "https://d27ucmmhxk51xv.cloudfront.net/media/english/ameProns/know1.mp3";
		$myarr = array("word" => $backup, "pa" => "None", "pamy" => $none, "paanh" => $none);
		return $myarr;
	}
}

function bstring($word) {
	$after = trim($word);
	$chrep = [":", "/", "-"];
	$after = str_replace($chrep, " ", $after);
	$after1 = "";
	echo strlen($word);
	for ($i = 0; $i < strlen($after); $i++) {
		echo $after[$i] . "<br/>";
		if (ctype_alpha(substr($after, $i, 1)) || substr($after, $i, 1) === " " || substr($after, $i, 1) === "'") {
			$after1 = $after1 . $after[$i];
		}
	}
	while (strpos($after1, "  ")) {
		$after1 = str_replace("  ", " ", $after1);
	}
	return $after1;
}

$word = $_POST['word'];
$word = trim($word);
$_SESSION['original'] = $_POST['word'];
$x = mb_convert_encoding('&#x2019;', 'UTF-8', 'HTML-ENTITIES');
echo $x;
$word = str_replace($x, "'", $word);
echo $word . "<br/>";
$after = bstring($word);
echo "AFTER: " . $after . "<br/>";
$eachw = explode(" ", $after);
for ($i = 0; $i < sizeof($eachw); $i++) {
	if ($eachw[$i] === "sth") {
		$eachw[$i] = "something";
	}
	if ($eachw[$i] === "sb") {
		$eachw[$i] = "somebody";
	}
}
// echo '<pre>';
// print_r($eachw);
// echo '</pre>';

$count = 0;
$check = false;
$done = array();
for ($i = 0; $i < count($eachw); $i++) {
	echo $i . "<br/>";
	$subarr = array_slice($eachw, 0, $i);
	if (in_array($eachw[$i], $subarr)) {
		$done[$i] = $done[array_search($eachw[$i], $subarr)];
	} else {
		$done[$i] = phienam($eachw[$i]);
	}
}
//session
$_SESSION['done'] = $done;
header('Location: ' . '../');
?>