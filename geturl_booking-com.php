<?php

  $http_origin = $_SERVER['HTTP_ORIGIN'];

  if ($http_origin == "https://tripfeed.co" || $http_origin == "https://www.tripfeed.co")
  {
      header("Access-Control-Allow-Origin: $http_origin");
  }

  $geturl = $_GET["url"];
  $randomId = $_GET["item_id"];

  $html = file_get_contents($geturl);

  $doc = new DOMDocument;

  // We don't want to bother with white spaces
  $doc->preserveWhiteSpace = false;

  // Most HTML Developers are chimps and produce invalid markup...
  $doc->strictErrorChecking = false;
  $doc->recover = true;

  @$doc->loadHTML($html);

  $xpath = new DOMXPath($doc);

  $get_name = "//h2[@class='hp__hotel-name']";
  $get_desc = "//meta[@name='description']";
  $get_address = "//span[@class='hp_address_subtitle jq_tooltip']";
  $get_rating = "//span[@class='review-score-badge']";
  $get_ratingtext = "//span[@class='review-score-widget__text']";
  $get_logo = "//img[@id='logo_no_globe_new_logo']";
  $get_stars = "//div[@class='hp__hotel_ratings__stars']";
  $get_image = "//meta[@property='og:image']";

  $name = $xpath->query($get_name);
  $desc = $xpath->query($get_desc);
  $address = $xpath->query($get_address);
  $ratingtext = $xpath->query($get_ratingtext);
  $rating = $xpath->query($get_rating);
  $logo = $xpath->query($get_logo);
  $stars = $xpath->query($get_stars);
  $image = $xpath->query($get_image);

?>

<div class="card-header">
  <div class="header-img" style="background-image:url(<?php echo $image->item(0)->getAttribute('content') ?>);"></div>
  <span class="mt-v"><img src="<?php echo $logo->item(0)->getAttribute('src') ?>"></span>
  <h4 class="card-title"><?php echo substr($name->item(0)->textContent,0,60); ?></h4>
</div><?php echo $stars->item(0)->textContent ?>
<div class="card-block bb-1">
  Rating
  <span class="float-right"><?php echo $rating->item(0)->textContent ?></strong><?php echo $ratingtext->item(0)->textContent ?></span>
</div>
<div class="card-block">
  <p class="card-text">
    <small><?php echo substr($desc->item(0)->getAttribute('content'),0,75); ?></small>
  </p>
</div>

<div id="addtotrip-<?php echo $randomId; ?>" class="modal">
  <div class="modal-body modal-sm">
    <div class="modal-header">
      <p class="modal-text mb-0">Add to Trip</p>
      <h2 class="modal-title truncate"><?php echo $title ?></h2>
    </div>
    <div class="modal-block">
      <form class="add-item">
        <div class="form-group dates">
          <div>
            <label>Start</label>
            <input type="date" class="form-control" name="startDate" required>
          </div>
          <div>
            <label>End</label>
            <input type="date" class="form-control" name="endDate" required>
          </div>
        </div>
        <div class="form-group">
          <label>Type</label>
          <select name="type" class="form-control">
            <option value="activity">Activity</option>
            <option value="accommodation">Accommodation</option>
            <option value="connection">Connection</option>
          </select>
        </div>
        <div class="form-group">
          <label>Description</label>
          <textarea name="description" class="form-control" rows="5"></textarea>
        </div>
        <input type="hidden" name="title" value="<?php echo $title ?>">
        <input type="hidden" name="randomId" value="<?php echo $randomId ?>">
        <input type="hidden" name="place_id" value="">
        <input type="hidden" name="lat" value="">
        <input type="hidden" name="lng" value="">
        <input type="hidden" name="url" value="<?php echo $geturl ?>">
        <button type="submit" class="btn btn-teal">Save</button>
      </form>
    </div>
  </div>
  <div class="backdrop"></div>
</div>
