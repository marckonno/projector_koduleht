<?

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0,pre-check=0", false);
header("Cache-Control: max-age=0", false);
header("Pragma: no-cache");


function getweight($file) {
   $kb = 1024; 
   $mb = 1024 * $kb;
   $gb = 1024 * $mb;
   $tb = 1024 * $gb;
 
   $size = filesize($file);

   if($size < $kb) {
       return $size." B";
   }
   else if($size < $mb) {
      return round($size/$kb,2)." KB";
   }
   else if($size < $gb) {
       return round($size/$mb,2)." MB";
   }
   else if($size < $tb) {
       return round($size/$gb,2)." GB";
   }
   else {
      return round($size/$tb,2)." TB";
   }
}


function getparams($file) {
    $size = getimagesize ($file);
    return array ($size[0],$size[1],$size[2]);
}




$files = array ();

$d = dir("./");
while($failinimi=$d->read()) {
	if  ($failinimi != ".." && $failinimi != ".")
	{
	    $fileext = substr($failinimi,-strpos(strrev($failinimi),'.'));
	    if ($fileext =="jpg" || $fileext =="jpeg" || $fileext =="png" || $fileext =="gif" || $fileext =="mp4" || $fileext =="swf") {
	        array_push ($files, $failinimi);
		}
	}
}

$d->close();
rsort ($files);

/* get values */
if (!empty($_REQUEST['bg'])) {
	$bg = $_REQUEST['bg'];
}
if (!empty($_REQUEST['bord'])) {
	$bord = $_REQUEST['bord'];
}
$bg = (!$bg) ? "FFFFFF" : $bg;

$border_color = '#000';
switch ($bg) {
	case 'FFFFFF':
		$selected[1] = " selected";
 		$font = "BBBBBB";
 		break;
	case 'DDDDDD':
		$selected[2] = " selected";
 		$font = "AAAAAA";
 		break;
	case '666666':
		$selected[3] = " selected";
		$font = "999999";
		break;
	case '000000':
		$selected[4] = " selected";
		$font = "888888";
		$border_color = '#fff';
		break;
}

$border=0;

if ($bord) {
 $checked = " checked"; 
 $border=1;
 $bord1 = '<table cellpadding="1" cellspacing="0" border="0"><tr><td style="background-color: '.$border_color.'">'; 
 $bord2 = '</td></tr></table>'; 
}


