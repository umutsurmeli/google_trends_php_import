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

  $item_ChildsLength=$x->item($i)->childNodes->length;
  $item_Childs = $x->item($i)->childNodes;
  
  
  
  foreach ($item_Childs as $cnode) {
      
      if($cnode->nodeName!='#text' && $cnode->nodeName != 'ht:news_item') {
          echo '<br/>['.$cnode->nodeName.'] '.$cnode->nodeValue.'';
      }
      else if($cnode->nodeName == 'ht:news_item') {

          echo '&lt;'.$cnode->childNodes->item(1)->nodeName.'&gt;';
          $ht_news_childs = $cnode->childNodes;
          foreach($ht_news_childs as $htchild) {
                if($htchild->nodeName!='#text') {
                    echo '<br/>*['.$htchild->nodeName.']'.$htchild->nodeValue;
                }
              
          }
           
      }

  }
  

  echo '<br/><hr/><br/>';

}
