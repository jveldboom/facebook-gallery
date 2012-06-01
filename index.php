<!DOCTYPE html>
<html lang="en">
<head>
	<title>Facebook Gallery</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
	<script src="bootstrap/js/bootstrap-dropdown.js"></script>
	<script src="bootstrap/js/bootstrap-tooltip.js"></script>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap-responsive.min.css">
	<link rel="stylesheet" href="css/prettyPhoto.css"/>
	<link rel="stylesheet" href="css/styles.css"/>
	<script type="text/javascript" charset="utf-8">
	$(function () {
		$("a[rel^='prettyPhoto']").prettyPhoto({theme: 'dark_rounded',social_tools: '',deeplinking: false});
		$("[rel=tooltip]").tooltip();
	});
	</script>
</head>
<body>

<!-- TOP NAV -->
<div class="navbar">
  <div class="navbar-inner">
    <div class="container">
      <a class="brand" href="<?php echo $_SERVER['PHP_SELF']; ?>">Facebook Gallery</a>
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <div class="nav-collapse">
	      <ul class="nav">
	      	<li><a href="https://github.com/jveldboom/facebook-gallery">github</a></li>
			<li><a href="https://github.com/jveldboom/facebook-gallery/issues">issues</a></li>
	      	<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">example galleries <b class="caret"></b></a>
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
</div>

<!-- GALLERY -->
<div class="container-fluid">
	<?php
	if(empty($_GET[fid])){$_GET[fid] = 'tacobell';} // force if empty for demo
	
	require('class.facebook-gallery.php');
	$cache = array('permission' => 'y',
					'location' => 'cache', // ensure this directory has permission to read and write
					'time' => 7200);
	$gallery = new FBGallery($_GET[fid],'y',$cache);
	?>
</div>

</body>
</html>