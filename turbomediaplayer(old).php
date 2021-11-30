<?php

// -----------------------------------------------------------------------------------
// Turbo Media Player
// By Tsuka - tsuka@catsuka.com
// Last updated: 2012-07-07

$turbomedia = $_REQUEST["id"];
$turbomobile = $_REQUEST["mobile"];
$error = "";
if ( file_exists($turbomedia.".xml") )
{
  $xml = simplexml_load_file($turbomedia.".xml");
  $turbodirectory = $xml->directory;
  $turbobackgroundcolor = $xml->backgroundcolor;
  $turbowidth = utf8_decode($xml->width);
  $turboheight = utf8_decode($xml->height);
  $turboarrows = $xml->arrows;
  if ( $turbobackgroundcolor == "" ) { $turbobackgroundcolor = "#FFFFFF"; }
  $turboformat = "";
  if ( is_dir($turbodirectory) )
  {
    if ( file_exists($turbodirectory."/1.jpg") ) { $turboformat = "jpg"; }
    if ( file_exists($turbodirectory."/1.png") ) { $turboformat = "png"; }
    if ( file_exists($turbodirectory."/1.gif") ) { $turboformat = "gif"; }
    if ( $turboformat == "" ) { $error .= "Error : no images in directory.<br>"; }
	elseif ( $turbowidth == "" && $turboheight == "" )
	{
      list($width,$height) = getimagesize($turbodirectory."/1.".$turboformat);
	  $turbowidth = $width;
	  $turboheight = $height;
	}
	else
	{
	  if (!ctype_digit($turbowidth)) { $error .= "Error : width is not an integer.<br>"; }
	  if (!ctype_digit($turboheight)) { $error .= "Error : height is not an integer.<br>"; }
	}
  }
  else { $error .= "Error : directory does not exists.<br>"; }
}
else
{
  $error .= "Error : no XML file.<br>";
}

if ( $error != "" ) { echo "<html>\n<head>\n<title></title>\n</head>\n<body>\n<center><br><br><b>".$error."</b></center>\n"; }

else {

if ( $turbomobile == "1" ) { $turboheight = floor($turboheight*(400/$turbowidth)); $turbowidth = 400; }

?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
if ( $turbomobile == "1" ) {
?>
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<meta name="viewport" content="height=device-height,width=400" />
<?php
}
?>

<script language="javascript">

function previous()
{
  var i = 2;
  while ( document.getElementById('div' + i).style.display == 'none' || document.getElementById('div' + i).style.display == 'inline' )
  {
    if ( document.getElementById('div' + i).style.display == 'inline' )
    { 
      document.getElementById('div' + i).style.display = 'none';
      document.getElementById('div' + (i-1)).style.display = 'inline';
      break;
    }
    i++;
  }
}

function next()
{
  var i = 1;
  while ( document.getElementById('div' + (i+1)).style.display == 'none' || document.getElementById('div' + (i+1)).style.display == 'inline' )
  {
    if ( document.getElementById('div' + i).style.display == 'inline' )
    { 
      document.getElementById('div' + i).style.display = 'none';
      document.getElementById('div' + (i+1)).style.display = 'inline';
      break;
    }
    i++;
  }
}

function first()
{
  document.getElementById('div1').style.display = 'inline';
  var i = 2;
  while ( document.getElementById('div' + i).style.display == 'none' || document.getElementById('div' + i).style.display == 'inline' )
  {
    document.getElementById('div' + i).style.display = 'none';
    i++;
  }
}

document.onkeydown = function(evt) {
    evt = evt || window.event;
    switch (evt.keyCode) {
        case 39:
            previous();
            break;
        case 37:
            next();
            break;
        case 38:
            first();
            break;
    }
};

</script>

<style type="text/css" media="screen">
body {
	margin:0;
	padding:0;
}
.container {
	position:absolute;
	width:<?php echo $turbowidth; ?>px;
	height:<?php echo $turboheight; ?>px;
	top:50%;
	left:50%;
	margin-top:-<?php echo floor($turboheight/2); ?>px;
	margin-left:-<?php echo floor($turbowidth/2); ?>px;
	text-align: center;
}
.containermobile {
	width:<?php echo $turbowidth; ?>px;
	height:<?php echo $turboheight; ?>px;
}
#previous, #next {
	width: 49%;
	height: 100%;
	background: transparent url(blank.gif) no-repeat; /* Trick IE into showing hover */
	display: block;
}
#previous { right: 0; float: right;}
#next { left: 0; float: left;}
<?php if ( $turboarrows == "1" ) { ?>
#previous:hover, #previous:visited:hover { background: url(previous.png) right 50% no-repeat; }
#next:hover, #next:visited:hover { background: url(next.png) left 50% no-repeat; }
<?php } ?>
.frame {
	position:absolute;
	top:0px;
	left:0px;
	z-index:-1;
}
</style>

</head>
<body style="background-color:<?php echo $turbobackgroundcolor; ?>;">
<div class="container<?php if ( $turbomobile == "1" ) { echo "mobile"; } ?>">
<?php

$mode = "inline";
$i = 1;
while ( file_exists($turbodirectory."/".$i.".".$turboformat) )
{
  echo "<div id=\"div".$i."\" style=\"display: ".$mode.";\">";
  echo "<img src=\"".$turbodirectory."/".$i.".".$turboformat."\" class=\"frame\" width=\"".$turbowidth."\" height=\"".$turboheight."\">";
  if ( $i>1 ) { echo "<a href=\"javascript:previous()\" id=\"previous\"></a>"; }
  if ( file_exists($turbodirectory."/".($i+1).".".$turboformat) ) { echo "<a href=\"javascript:next()\" id=\"next\"></a>"; }
  echo "<div class=\"clear\"></div>";
  echo "</div>\n";
  $mode = "none";
  $i++;
}

?>
</div>
<?php

}

?>
</body>
</html>