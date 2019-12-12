<?php
    ini_set('display_errors',true);
    include('conf.php');
    include('class_trends.php');
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
echo '<pre> Okunan trend sayısı:'.$x->length."\r\n";



for ($i=0; $i<$x->length; $i++) {
    
  $item_title= $x->item($i)->getElementsByTagName('title')
  ->item(0)->childNodes->item(0)->nodeValue;
  //$trends_items[$i]['item_title'] = $item_title;
  
  if($x->item($i)->hasAttribute('description'))
  {
    $item_description = $x->item($i)->getElementsByTagName('description')
  ->item(0)->childNodes->item(0)->nodeValue;
  //$trends_items[$i]['item_description'] = $item_description;
  }
  else {
      //$trends_items[$i]['item_description'] ='';
  }
  $item_pubDate=$x->item($i)->getElementsByTagName('pubDate')
  ->item(0)->childNodes->item(0)->nodeValue;
  //$trends_items[$i]['item_pubDate'] = $item_pubDate;

  $item_ChildsLength=$x->item($i)->childNodes->length;
  $item_Childs = $x->item($i)->childNodes;
  
  
  
  $htnews_item_index=0;
  foreach ($item_Childs as $cnode) {
      
      if($cnode->nodeName!='#text') {
          //echo '<br/>['.$cnode->nodeName.'] '.$cnode->nodeValue.'';
          switch ($cnode->nodeName) {
              //$trends_items[$i]['item_pubDate'] = $item_pubDate;
            case 'title':
            $trends_items[$i]['title'] = $cnode->nodeValue;
            break;
            case 'ht:approx_traffic':
            $trends_items[$i]['ht:approx_traffic'] = $cnode->nodeValue;
            break;
            case 'description':
            $trends_items[$i]['description'] = $cnode->nodeValue;
            break;
            case 'pubDate':
            $trends_items[$i]['pubDate'] = $cnode->nodeValue;
            break;
            case 'ht:picture':
            $trends_items[$i]['ht:picture'] = $cnode->nodeValue;
            break;
            case 'ht:picture_source':
            $trends_items[$i]['ht:picture_source'] = $cnode->nodeValue;
            break;
            case 'ht:news_item':
                    
                    
                    $ht_news_childs = $cnode->childNodes;
                    foreach($ht_news_childs as $htchild) {
                          if($htchild->nodeName!='#text') {
                              $trends_items[$i]['ht:news_item'][$htnews_item_index][$htchild->nodeName] = $htchild->nodeValue;
                          }

                    }
                    $htnews_item_index++;

            break;
             
              default :
              
          }
      }

  }
  

  

}
//echo var_dump($trends_items);
$trends = new trends();
$con = $trends->baglan(HOST, USER, PASSWORD, DATABASE);
if($con===false) {
    exit('V.T. Bağlantısu kurulamadı.');
}
$channel_title_id = $trends->set_channel_title_id($channel_title);
$channel_description_id = $trends->set_channel_description_id($channel_description);

if($channel_title_id === false) {
    exit('set_channel_title_id bilinmeyen hata oluştu!');
}
echo '<h1>'.$channel_title.'</h1>';
echo '<h3>'.$channel_description.'</h3>';
$fontred='66';
$hit_line = 1;
foreach ($trends_items as $key=>$value) {
    if($fontred=='66') {$fontred='00';}
    else {$fontred='66';}
    echo '<font color="#'.$fontred.'0000">';

    $ht_news_item = $value['ht:news_item'];
    $ht_news_item_count = count($ht_news_item);
    echo '<hr/><br/>'.$value['title'].' ('.count($ht_news_item).') Kaynak<br/>';
    if($ht_news_item_count >1 ) {
        for($hi=0;$hi<$ht_news_item_count;$hi++) {
            $item = $value;
            $item['line'] = $hi;
            $item['ht:news_item'] = $value['ht:news_item'][$hi];
            print_r($item);
            $sonuc = $trends->add_item($channel_title_id, $channel_description_id, $item,$hit_line,$hi);
            print('<br/><b>Yukarda Eklenen:'.intval($sonuc).'</b><br/>');
            if($sonuc===false) {
                $guncelleme = $trends->update_item_traffic($channel_title_id, $channel_description_id, $item,$hit_line,$hi);
                print('<br/><b>Yukarda Trafiği güncellendi:'.$guncelleme.'</b><br/>');
            }
        }
    }
    else {
        $item = $value;
        $item['ht:news_item'] = $value['ht:news_item'][0];
        $item['line'] = 0;
        print_r($item);
        $sonuc = $trends->add_item($channel_title_id, $channel_description_id, $item,$hit_line,0);
        print('<br/><b><u>Yukarda Eklenen:'.intval($sonuc).'</u></b><br/>');
            if($sonuc===false) {
                $guncelleme = $trends->update_item_traffic($channel_title_id, $channel_description_id, $item,$hit_line,0);
                print('<br/><b>Yukarda Trafiği güncellendi:'.$guncelleme.'</b><br/>');
            }
    
    }
    echo '</font>';
    $hit_line++;
}
