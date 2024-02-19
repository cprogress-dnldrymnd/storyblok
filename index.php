<?php
// Require composer autoload
require 'vendor/autoload.php';
// Use the Storyblok\Client class
use Storyblok\Client;
// Use the Client class
$client = new Client('Z5R6TMf4M0FDuypDqcwQIwtt');

// Get all Stories from the article folder
$client->getStories(['starts_with' => 'blog']);
$data = $client->getBody();
$stories = $data["stories"];
?>
<pre>
    <?php print_r($stories); ?>
</pre>

