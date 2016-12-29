<?php
require __DIR__ . '/facebook-sdk-v5/autoload.php';

class FBGallery
{
    /**
     * FBGallery constructor.
     * @param $config array
     */
    public function __construct($config) {
        $this->fb = new \Facebook\Facebook([
            'app_id' => $config['app_id'],
            'app_secret' => $config['app_secret'],
            'default_graph_version' => 'v2.8'
        ]);

        $this->access_token = $this->fb->getApp()->getAccessToken();
        $this->fb->setDefaultAccessToken( $this->access_token );

        $this->page_name = $config['page_name'];
        $this->breadcrumbs = $config['breadcrumbs'];
        $this->cache = $config['cache'];
    }

    public function display(){
        try{
            if(empty($_GET['id'])){
                return $this->displayAlbums();
            }

            return $this->displayPhotos($_GET['id'],$_GET['title']);

        } catch(Exception $e){
            return 'Unable to display gallery due to the following error: '.$e->getMessage();
        }
    }

    /**
     * Sends each request Facebook (currently only for 'albums' and 'photos')
     *
     * @param string $album_id
     * @param string $type
     * @return mixed
     * @throws Exception
     */
    private function getData($album_id='',$type=''){
        if($type == 'photos'){
            $url = 'https://graph.facebook.com/v2.8/'.$album_id.'/photos?access_token='.$this->access_token.'&limit=100&fields=id,picture,source';

        } else {
            $url = 'https://graph.facebook.com/v2.8/'.$this->page_name.'/albums?access_token='.$this->access_token.'&limit=100&fields=id,name,cover_photo,count';
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $return_data = curl_exec($ch);

        $json_array = json_decode($return_data,true);
        if(isset($json_array['error'])){
            throw new Exception($json_array['error']['message']);
        }

        return $json_array;
    }

    private function displayAlbums(){
        $cache = $this->getCache($this->page_name); // loads cached file
        if($cache) return $cache;

        $gallery = '';
        $albums = $this->getData($this->page_name,$type='albums');

        foreach($albums['data'] as $album){
            if($album['count'] > 0) {
                $gallery .= '<div class="col-lg-2 col-sm-3 col-xs-6">
                                <a href="?id='.$album['id'].'&title='.urlencode($album['name']).'" class="thumbnail" rel="tooltip" data-placement="bottom" title="'.$album['name'].' ('.$album['count'].')">
                                    <img src="http://graph.facebook.com/'.$album['cover_photo']['id'].'/picture?type=normal">
                                </a>
                            </div>';
            }
        }

        $gallery = '<ul class="thumbnails">'.$gallery.'</ul>';

        if($this->breadcrumbs){
            $crumbs = array('Gallery' => $_SERVER['PHP_SELF']);
            $gallery = $this->addBreadCrumbs($crumbs).$gallery;
        }

        $this->saveCache($this->page_name,$gallery); // saves cached HTML file

        return $gallery;
    }

    private function displayPhotos($album_id,$title='Photos'){
        $cache = $this->getCache($album_id); // loads cached file
        if($cache) return $cache;

        $photos = $this->getData($album_id,$type='photos');
        if(count($photos) == 0) return 'No photos in this gallery';

        $gallery = '';
        foreach($photos['data'] as $photo)
        {
            $gallery .= '<div class="col-lg-2 col-sm-3 col-xs-6">
                            <a href="'.$photo['source'].'" rel="prettyPhoto['.$album_id.']" title="" class="thumbnail">
                                <img src="http://graph.facebook.com/'.$photo['id'].'/picture?type=normal">
                            </a>
                        </div>';
        }

        if($this->breadcrumbs){
            $crumbs = array('Gallery' => $_SERVER['PHP_SELF'],  $title => '');
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
    private function addBreadCrumbs($crumbs_array){
        $crumbs = '';
        if(is_array($crumbs_array)){
            foreach($crumbs_array as $title => $url){
                $crumbs .= '<li><a href="'.$url.'">'.stripslashes($title).'</a></li>';
            }

            return '<ol class="breadcrumb">'.$crumbs.'</ol>';
        }
    }


    ##---------------------------
    ## CACHE
    ##---------------------------
    private function saveCache($id,$html){
        if($this->cache && is_writable($this->cache['location']))
        {
            $fp = @fopen($this->cache['location'].'/'.$id.'.html', 'w');
            if (false == $fp) {
                $error = error_get_last();
                throw new Exception('Unable to save cache due to '.$error['message']);
            } else {
                fwrite($fp, $html);
                fclose($fp);
            }

        }
    }

    private function getCache($id){
        if($this->cache) {
            $cache_file = $this->cache['location'].'/'.$id.'.html';
            if(file_exists($cache_file) AND filemtime($cache_file) > (date("U") - $this->cache['time'])) {
                return file_get_contents($cache_file);

            }
        }

        return false;
    }
}
