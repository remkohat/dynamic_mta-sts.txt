<?php

include_once './conf/config.php';

$mx = "";
$mxhosts = "";
$weight = "";

$domain = preg_replace("~^mta-sts\.~", "", $_SERVER['HTTP_HOST']);

if (str_starts_with($_SERVER['HTTP_HOST'], "mta-sts.")) {
	if (checkdnsrr("_mta-sts.".$domain, "TXT")) {
		if ($mode == "enforce" || $mode == "testing") {
			if (dns_get_mx($domain, $mxhosts, $weight)) {
				array_multisort($weight, $mxhosts);
				for ($i = 0; $i < count($mxhosts); $i++) {
					$mx = $mx . "mx: " . $mxhosts[$i] . "\n";
				}
				if (!($max_age >= "86400" && $max_age <= "31557600")) {
					header("Location:http://".$domain."/");
				}
			} else {
				header("Location:http://".$domain."/");
			}
		} elseif ($mode == "none") {
			$mx = "mx: none\n";
			$max_age = "86400";
		} else {
			header("Location:http://".$domain."/");
		}
	} else {
		header("Location:http://".$domain."/");
	}
} else {
	header("Location:http://".$domain."/");
}

$mta_sts = "version: STSv1"."\n"."mode: ".$mode."\n".$mx."max_age: ".$max_age;

?>
