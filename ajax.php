<?php
	require("./cfg.ecoCompteur.php");
	require("./class.cache.php");
	$cache = new cache();
	date_default_timezone_set('UTC');

	function aff($data) {
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}
	function csv2json4log($csv,$aggregate=false) {
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
						$tmp[$i]=(string)round($tmp[$i]-$previous[$i],3);
					}
					if($aggregate!==false) {
						if($aggregate=="semaine") {
							$timestamp=mktime($tmp[3],$tmp[4],0,$tmp[1],$tmp[0],$tmp[2]);
							$number=date('W',$timestamp);
						}
						else if($aggregate=="mois") {
							$number=$tmp[1];
						}
						unset($tmp[0]);
						unset($tmp[1]);
						unset($tmp[2]);
						unset($tmp[3]);
						$tmp[4]=$number;
						if(isset($tab[$number])) {
							for($i=6;$i<36;$i++) {
								$tab[$number][$i]+=$tmp[$i];
							}
						}
						else {
							$tab[$number]=$tmp;
						}
					}
					else {
						$tab[]=$tmp;
					}
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
	function getData($url,$cacheID,$delai) {
		global $cache;
		
		if($cache->is_old($cacheID,$delai)) {
			$csv=readRemoteFile($url);
			$cache->set($cacheID,$csv);
		}
		else {
			$csv=$cache->get($cacheID);
		}
		
		return $csv;
	}
	if(isset($_GET['part'])) {
		switch($_GET['part']) {
			case "inst.json":
				$json=readRemoteFile($urlEcoCompteur_inst);
				echo $json;
				break;
			case "data.json":
				$json=getData($urlEcoCompteur_data,"data",60*60*24);
				echo $json;
				break;
			case "log1.csv":
				$csv=getData($urlEcoCompteur_log1,"log1",60*60*24);
				if(isset($_GET['aggregate']) && $_GET['aggregate']=="semaine") {
					echo csv2json4log($csv,"semaine");
				}
				else if(isset($_GET['aggregate']) && $_GET['aggregate']=="mois") {
					echo csv2json4log($csv,"mois");
				}
				else {
					echo csv2json4log($csv);
				}
				break;
			case "log2.csv":
				$csv=getData($urlEcoCompteur_log2,"log2",60*60);
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