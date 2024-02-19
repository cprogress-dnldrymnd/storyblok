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

<?php

    $blog_array = array();

    foreach($stories as $story) {

        $content = $story['content']['introText']['content'];
        $blog_array[] = array(
            'name' => $story['name'],
            'date' => $story['published_at'],
            'content' => $content,
        );
    }
?>

<pre>
    <?php print_r($blog_array); ?>
</pre>