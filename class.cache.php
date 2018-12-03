<?php
class cache {
	private $root_Path='';
	public function __construct() {
		$this->root_Path='./cache';
		$mode=0777;
		$path=$this->root_Path;
		if(!is_dir($path)) {
			mkdir($path);
			chmod($path,$mode);
		}
	}
    public function get($id) {
		$path=$this->create_path($id);
		if(file_exists($path)) {
			require($path);
			if(isset($cacheData)) {
				return $this->unserializeur($cacheData);
			}
		}

		return NULL;
    }
    public function set($id,$data) {
		$path=$this->create_path($id);
		$this->write($path,$data);
    }
    public function is_old($id,$duree) {
		$path=$this->create_path($id);
		if(file_exists($path)) {
			require($path);
			if(isset($cacheTime)) {
				$vieillesse=time()-$cacheTime;
				if($vieillesse>$duree) {
					return true;	
				}
			}
		}
		else {
			return true;
		}

		return false;
    }
    public function is_cached($id) {
		$path=$this->create_path($id);
		if(file_exists($path)) {
			require($path);
			if(isset($cacheData)) {
				return true;
			}
		}

		return false;
    }
	public function un_set($id) {
		$path=$this->create_path($id);
		if(file_exists($path)) {
			unlink($path);
		}
	}
	private function unserializeur($str) {
		return unserialize(stripslashes($str));
	}
	private function serializeur($str) {
		$search=array("\n","\t");
		$replace=array('','');
		$str=str_replace($search,$replace,$str);

		return addslashes(serialize($str));
	}
	private function write($path,$value) {
		$str='<?php'."\n";
		$str.='$cacheData=\''.$this->serializeur($value).'\';'."\n";
		$str.='$cacheTime=\''.time().'\';'."\n";
		$str.='?>';
		$fic=fopen($path,"w");
		fwrite($fic,$str);
		fclose($fic);
		$mode=0777;
		chmod($path,$mode);
	}
	private function create_path($id) {
		$out=$this->root_Path.'/'.$id.'.cache';

		return $out;
	}
}
?>