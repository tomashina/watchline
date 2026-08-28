<?php
class ModelToolWebpImage extends Model {
  
	public function convert($filename) {
		if (!is_file(DIR_IMAGE . $filename) || substr(str_replace('\\', '/', realpath(DIR_IMAGE . $filename)), 0, strlen(DIR_IMAGE)) != str_replace('\\', '/', DIR_IMAGE)) {
			return;
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);
    
    if ($extension == 'webp') {
      return $filename;
    }
    
    $extension = 'webp';
    $webpQuality = $this->config->get('webp_image_quality') ? $this->config->get('webp_image_quality') : 90;
		$image_old = $filename;
		$image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '.' . $extension;

		if (!is_file(DIR_IMAGE . $image_new) || (filemtime(DIR_IMAGE . $image_old) > filemtime(DIR_IMAGE . $image_new))) {
			list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);
				 
			if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) { 
				return DIR_IMAGE . $image_old;
			}
			
			$path = '';

			$directories = explode('/', dirname($image_new));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}

				$image = new Image(DIR_IMAGE . $image_old);
				//$image->resize($width, $height);
				$image->save(DIR_IMAGE . $image_new, $webpQuality);
		}
		
		$image_new = str_replace(' ', '%20', $image_new);
		
		if ($this->request->server['HTTPS']) {
			return $this->config->get('config_ssl') . 'image/' . $image_new;
		} else {
			return $this->config->get('config_url') . 'image/' . $image_new;
		}
	}
  
  public function convertInHtml($html) {
    $dom = new DOMDocument();
    //@$dom->loadHTML($html);
    @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html); //load in utf-8
    $images = $dom->getElementsByTagName('img');
    
    $changed = false;
    
    foreach ($images as $image) {
      $convert = true;
      
      $imgSrc = $image->getAttribute('src');
      
      $imgType = pathinfo($imgSrc, PATHINFO_EXTENSION);
      
      if (!in_array(strtolower($imgType), array('png', 'jpg', 'jpeg', 'gif'))) {
        continue;
      }
      
      $imgUrl = str_replace(array('http://www.', 'https://www.', 'http://', 'https://'), '', HTTP_SERVER . 'image/');
      $imgNewSrc = str_replace(array('http://www.', 'https://www.', 'http://', 'https://'), '', $imgSrc);

      if (strpos($imgNewSrc, $imgUrl) !== false) {
        $imgSrc = str_replace($imgUrl, '', $imgNewSrc);
      /*
      if (strpos($imgSrc, HTTP_SERVER . 'image/') !== false) {
        $imgSrc = str_replace(HTTP_SERVER . 'image/', '', $imgSrc);
      } else if (strpos($imgSrc, HTTPS_SERVER . 'image/') !== false) {
        $imgSrc = str_replace(HTTPS_SERVER . 'image/', '', $imgSrc);
      */
      } else if (substr($imgSrc, 0, 7) == '/image/') {
        $imgSrc = substr($imgSrc, 7);
      } else {
        $convert = false;
      }
      
      if ($convert) {
      $image->setAttribute('src', $this->convert($imgSrc)); 
      }
      
      $changed = true;
    }
    
    if (!$changed) {
      return $html;
    }
    
    return $dom->saveHTML();
    //return utf8_decode($dom->saveHTML($dom->documentElement)); // save in utf-8, to use if load method is not working
  }
}
