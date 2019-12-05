<?php

$http_origin = $_SERVER['HTTP_ORIGIN'];

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');


/**if ($http_origin == "https://tripfeed.co" || $http_origin == "https://www.tripfeed.co")
{
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type');
}**/

$geturl = $_GET["url"];
$randomId = $_GET["id"];

function file_get_contents_curl($url)
{
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_URL, $url);

  $data = curl_exec($ch);
  curl_close($ch);

  return $data;
}

$html = file_get_contents_curl($geturl);

//parsing begins here:
$doc = new DOMDocument();
@$doc->loadHTML($html);
$nodes = $doc->getElementsByTagName('title');

//get and display what you need:
$title = $nodes->item(0)->nodeValue;

$metas = $doc->getElementsByTagName('meta');

for ($i = 0; $i < $metas->length; $i++)
{
  $meta = $metas->item($i);
  if($meta->getAttribute('name') == 'description')
      $description = $meta->getAttribute('content');
  if($meta->getAttribute('name') == 'keywords')
      $keywords = $meta->getAttribute('content');

  if($meta->getAttribute('property') == 'og:title')
      $og_title = $meta->getAttribute('content');
  if($meta->getAttribute('property') == 'og:type')
      $og_type = $meta->getAttribute('content');
  if($meta->getAttribute('property') == 'og:description')
      $og_description = $meta->getAttribute('content');
  if($meta->getAttribute('property') == 'og:image')
      $og_image = $meta->getAttribute('content');
  if($meta->getAttribute('property') == 'og:site_name')
      $og_site_name = $meta->getAttribute('content');
}

$array = array(
  "title" => $title,
  "description" => $description,
  "keywords" => [$keywords],
  "url" => $geturl,

  "og_title" => $og_title,
  "og_type" => $og_type,
  "og_description" => $og_description,
  "og_site_name" => $og_site_name,
  "og_image" => $og_image
);

$getJSON = json_encode($array);

echo $getJSON;

?>