?>
<HTML>
<HEAD>
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<TITLE>Creatum</TITLE>
<style type="text/css">
<!--
td { font-family: "MS Sans Serif", Arial, sans-serif; color: #<?=$font?>; font-size: 9pt;}
a { font-family: "MS Sans Serif", Arial, sans-serif; color: #<?=$font?>; font-size: 9pt; text-decoration: none; }
input { font-family: "MS Sans Serif", Arial, sans-serif; color: #000000; font-size: 9pt;}
textarea { font-family: "MS Sans Serif", Arial, sans-serif; color: #000000; font-size: 9pt;}
select { font-family: "MS Sans Serif", Arial, sans-serif; color: #000000; font-size: 9pt;}
img{-webkit-box-shadow: 0px 0px 15px 0px rgba(173,173,173,1);
-moz-box-shadow: 0px 0px 15px 0px rgba(173,173,173,1);
box-shadow: 0px 0px 15px 0px rgba(173,173,173,1);}
-->
</style>

<script language="javascript">
<!--
function show(f) {
 document.forms[0].f.value = f;
 document.forms[0].submit();
}
//-->
</script>


</HEAD>


<body bgcolor="#<?=$bg?>" link="#<?=$link?>" vlink="#<?=$link?>" alink="#<?=$link?>">

<center>
<table border="0">
<form method="get" action="<?php echo $_SERVER['PHP_SELF']?>">
<tr>
<input type="hidden" value="<?=$f?>" name="f">
<td align="right" valign="middle">Background color:</td><td valign="middle"><select name="bg" onChange="show('<?=$f?>')">
<option value="FFFFFF"<?=$selected[1]?>> White
<option value="DDDDDD"<?=$selected[2]?>> Light gray
<option value="666666"<?=$selected[3]?>> Dark gray
<option value="000000"<?=$selected[4]?>> Black
</select></td>
<td>&nbsp;</td>
<td valign="middle"><input type="checkbox" name="bord" id="bord" onClick="show('<?=$f?>')"<?=$checked?>></td><td valign="middle"><label for="bord">Show border around images</label></td>
</tr>
</table>
<?
if (!empty($f)) {
    list($iwidth, $iheight, $itype) = getparams($files[$f]);
    
    echo '
    <table border="0">
    <tr>
    <td align="center" valign="middle">
    ';


	if (!is_file($files[$f])) {
			echo "no such file";
			exit;
	} else {



		echo '<input type="button" onClick="show(\'\')" value="  Listing  ('.count($files).') "> ';
	
		if ($f > 0) {
			echo '<input type="button" onClick="show(\''.($f-1).'\')" value="  Previous  ">';
		} else {
			echo '<input type="button" disabled value="  Previous  ">';
		}


		if ($f < count($files)-1) {
			echo ' <input type="button" onClick="show(\''.($f+1).'\')" value="      Next      ">';
		} else {
			echo ' <input type="button" disabled value="      Next      ">';
		}

?>


        </td>
        </tr>
        </table>

        <!--
        <textarea name="comm" wrap="virtual" class="txt" rows="6" cols="100" style="width: 260; height: 80">'.$comm.'</textarea>
        -->


        <table width="95%" height="80%" border="0">
        <tr>
        <td align="center" valign="middle" height="90%" colspan="2">
        <table border="0">
        <tr>
        <td align="center">


<?
        if ($itype == "4" || $itype == "13") {
/*
// flash 6
echo $bord1.'
<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" id="flash'.$f.'" width="'.$iwidth.'" height="'.$iheight.'">
<PARAM name="movie" value="'.$files[$f].'" />
<PARAM name="menu" value="false" />
<PARAM name="quality" value="high" />
<PARAM name="scale" value="noscale" />
<EMBED name="flash'.$f.'" src="'.$files[$f].'" quality="high" width="'.$iwidth.'" height="'.$iheight.'" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" menu="false" scale="noscale"></EMBED>
</OBJECT>
'.$bord2;
*/
echo $bord1.'
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://active.macromedia.com/flash4/cabs/swflash.cab#version=4,0,0,0" id="flash'.$f.'" width="'.$iwidth.'" height="'.$iheight.'">
<param name="movie" value="'.$files[$f].'">
<param name="quality" value="high">
<embed name="flash'.$f.'" src="'.$files[$f].'" quality="high" width="'.$iwidth.'" height="'.$iheight.'" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">
</embed>
</object>
<br><br><a href="'.$files[$f].'">Download ('.$files[$f].') using Right-Click</a>

'.$bord2;

        } else {
echo '
<a href="javascript:show(\'\')">    
<img src="'.$files[$f].'" border="'.$border.'" style="border: '.$border.'px solid '.$border_color.'" title="'. $files[$f].', '. $iheight . ' x '. $iwidth .'px , ' .getweight($files[$f]).'"></a>
</td>
</tr>
</table>
';
}

        }
	} else {
	    
		for ($i=0; $i <= count($files)-1; $i++) {
		    list($iwidth, $iheight, $itype) = getparams($files[$i]);
		    
		    echo '
		    <p>&nbsp;</p>
		    ';
		    

		if ($itype == IMAGETYPE_SWF || $itype == IMAGETYPE_SWC) {
/*
// flash 6
echo '
<center>
<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" id="flash'.$f.'" width="'.$iwidth.'" height="'.$iheight.'">
<PARAM name="movie" value="'.$files[$f].'" />
<PARAM name="menu" value="false" />
<PARAM name="quality" value="high" />
<PARAM name="scale" value="noscale" />
<EMBED name="flash'.$f.'" src="'.$files[$f].'" quality="high" width="'.$iwidth.'" height="'.$iheight.'" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" menu="false" scale="noscale"></EMBED>
</OBJECT>
';
*/

			echo '
            <center>
            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://active.macromedia.com/flash4/cabs/swflash.cab#version=4,0,0,0" id="flash'.$i.'" width="'.$iwidth.'" height="'.$iheight.'">
            <param name="movie" value="'.$files[$i].'">
            <param name="quality" value="high">
            <embed name="flash'.$i.'" src="'.$files[$i].'" quality="high" width="'.$iwidth.'" height="'.$iheight.'" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">
            </embed>
            </object>
            ';  

		    } else {
			 echo '<img src="'.$files[$i].'" border="'.$border.'" style="border: '.$border.'px solid '.$border_color.'">';
		    }
		    echo '
		    <br><br><br>
			<a href="javascript:show(\''.$i.'\')" name="i'.$i.'" class="txt">'.$files[$i].' (' .$iwidth. ' x ' .$iheight. 'px, ' .getweight($files[$i]).')'.'</a>
			<p><a href="'.$files[$i].'">Download ('.$files[$i].') using Right-Click</a></p>
			<p>&nbsp;</p>
			';
		}
}


?>



</form>
</center>
</body>
</HTML>

