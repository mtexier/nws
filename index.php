<?
/*
Index : View feeds
Time-stamp: <index.php - Wed 15-Feb-2012 17:14:23>

This script is part of NWS

NWS is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free
Software Foundation, either version 3 of the License, or (at your
option) any later version.

This program is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/
if (isset($_GET['code'])) { die(highlight_file(__FILE__, 1)); }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>nws</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="shortcut icon" type="image/x-icon" href="nws/favicon.png" />
    <link href="nws/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <style type="text/css" media="screen">@import "nws/nws.css";</style>
  </head>
<body>
    <script src="nws/jquery.min.js"></script>
    <script src="nws/jquery-ui.min.js"></script>

<script>
    $(document).ready(function() {
    	$.ajaxSetup ({
    	  cache: false
    	      });

    	var ajax_load = '<img src="nws/loading.gif" class="loading" alt="loading..." />';
    	var loadUrl = 'nws/nws-reload-feed.php';

	$('.reload').click(function(){
	    var DivToReload = $(this).parent()
	      var myUrl = DivToReload.attr('title')
	      DivToReload.children('div.innerContainer')
	      .html(ajax_load)
	      .load(loadUrl, "z="+myUrl);
    	  });

	$( "#tabs" ).tabs().find( ".ui-tabs-nav" ).sortable({ axis: "x" });
	// $( ".feed" ).tooltip();
	$('.reload').trigger('click');

      });
</script>
<div id="tabs">
<?php
  $conf = "nws/feeds.xml";
$urls = simplexml_load_file($conf);

$z = 0;

/* limite d'items par feed */
if (isset($_GET['limit'])) $feedsByTab = $_GET['limit'];
$feedsByTab="8";

function parse($u) {
  $limit="8";
  $feedRss=simplexml_load_file($u);
  $chars = array(" ", ".", ":");
  $i=0;

  if($feedRss)
    {
      $items = $feedRss->channel->item;
      $idiv = str_replace($chars, "", $feedRss->channel->title);
      echo '
<div class="outerContainer" style="" title ="'.$u.'">
<span class="reload" title="Reload '.htmlspecialchars($feedRss->channel->title).'">&phi;</span>
<div class="innerContainer"></div>
</div>
';
    }
}

foreach ($urls->url as $url) {
  $myUrls[] = $url;
    foreach($url->attributes() as $attr => $val) {
      if ($attr == 'tab') {
	$myTabs[] = array('tab'=> (string) $val, 'url'=> (string) $url);
      }
    }
}

foreach($myTabs as $aRow){
    $tabGroups[$aRow['tab']][] = $aRow['url'];
}

echo '
<ul>
';

foreach (array_keys($tabGroups) as $tabName) {
    echo '
<li><a title="'.$tabName.', Drag to re-order" href="#tab-'.$tabName.'"><span>'.$tabName.'</span></a></li>';
}

echo '
</ul>
';


foreach (array_keys($tabGroups) as $tabName) {
  echo '<div id="tab-'.$tabName.'">';
  foreach ($tabGroups[$tabName] as $tabUrl) {
    parse($tabUrl);
  }
    echo '</div>';
}

?>

</div>
<a href="mgmt.php">mgmt</a>
</body>
</html>
