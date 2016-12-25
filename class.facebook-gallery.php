<?php
require __DIR__ . '/facebook-sdk-v5/autoload.php';

class FBGallery
{
    /**
     * FBGallery constructor.
     * @param $config array
     */
    public function __construct($config)
	{
        $this->fb = new \Facebook\Facebook([
            'app_id' => $config['app_id'],
            'app_secret' => $config['app_secret'],
            'default_graph_version' => 'v2.5'
        ]);

        $this->access_token = $this->fb->getApp()->getAccessToken();
        $this->fb->setDefaultAccessToken( $this->access_token );

        $this->page_name = $config['page_name'];
		$this->breadcrumbs = $config['breadcrumbs'];
		$this->cache = $config['cache'];
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
     * @param $album_id string
     * @param string $type
     * @return mixed|string
     */
	private function getData($album_id='',$type='')
	{
		if($type == 'photos'){
            $url = 'https://graph.facebook.com/'.$album_id.'/photos?access_token='.$this->access_token.'&fields=id,picture,images,caption';
        } else {
            $url = 'https://graph.facebook.com/'.$this->page_name.'/albums?access_token='.$this->access_token;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $return_data = curl_exec($ch);
        $json_array = json_decode($return_data,true);

        return $json_array;
	}

    private function displayAlbums()
    {
        //$this->loadCache($this->id); // loads cached file
        $gallery = '';
        $albums = $this->getData($this->page_name,$type='albums');

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

    private function displayPhotos($album_id,$title='Photos')
    {
        $this->loadCache($album_id); // loads cached file

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

    /**
     * Loops through array of breadcrubs to be displayed
     * $crumbs must be setup like array('parent title' => 'parent url','child title' => 'child array')
     *
     * @param $crumbs_array
     * @return string
     */
    private function addBreadCrumbs($crumbs_array)
    {
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


    ##---------------------------
    ## CACHE
    ##---------------------------
    private function saveCache($id,$html)
    {
        if($this->cache['permission'])
        {
            $fp = @fopen($this->cache['location'].'/'.$id.'.html', 'w');
            if (false == $fp) {

                $error_object = error_get_last();

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

    private function loadCache($id)
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
}
