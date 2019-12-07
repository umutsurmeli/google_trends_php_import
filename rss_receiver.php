<?php
    ini_set('display_errors',true);
    $url = 'https://trends.google.com/trends/trendingsearches/daily/rss?geo=TR&rast='.rand(100,1000);
    //$url = 'https://news.google.com/rss?topic=h&hl=tr&gl=TR&ceid=TR:tr'; // haberler
    $doc = new DOMDocument();
    $doc->load($url);
if(isset($_REQUEST['mode'])&&$_REQUEST['mode']=='xml') {
    header('Content-type: text/xml');

    echo $doc->saveXML();
    exit();
}

$channel=$doc->getElementsByTagName('channel')->item(0);
$channel_title = $channel->getElementsByTagName('title')
->item(0)->childNodes->item(0)->nodeValue;
/* $channel_link = $channel->getElementsByTagName('link')
->item(0)->childNodes->item(0)->nodeValue;
 * 
 */
$channel_description = $channel->getElementsByTagName('description')
->item(0)->childNodes->item(0)->nodeValue;


//get and output "<item>" elements
$trends_items = array();
$x=$channel->getElementsByTagName('item');
//echo '<pre> count($x):'.count($x)."\r\n";
echo '<pre> count($x):'.$x->length."\r\n";
for ($i=0; $i<$x->length; $i++) {
    
  $item_title=$x->item($i)->getElementsByTagName('title')
  ->item(0)->childNodes->item(0)->nodeValue;
  $items[$i]['item_title'] = $item_title;
  
  /*
  $item_title=$x->item($i)->getElementsByTagName('title')
  ->item(0)->childNodes->item(0)->nodeValue;
  $items[$i]['item_title'] = $item_title;
  */
  $item_link=$x->item($i)->getElementsByTagName('link')
  ->item(0)->childNodes->item(0)->nodeValue;
  
    //$item_desc=$x->item($i)->hasAttribute('description');

  $item_ChildsLength=$x->item($i)->childNodes->length;
  $item_Childs = $x->item($i)->childNodes;

  foreach ($item_Childs as $cnode) {

          echo '['.$cnode->nodeName.'] '.$cnode->nodeValue.'<br/><br/>';

  }
  echo '<br/><hr/><br/>';

}
