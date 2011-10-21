<html>
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
				<li class="active"><a href="http://jveldboom.github.com/facebook-gallery/">github</a></li>
				<li><a href="https://github.com/jveldboom/facebook-gallery/issues">issues</a></li>
				<li><a href="#about">about</a></li>
			</ul>
		</div>
	</div>
</div>

<div class="container">	
	<?php
	require('class.facebook-gallery.php');
	
	$gallery = new FBGallery('pepsi','y');
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