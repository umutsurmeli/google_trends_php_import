<?php
    ini_set('display_errors',true);
    $url = 'https://trends.google.com/trends/trendingsearches/daily/rss?geo=TR';
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
$channel_link = $channel->getElementsByTagName('link')
->item(0)->childNodes->item(0)->nodeValue;
$channel_desc = $channel->getElementsByTagName('description')
->item(0)->childNodes->item(0)->nodeValue;

//output elements from "<channel>"
echo("<p><a href='" . $channel_link
  . "'>" . $channel_title . "</a>");
echo("<br>");
echo($channel_desc . "</p><br/>\r\n");

//get and output "<item>" elements
$x=$doc->getElementsByTagName('item');
for ($i=0; $i<count($x); $i++) {
  $item_title=$x->item($i)->getElementsByTagName('title')
  ->item(0)->childNodes->item(0)->nodeValue;
  $item_link=$x->item($i)->getElementsByTagName('link')
  ->item(0)->childNodes->item(0)->nodeValue;
    $item_desc=$x->item($i)->hasAttribute('description');
  
  //$item_ht = $item_ht_id->item(0)->childNodes->item(0)->nodeValue;
  ## başarılı :$item_ht=$x->item($i)->childNodes->item(3)->nodeName;
  $item_ChildsLength=$x->item($i)->childNodes->length;
  $item_Childs = $x->item($i)->childNodes;
  echo "\r\n<pre>";
  foreach ($item_Childs as $cnode) {

          echo $cnode->nodeValue.'<br/><br/>';

  }
  echo '<br/><hr/><br/></pre>';
  //$item_ht = $x->item($i)->getElementsByTagName('ht')->item(0)->nodeValue;
  /*
  print('<pre><br/>');echo var_dump($item_Childs);print('</pre><br/>');
  echo ("<p><a href='" . $item_link
  . "'>" . $item_title . "</a>");
  echo ("<br>");
  echo ($item_desc . "</p>");
   
   */
}
