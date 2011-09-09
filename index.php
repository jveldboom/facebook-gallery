<?php
class fb_gallery
{
	## GLOBAL VARS
	$breadcrumbs = 'y'; // y or n = display breadcrumbs
		
	function getAlbums($page_id)
	{
		$url = 'https://graph.facebook.com/'.$page_id.'/albums';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch, CURLOPT_HEADER,0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$return_data = curl_exec($ch);
		$json_array = json_decode($return_data,true);
		$data_count = count($json_array[data]);
		for($x=0; $x<$data_count; $x++)
		{
			if(!empty($json_array[data][$x][cover_photo])) // do not include empty albums
			{
				$gallery .= '<div class="photo_box">
									<a href="?id='.$json_array[data][$x][id].'&title='.urlencode($json_array[data][$x][name]).'" class="" title="'.$json_array[data][$x][name].'">
										<img src="http://graph.facebook.com/'.$json_array[data][$x][cover_photo].'/picture?type=album" class=""/>
										<br />
										'.$json_array[data][$x][name].'
									</a>
								</div>';
			}
		}
		
		if($breadcrumbs != 'n'){
			$gallery = '<div class="breadcrumbs">
							<ul>
								<li><a href="/">Home</a></li>
								<li>Gallery</li>
							</ul>
							</div>
							'.$gallery;
		}
		
		
		return $gallery;
	}
	
	function getPhotos($album_id,$title='Photos')
	{
		$title = urldecode($title);
		$title = preg_replace("/[^A-Za-z0-9 -]/","", $title);
				
		$url = 'https://graph.facebook.com/'.$album_id.'/photos';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch, CURLOPT_HEADER,0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$return_data = curl_exec($ch);
		
		$json_array = json_decode($return_data,true);
		$data_count = count($json_array[data]);
		for($x=0; $x<$data_count; $x++)
		{
			$gallery .= '<div class="photo_box">
								<a href="'.$json_array[data][$x][source].'" class="" rel="prettyPhoto['.$album_id.']" title="'.$json_array[data][$x][name].'">
									<img src="'.$json_array[data][$x][picture].'" alt="'.$title.'" class=""/>
								</a>
							</div>';
		}
		
		if($breadcrumbs != 'n'){
			$gallery = '<div class="breadcrumbs">
							<ul>
								<li><a href="/">Home</a></li>
								<li><a href="'.$_SERVER[PHP_SELF].'">Gallery</a></li>
								<li>'.$title.'</li>
							</ul>
							</div>
							'.$gallery;
		}
		
		return $gallery;
	}
}
?>
<html>
<head>
	<title>FB Gallery</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="http://www.no-margin-for-errors.com/wp-content/themes/NMFE/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="http://www.no-margin-for-errors.com/wp-content/themes/NMFE/css/prettyPhoto.css"/>
	<link rel="stylesheet" href="default.css"/>
	<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
		$("a[rel^='prettyPhoto']").prettyPhoto({theme: 'dark_rounded',social_tools: ''});
	});
	</script>
	
	<style>
	body {
		font: 12px/20px arial, serif;
		background: #333;
	}
	
	.footer {
		clear: left;
		background: #000;
		color: #dcdcdc;
		padding: 10px;
	}
	</style>
</head>
<body>

	<?php
	$gallery = new fb_gallery;
	if(empty($_GET[id])){
		echo $gallery->getAlbums('152449594809170');}
	else{
		echo $gallery->getPhotos($_GET[id],$_GET[title]);}
	?>
	
	<div class="footer">
		Please visit our github page if you have any questions or would like to help improve this.
	</div>
</body>
</html>