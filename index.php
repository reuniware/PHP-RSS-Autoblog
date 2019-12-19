<?php

// echo "<img src='https://media-mbst-pub-ue1.s3.amazonaws.com/creatr-images/2019-11/f3fe64e0-06ee-11ea-a7b7-c13b44577ec9' width='125px' height='56px'/>";

echo "<html><head><title>Autoblog</title></head><body>";

$arr = file("rsslinks.txt", FILE_IGNORE_NEW_LINES);

if (isset($_GET['id_feed'])) {
    echo "<a href='/'>Back to main</a><br/><br/>";
    $id_feed = $_GET['id_feed'];
    getFeed($arr[$id_feed]);
    
    echo "</body></html>";
    exit;
}

$id_feed = 0;
foreach ($arr as &$value) {
try {
    if (!startsWith($value, "#")) {
        getFeedTitle($value, $id_feed);
    }
    $id_feed = $id_feed+1;
}catch(exception $e){
    echo "$e";
}
}

echo "</body></html>";
exit;

function getFeedTitle($feed_url, $id_feed) {

    try{
    $content = file_get_contents($feed_url);
    $x = new SimpleXmlElement($content);
    
    foreach($x->channel as $entry) {
        $fulltitle = "$entry->title ";
        if ($entry->lastBuildDate<>"") $fulltitle = $fulltitle . "($entry->lastBuildDate)";
        echo "<a href='?id_feed=$id_feed'>$fulltitle</a><br/>";
    }
    }catch(exception $e){
        
    }
    
}

function getFeed($feed_url) {
     
    $content = file_get_contents($feed_url);
    $x = new SimpleXmlElement($content);
    
    foreach($x->channel as $entry) {
        echo "<h3>$entry->title ";
        if ($entry->lastBuildDate<>"") echo "($entry->lastBuildDate)";
        echo "</h3>";
    }
    
    echo "<ul>";
     
    foreach($x->channel->item as $entry) {
        echo "<li>$entry->pubDate : <a href='$entry->link' title='$entry->title'>" . $entry->title . "</a></li>";
        if ($entry->description <> "") {
            echo "$entry->description";
            echo "<br/><br/>";
        } else {
        }
    }
    
    echo "</ul>";
    
}

function startsWith ($string, $startString) 
{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
} 

?>
