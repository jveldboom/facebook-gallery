<!DOCTYPE html>
<html lang="en">
<head>
	<title>Facebook Gallery</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/bootstrap-twipsy.js" type="text/javascript" charset="utf-8"></script>
	<script src="http://twitter.github.com/bootstrap/1.3.0/bootstrap-dropdown.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/prettyPhoto.css"/>
	<link rel="stylesheet" href="css/stlyes.css"/>
	<script type="text/javascript" charset="utf-8">
	$(function () {
		$("a[rel^='prettyPhoto']").prettyPhoto({theme: 'dark_rounded',social_tools: '',deeplinking: false});
		
		 
		$("a[rel=twipsy]").twipsy({
			live: true,
			placement: 'below'
		});
		
		$('#topbar').dropdown();
	
	});
	</script>
</head>
<body>
<div class="topbar">
	<div class="fill">
		<div class="container">
			<a class="brand" href="<?php echo $_SERVER['PHP_SELF']; ?>">Facebook Gallery</a>
			<ul class="nav">
				<li class="active"><a href="https://github.com/jveldboom/facebook-gallery">github</a></li>
				<li><a href="https://github.com/jveldboom/facebook-gallery/issues">issues</a></li>
				<li class="dropdown" data-dropdown="dropdown">
    				<a href="#" class="dropdown-toggle">galleries</a>
    				<ul class="dropdown-menu">
      				<li><a href="?fid=tacobell">Taco Bell</a></li>
      				<li><a href="?fid=XGames">X Games</a></li>
      				<li><a href="?fid=googlechrome">Google Chrome</a></li>
    				</ul>
  				</li>
			</ul>
		</div>
	</div>
</div>
<div class="container">	
	<?php
	if(empty($_GET[fid])){$_GET[fid] = 'tacobell';} // force if empty
	require('class.facebook-gallery.php');
	$cache = array('permission' => 'y',
						'location' => 'cache',
						'time' => 7200);
	$gallery = new FBGallery($_GET[fid],'y',$cache);
	if(empty($_GET[id])){
		echo $gallery->displayAlbums();
	}
	else{
		echo $gallery->displayPhotos($_GET[id],$_GET[title]);
	}
	?>
</div>
</body>
</html>