<?php


class cache_image {

	var $image;
	var $image_type;
	var $image_resizer;
	var $image_size_width;
	var $image_size_height;      

	public function __construct($config = array()) {
		$this->image_resizer = new image_resizer();
	}

	function cache_image($image,$max_width,$max_height) {
		$dir_interna = DIR_IMAGENES;
		return $this->cache_image_dir_interna($dir_interna, $image,$max_width,$max_height);
	}

	function cache_image_dir_interna($dir_interna, $image,$max_width,$max_height) {
		$dir_cache = DIR_CACHE;
		return $this->cache_image_dir_cache_dir_interna($dir_cache,$dir_interna, $image,$max_width,$max_height);
	}
	function height(){
		return $this->image_size_height ? $this->image_size_height : 0;
	}
	function width(){
		return $this->image_size_width ? $this->image_size_width : 0;
	}

	function cache_image_dir_cache_dir_interna($dir_cache,$dir_interna, $image,$max_width,$max_height) {


		$ext =  substr(strrchr($image,'.'),1);
		$cache_name = $image;
		$cache_name = str_replace('/','_',$cache_name); 
		$cache_name = str_replace('.'.$ext,'',$cache_name);                        
		$cache_name.='_'.$max_width.'_'.$max_height.'.'.$ext;

		//$cache_name= '_'.$max_width.'_'.$max_height.'_'.$cache_name;
		$cache_name= '_'.$cache_name;
		if(!$image) {            	
			$return = '';
		}else if(file_exists($dir_cache.$cache_name)) {
			list($width, $height, $type, $attr) = getimagesize($dir_interna.$image);
			$this->image_size_width = $width;
			$this->image_size_height  = $height;                              
			$return= $cache_name;
		}else if($image && file_exists($dir_interna.$image) && !file_exists($dir_cache.$cache_name)) {
			list($width, $height, $type, $attr) = getimagesize($dir_interna.$image);
			$this->image_resizer->load($dir_interna.$image);
			if($width>$max_width && $width>=$height) {
				$this->image_resizer->resizeToWidth($max_width);
			}else if($height>$max_height && $height>$width) {
				$this->image_resizer->resizeToHeight($max_height);
			}
			$this->image_resizer->save($dir_cache.$cache_name);
			$return = $cache_name;
		}else {
			$return ='';
		}           
		if(!$return) return false;
		list($width, $height, $type, $attr) = getimagesize(DIR_CACHE.$return);            
		$this->image_size_width = $width;
		$this->image_size_height  = $height;                                          
		$return = URL_CACHE.$return;            
		return $return; 
	}
}



class image_resizer {

	var $image;
	var $image_type;

	function load($filename) {

		$image_info = getimagesize($filename);
		$this->image_type = $image_info[2];
		if( $this->image_type == IMAGETYPE_JPEG ) {

			$this->image = imagecreatefromjpeg($filename);
		} elseif( $this->image_type == IMAGETYPE_GIF ) {

			$this->image = imagecreatefromgif($filename);
		} elseif( $this->image_type == IMAGETYPE_PNG ) {

			$this->image = imagecreatefrompng($filename);
		}
	}
	function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {

		if(  $this->image_type  == IMAGETYPE_JPEG ) {
			imagejpeg($this->image,$filename,$compression);
		} elseif(  $this->image_type  == IMAGETYPE_GIF ) {

			imagegif($this->image,$filename);
		} elseif(  $this->image_type  == IMAGETYPE_PNG ) {

			imagepng($this->image,$filename);
		}
		if( $permissions != null) {

			chmod($filename,$permissions);
		}

	}


	function output($image_type=IMAGETYPE_JPEG) {

		if( $image_type == IMAGETYPE_JPEG ) {
			imagejpeg($this->image);
		} elseif( $image_type == IMAGETYPE_GIF ) {

			imagegif($this->image);
		} elseif( $image_type == IMAGETYPE_PNG ) {
			imagepng($this->image);
		}
	}
	function getWidth() {

		return imagesx($this->image);
	}
	function getHeight() {

		return imagesy($this->image);
	}
	function resizeToHeight($height) {

		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width,$height);
	}

	function resizeToWidth($width) {
		$ratio = $width / $this->getWidth();
		$height = $this->getheight() * $ratio;
		$this->resize($width,$height);
	}

	function scale($scale) {
		$width = $this->getWidth() * $scale/100;
		$height = $this->getheight() * $scale/100;
		$this->resize($width,$height);
	}

	function resize($width,$height) {

		if( $this->image_type == IMAGETYPE_PNG ) {

			$new_image = imagecreatetruecolor($width, $height);

			$background = imagecolorallocate($new_image, 0, 0, 0);
			// removing the black from the placeholder
			imagecolortransparent($new_image, $background);

			// turning off alpha blending (to ensure alpha channel information
			// is preserved, rather than removed (blending with the rest of the
			// image in the form of black))
			imagealphablending($new_image, false);

			// turning on alpha channel information saving (to ensure the full range
			// of transparency is preserved)
			imagesavealpha($new_image, true);

			imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
			$this->image = $new_image;


		}else {

			$new_image = imagecreatetruecolor($width, $height);
			imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
			$this->image = $new_image;

		}
	}

}






?>