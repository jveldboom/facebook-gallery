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
			<a class="brand" href="<?php echo $_SERVER['PHP_SELF']; ?>">PHP Facebook Gallery</a>
			<ul class="nav">
				<li class="active"><a href="http://jveldboom.github.com/fb_gallery/">github</a></li>
				<li><a href="#about">issues</a></li>
				<li><a href="#about">about</a></li>
			</ul>
		</div>
	</div>
</div>

<div class="container">	
	<?php
	require('class.fb_gallery.php');
	
	$gallery = new fb_gallery('pepsi','y');
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