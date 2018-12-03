<?php
	require("./cfg.ecoCompteur.php");
	require("./class.cache.php");
	$cache = new cache();

	function aff($data) {
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}
	function csv2json4log($csv) {
		$previous=false;
		$csv=explode("\r",$csv);
		$tab=array();
		foreach($csv as $line) {
			if(trim($line)!="") {
				$aLine=explode(";",$line);
				if(!$previous) {
					$previous=$aLine;
				}
				else {
					$tmp=$aLine;
					$tmp[0]=trim($tmp[0]);
					for($i=7;$i<17;$i++) {
						$tmp[$i]=(string)($tmp[$i]-$previous[$i]);
					}
					$tab[]=$tmp;			
					$previous=$aLine;
				}
			}
		}
		//aff($tab);
		return json_encode($tab);
	}
	function readRemoteFile($url) {
		//return file_get_contents($url);
		$ch=curl_init();
		$timeout=30;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		
		$result=curl_exec($ch);
		$info=curl_getinfo($ch);
		//echo "**********************cURL-INFO-BEGIN*********************\n";
		//aff($info);
		//echo "***********************cURL-INFO-END**********************\n";
		curl_close($ch);
		
		return $result;
	}
	
	if(isset($_GET['part'])) {
		switch($_GET['part']) {
			case "inst.json":
				$json=readRemoteFile($urlEcoCompteur_inst);
				echo $json;
				break;
			case "data.json":
				$json=readRemoteFile($urlEcoCompteur_data);
				echo $json;
				break;
			case "log1.csv":
				if($cache->is_old("log1",24*60*60)) {
					$csv=readRemoteFile($urlEcoCompteur_log1);
					$cache->set("log1",$csv);
				}
				else {
					$csv=$cache->get('log1');
				}
				echo csv2json4log($csv);
				break;
			case "log2.csv":
				if($cache->is_old("log2",60*60)) {
					$csv=readRemoteFile($urlEcoCompteur_log2);
					$cache->set("log2",$csv);
				}
				else {
					$csv=$cache->get('log2');
				}
				echo csv2json4log($csv);
				break;
			default:
				echo "Bad request";
				break;
		}
	}
	else {
		echo "Bad request";
	}
?>