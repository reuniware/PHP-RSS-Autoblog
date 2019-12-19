<?php

// echo "<img src='https://media-mbst-pub-ue1.s3.amazonaws.com/creatr-images/2019-11/f3fe64e0-06ee-11ea-a7b7-c13b44577ec9' width='125px' height='56px'/>";

echo "<html><head><title>Alter Native News Blog</title>";
echo "<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>";
echo "</head><body>";

$arr = file("rsslinks.txt", FILE_IGNORE_NEW_LINES);

if (isset($_GET['id_feed'])) {
    echo "<a href='/'>Back to main</a><br/><br/>";
    $id_feed = $_GET['id_feed'];
    getFeed($arr[$id_feed]);
} else {
    $id_feed = 0;
    foreach ($arr as &$value) {
    try {
        if (!startsWith($value, "#")) {
            $subvalue = explode('##', $value);
            if ($subvalue[1] <> null) {
                echo "$subvalue[1] : ";
            }
            getFeedTitle($subvalue[0], $id_feed);
        }
        $id_feed = $id_feed+1;
    }catch(exception $e){
        echo "$e";
    }
    }
}

echo "</body></html>";
exit;

function getFeedTitle($feed_url, $id_feed) {

    $microtime_start = microtime(true);

    try{
    $content = file_get_contents($feed_url);
    $x = new SimpleXmlElement($content);
    
    foreach($x->channel as $entry) {
        $fulltitle = "$entry->title ";
        if ($entry->lastBuildDate<>"") $fulltitle = $fulltitle . "($entry->lastBuildDate)";
        echo "<a href='?id_feed=$id_feed'>$fulltitle</a>";
    }
    }catch(exception $e){
        
    }
    
    $microtime_end = microtime(true);
    $elapsed_time = $microtime_end - $microtime_start;
    $elapsed_str = number_format((float)$elapsed_time, 2, '.', '');

    echo "<p class='text-warning'>(Loading time = $elapsed_str)</p>";
    
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
