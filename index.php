<!DOCTYPE html>
<html lang="en">
<head>
	<title>Facebook Gallery</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
	<!--<script src="js/bootstrap-twipsy.js" type="text/javascript" charset="utf-8"></script>-->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/prettyPhoto.css"/>
	<link rel="stylesheet" href="css/stlyes.css"/>
	<script type="text/javascript" charset="utf-8">
	$(function () {
		$("a[rel^='prettyPhoto']").prettyPhoto({theme: 'dark_rounded',social_tools: ''});
		
		/* working on this...
		$("a[id='twipsy']").twipsy({
			live: true,
			placement: 'below'
		});
		*/
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
			</ul>
		</div>
	</div>
</div>

<div class="container">	
	<?php
	require('class.facebook-gallery.php');
	
	$cache = array('permission' => 'y',
						'location' => 'cache',
						'time' => 7200);
	$gallery = new FBGallery('pepsi','y',$cache);
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