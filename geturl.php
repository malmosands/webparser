<?php

  $http_origin = $_SERVER['HTTP_ORIGIN'];

  if ($http_origin == "https://tripfeed.co" || $http_origin == "https://www.tripfeed.co")
  {
      header("Access-Control-Allow-Origin: $http_origin");
  }

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
    if($meta->getAttribute('property') == 'og:image')
        $image = $meta->getAttribute('content');
    if($meta->getAttribute('property') == 'og:site_name')
        $site_name = $meta->getAttribute('content');
  }

?>

<div class="card">
  <?php if(!empty($image)) { ?>
    <div class="card-header-img" style="background-image:url(<?php echo $image; ?>)">
      <?php if(!empty($site_name)) { ?>
        <p class="card-text">
          <span class="tag tag-red"><?php echo $site_name ?></span>
        </p>
      <?php } ?>
      <h4 class="card-title mt-v"><?php echo substr($title,0,60) . "(...)" ?></h4>
    </div>
  <?php } else { ?>
    <div class="card-header">
      <h4 class="card-title mt-v"><?php echo substr($title,0,60) . "(...)" ?></h4>
    </div>
  <?php } ?>
  <div class="card-block">
    <?php if(!empty($description)) { ?>
      <p class="card-text">
        <?php echo substr($description,0,75) . "(...)" ?>
      </p>
    <?php } ?>
  </div>
</div>


<input type="hidden" name="image" value="<?php echo $image; ?>">
<input type="hidden" name="description" value="<?php echo $description; ?>">
<input type="hidden" name="site_name" value="<?php echo $site_name ?>">
<input type="hidden" name="title" value="<?php echo $title ?>">
<input type="hidden" name="place_id" value="">
<input type="hidden" name="lat" value="">
<input type="hidden" name="lng" value="">
