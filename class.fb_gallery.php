<?php
class fb_gallery
{
	public function __construct($id,$breadcrumbs,$cache='y',$cache_loc='cache') {
		/**
		* Simply sets variables set in class contruction
		*
		* @param	$id				= Facebook page id. Could be a name or number ('Coca-Cola','photobucket')
		* @param	$breadcrumbs	= 'n' turns off breadcrumbs. Everything else leaves them on
		* @param	$cache			= 'n' turns off caching
		* @param	$cache_loc		= location of where the cahced files are saved
		*/
		$this->id = $id;
		$this->breadcrumbs = $breadcrumbs;
		$this->cache = $cache;
		$this->cache_loc = $cache_loc; // location of cached files
	}
	
	function getData($id,$type='')
	{
		/**
		* Sends each request Facebook (currently only for 'albums' and 'photos')
		*/
		if(!empty($id))
		{
			if($type == 'photos'){$type = 'photos';}
			else{$type = 'albums';}			
			
			$url = 'https://graph.facebook.com/'.$id.'/'.$type;
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
			curl_setopt($ch, CURLOPT_HEADER,0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			$return_data = curl_exec($ch);
			$json_array = json_decode($return_data,true);
			
			return $json_array;
		}
		else{return 'id was empty';}
	}
	
	function displayAlbums()
	{
		$this->loadCache($album_id); // loads cached file
		
		$json_array = $this->getData($this->id,$type='albums');
		$data_count = count($json_array['data']);
		for($x=0; $x<$data_count; $x++)
		{
			if(!empty($json_array['data'][$x]['cover_photo'])) // do not include empty albums
			{
				$gallery .= '<li>
									<a href="?id='.$json_array['data'][$x]['id'].'&title='.urlencode($json_array['data'][$x]['name']).'" title="'.$json_array['data'][$x]['name'].'" class="twipsies">
										<span class="thumbnail"><i style="background-image:url(\'http://graph.facebook.com/'.$json_array['data'][$x]['cover_photo'].'/picture?type=album\');"></i></span>
									</a>
								</li>';
			}
			
		}
		$gallery = '<ul class="media-grid">'.$gallery.'</ul>';
		
		if($this->breadcrumbs != 'n'){
			$crumbs = array('Gallery' => $_SERVER['PHP_SELF']);
			$gallery = $this->addBreadCrumbs($crumbs).$gallery;
		}
		
		$this->saveCache($album_id,$gallery); // cache gallery to static HTML file
		
		return $gallery;
	}
	
	function displayPhotos($album_id,$title='Photos')
	{
		$this->loadCache($album_id); // loads cached file
		
		$json_array = $this->getData($album_id,$type='photos');
		$data_count = count($json_array['data']);
		if($data_count > 0)
		{
			for($x=0; $x<$data_count; $x++)
			{
				$json_array['data'][$x]['name'] = '';
				$gallery .= '<li>
									<a href="'.$json_array['data'][$x]['source'].'" rel="prettyPhoto['.$album_id.']" title="'.$json_array['data'][$x]['name'].'">
										<span class="thumbnail"><i style="background-image:url(\''.$json_array['data'][$x]['picture'].'\');"></i></span>
									</a>
								</li>';
			}
			$gallery = '<ul class="media-grid">'.$gallery.'</ul>';
			
			if($this->breadcrumbs != 'n'){
				$crumbs = array('Gallery' => $_SERVER['PHP_SELF'],
									 $title => '');
				$gallery = $this->addBreadCrumbs($crumbs).$gallery;
			}
		}
		else{$gallery = 'no photos in this gallery';}
		
		
		$this->saveCache($album_id,$gallery); // cache gallery to static HTML file
		
		return $gallery;
	}
	
	function addBreadCrumbs($crumbs_array)
	{
		/**
		* Loops through array of breadcrubs to be displayed
		*
		* $crumbs must be setup like array('parent title' => 'parent url','child title' => 'child array')
		*/
		if(is_array($crumbs_array))
		{
			$divider = ' <span class="divider">/</span>';
			$count = count($crumbs_array);
			if($count <= 1){$divider = '';} // only one crumb to display so no divider
			$counter = 1;
			foreach($crumbs_array as $title => $url)
			{
				if($count == $counter){$divider = '';} // removed divider from last crumb
				$crumbs .= '<li><a href="'.$url.'">'.$title.'</a>'.$divider.'</li>';
				++$counter;
			}
			
			return '<ul class="breadcrumb">
							'.$crumbs.'
						</ul>';
		}
		// else simple return nothing
	}
	
	
	##--------
	## CACHE
	##--------
	function saveCache($id,$html)
	{
		if($this->cache != 'n')
		{
			$fp = fopen($this->cache_loc.'/'.$id.'.html', 'w');
			fwrite($fp, $html);
			fclose($fp);
		}
	}
	
	function loadCache($id)
	{
		if($this->cache != 'n')
		{
			$cache_file = $this->cache_loc.'/'.$id.'.html';
			if(file_exists($cache_file) AND filemtime($cache_file) > date("U") - 7200) // 2 hours
			{
				require($cache_file);
				exit;
			}
		}
	}
}
?>