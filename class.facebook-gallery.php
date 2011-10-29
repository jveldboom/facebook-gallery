<?php
class FBGallery
{
	public function __construct($id,$breadcrumbs,$cache=array())
	{
		/**
		* Simply sets variables set in class contruction
		*
		* @string $id = Facebook page id. Could be a name or number ('Coca-Cola','photobucket')
		* @string $breadcrumbs	= 'n' turns off breadcrumbs. Everything else leaves them on
		* @array  $cache = array(
		*                 'permission' => 'y', // anything other than 'y' will turn caching off
		*                 'location'   => 'cache', // location to store the cached files
		*                 'time'       => 7200 // seconds inbetween caches (7200 seconds = 2 hours)
		*                  ) 
		*/
		
		$this->id = $this->getPageId($id); // if you're certain you'll always know the correct page id, just comment this line an uncomment the line below
		//$this->id = $id;
		$this->breadcrumbs = $breadcrumbs;
		$this->cache = $cache;
	}
	
	function getData($id,$type='')
	{
		/**
		* Sends each request Facebook (currently only for 'albums' and 'photos')
		*/
		if(!empty($id))
		{
			if($type == 'photos'){$query = "SELECT src,src_big,caption FROM photo WHERE aid = '$id'";}
			else{$query = "SELECT aid,object_id,name,size,type FROM album WHERE owner = '$id' ORDER BY modified DESC";}
			$url = 'https://graph.facebook.com/fql?q='.rawurlencode($query);
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
		$this->loadCache($this->id); // loads cached file
		
		$json_array = $this->getData($this->id,$type='albums');
		$data_count = count($json_array['data']);
		for($x=0; $x<$data_count; $x++)
		{
			if(!empty($json_array['data'][$x]['object_id'])) // do not include empty albums
			{
				$gallery .= '<li>
									<a href="?id='.$json_array['data'][$x]['aid'].'&title='.urlencode($json_array['data'][$x]['name']).'" title="'.$json_array['data'][$x]['name'].'" class="twipsies">
										<span class="thumbnail"><i style="background-image:url(\'http://graph.facebook.com/'.$json_array['data'][$x]['object_id'].'/picture?type=album\');"></i></span>
									</a>
								</li>';
			}
			
		}
		$gallery = '<ul class="media-grid">'.$gallery.'</ul>';
		
		if($this->breadcrumbs != 'n'){
			$crumbs = array('Gallery' => $_SERVER['PHP_SELF']);
			$gallery = $this->addBreadCrumbs($crumbs).$gallery;
		}
		
		$this->saveCache($this->id,$gallery); // saves cached HTML file
		
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
				$json_array['data'][$x]['caption'] = '';
				$gallery .= '<li>
									<a href="'.$json_array['data'][$x]['src_big'].'" rel="prettyPhoto['.$album_id.']" title="'.$json_array['data'][$x]['caption'].'">
										<span class="thumbnail"><i style="background-image:url(\''.$json_array['data'][$x]['src'].'\');"></i></span>
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
		
		
		$this->saveCache($album_id,$gallery); // saves cached HTML file
		
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
				$crumbs .= '<li><a href="'.$url.'">'.stripslashes($title).'</a>'.$divider.'</li>';
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
		if($this->cache['permission'] != 'n')
		{
			$fp = fopen($this->cache['location'].'/'.$id.'.html', 'w');
			fwrite($fp, $html);
			fclose($fp);
		}
	}
	
	function loadCache($id)
	{
		if($this->cache['permission'] != 'n')
		{
			$cache_file = $this->cache['location'].'/'.$id.'.html';
			if(file_exists($cache_file) AND filemtime($cache_file) > (date("U") - $this->cache['time']))
			{
				require($cache_file);
				exit;
			}
		}
	}
	
	function getPageId($string)
	{
		/**
		* Checks to see if page id is vaild
		*/
		if(is_numeric($string)){$query_where = 'page_id';}
		else{$query_where = 'username';}
		$query = "SELECT page_id FROM page WHERE $query_where = '$string'";
		$url = 'https://graph.facebook.com/fql?q='.rawurlencode($query);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch, CURLOPT_HEADER,0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$return_data = curl_exec($ch);
		$json_array = json_decode($return_data,true);
		
		if(isset($json_array['data'][0]['page_id'])){return $json_array['data'][0]['page_id'];}
		else{die('invalid page id or name');}
	}
}
?>