<?php
require_once __DIR__ . '/facebook-sdk-v5/autoload.php';
class FBGallery
{
    /**
     * @param $pageId Facebook page name. ('Coca-Cola','photobucket')
     * @param $breadcrumbs 'n' turns off breadcrumbs. Everything else leaves them on
     * @param array $cache
     */
    public function __construct($pageId,$breadcrumbs,$cache=array())
	{
        $this->fb = new \Facebook\Facebook([
            'app_id' => 'xxxxx',
            'app_secret' => 'xxxxxx',
            'default_graph_version' => 'v2.5'
        ]);

        $this->access_token = $this->fb->getApp()->getAccessToken();
        $this->fb->setDefaultAccessToken( $this->access_token );

        $this->pageId = $pageId;
        $this->id = $pageId;
		$this->breadcrumbs = $breadcrumbs;
		$this->cache = $cache;
	}

	public function display(){

        if(empty($_GET['id'])){
            return $this->displayAlbums();
        }
        else{
            return $this->displayPhotos($_GET['id'],$_GET['title']);
        }
    }

    /**
     * Sends each request Facebook (currently only for 'albums' and 'photos')
     *
     * @param string $type
     * @return mixed|string
     */
	private function getData($album_id='',$type='')
	{
		if($type == 'photos'){
            $url = 'https://graph.facebook.com/'.$album_id.'/photos?access_token='.$this->access_token.'&fields=id,picture,images,caption';
        } else {
            $url = 'https://graph.facebook.com/'.$this->pageId.'/albums?access_token='.$this->access_token;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $return_data = curl_exec($ch);
        $json_array = json_decode($return_data,true);

        return $json_array;
	}

    function displayAlbums()
    {
        //$this->loadCache($this->id); // loads cached file
        $gallery = '';
        $albums = $this->getData($this->id,$type='albums');

        foreach($albums['data'] as $album)
        {
            if($album['count'] > 0) // do not include empty albums
            {
                $gallery .= '<li class="span2">
    							<a href="?id='.$album['id'].'&title='.urlencode($album['name']).'" class="thumbnail" rel="tooltip" data-placement="bottom" title="'.$album['name'].' ('.$album['count'].')">
    							<img src="http://graph.facebook.com/'.$album['cover_photo'].'/picture?type=album">
    							</a>
  							</li>';
            }
        }

        $gallery = '<ul class="thumbnails">'.$gallery.'</ul>';

        if($this->breadcrumbs != 'n'){
            $crumbs = array('Gallery' => $_SERVER['PHP_SELF']);
            $gallery = $this->addBreadCrumbs($crumbs).$gallery;
        }

        $this->saveCache($this->id,$gallery); // saves cached HTML file

        return $gallery;
    }

    function displayPhotos($album_id,$title='Photos')
    {
        //$this->loadCache($album_id); // loads cached file

        $photos = $this->getData($album_id,$type='photos');
        if(count($photos) == 0) return 'No photos in this gallery';

        $gallery = '';
        foreach($photos['data'] as $photo)
        {
            $gallery .= '<li>
                            <a href="'.$photo['picture'].'" rel="prettyPhoto['.$album_id.']" title="" class="thumbnail">
                            <img src="'.$photo['picture'].'">
                            </a>
                        </li>';
        }

        $gallery = '<ul class="thumbnails">'.$gallery.'</ul>';

        if($this->breadcrumbs != 'n'){
            $crumbs = array('Gallery' => $_SERVER['PHP_SELF'],
                $title => '');
            $gallery = $this->addBreadCrumbs($crumbs).$gallery;
        }




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
        $crumbs = '';
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
            $fp = @fopen($this->cache['location'].'/'.$id.'.html', 'w');
            if (false == $fp) {

                $error_object = error_get_last();

                //expected error_object contents
                //                    Array
                //                    (
                //                        [type] => 8
                //                        [message] => Undefined variable: a
                //                        [file] => C:\WWW\index.php
                //                        [line] => 2
                //                        )

                //Warning: fopen(cache/321662419491.html): failed to open stream: Permission denied in /_/facebook/album_display_stuff/facebook-gallery/class.facebook-gallery.php on line 150
                $message  = 'message:' . $error_object['message'] . ' file:' . $error_object['file'] . ' line:' . $error_object['line'];
                $message_type  = $error_object['type'];
                error_log($message);

                unset($message);
                unset($message_type);
            } else {
                fwrite($fp, $html);
                fclose($fp);
            }

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
         * Checks to see if page id is valid
         */
        if(is_numeric($string)){$query_where = 'page_id';}
        else{$query_where = 'username';}
        $query = "SELECT page_id FROM page WHERE $query_where = '$string'";
        $url = 'https://graph.facebook.com/fql?q='.rawurlencode($query).'&format=json-strings';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $return_data = curl_exec($ch);
        $json_array = json_decode($return_data,true);

        if(isset($json_array['data'][0]['page_id'])){return $json_array['data'][0]['page_id'];}
        else{die('invalid page id or name');}
    }
}
?>