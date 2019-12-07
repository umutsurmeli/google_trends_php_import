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
echo '<pre> $x->length:'.$x->length."\r\n";
echo '<h2>'.$channel_title.'</h2><h3>'.$channel_description.'</h3>';
for ($i=0; $i<$x->length; $i++) {
    
  $item_title= $x->item($i)->getElementsByTagName('title')
  ->item(0)->childNodes->item(0)->nodeValue;
  $trends_items[$i]['item_title'] = $item_title;
  
  if($x->item($i)->hasAttribute('description'))
  {
    $item_description = $x->item($i)->getElementsByTagName('description')
  ->item(0)->childNodes->item(0)->nodeValue;
  $trends_items[$i]['item_description'] = $item_description;
  }
  else {
      $trends_items[$i]['item_description'] ='';
  }
  $item_pubDate=$x->item($i)->getElementsByTagName('pubDate')
  ->item(0)->childNodes->item(0)->nodeValue;
  $trends_items[$i]['item_pubDate'] = $item_pubDate;
  
  /* tagin adındaki ":" yüzünden çalışmaz.
    $item_traffic=$x->item($i)->getElementsByTagName('ht:approx_traffic')
  ->item(0)->childNodes->item(0)->nodeValue;
    echo '<b>'.$item_traffic.'</b>';
    */
  //$trends_items[$i]['item_title'] = $item_title;
  
  /*
  $item_title=$x->item($i)->getElementsByTagName('title')
  ->item(0)->childNodes->item(0)->nodeValue;
  $items[$i]['item_title'] = $item_title;
  
  $item_link=$x->item($i)->getElementsByTagName('link')
  ->item(0)->childNodes->item(0)->nodeValue;
  */
    //$item_desc=$x->item($i)->hasAttribute('description');

  $item_ChildsLength=$x->item($i)->childNodes->length;
  $item_Childs = $x->item($i)->childNodes;

  foreach ($item_Childs as $cnode) {
      

      if($cnode->nodeName == 'ht:approx_traffic') {
          $trends_items[$i]['item_traffic'] = $cnode->nodeValue;
      }
      if($cnode->nodeName == 'ht:picture') {
          $trends_items[$i]['item_picture'] = $cnode->nodeValue;
      }
      if($cnode->nodeName == 'ht:picture_source') {
          $trends_items[$i]['item_picture_source'] = $cnode->nodeValue;
      }
      /*
      if($cnode->nodeName == 'ht:news_item') {
          
          echo ($cnode->hasChildNodes?'evet':'hayır');
          //echo "\r\n".'<b>'.$cnode->nodeValue.'</b>';
      }
      */
      if($cnode->nodeName == 'ht:news_item_url') {
          echo $cnode->nodeValue;
          
      }
      else {
          echo 'ht:news_item_url nodeName`ler arasında yok!';
      }
      if($cnode->nodeName!='#text') {
          echo '['.$cnode->nodeName.'] '.$cnode->nodeValue.'<br/><br/>';
      }
  }
  echo '<br/><hr/><br/>';

}
