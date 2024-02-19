<?php
// Require composer autoload
require 'vendor/autoload.php';
// Use the Storyblok\Client class
use Storyblok\Client;
// Use the Client class
$client = new Client('Z5R6TMf4M0FDuypDqcwQIwtt');

// Get all Stories from the article folder
$client->getStories([
    'starts_with' => 'blog',
    'per_page' => 70
]);
$data = $client->getBody();
$stories = $data["stories"];
?>

<?php

$blog_array = array();

foreach ($stories as $story) {

    $content = $story['content']['introText'];

    foreach ($content as $key => $contentx) {
        if($key == 'content') {
            
        }
        echo '<pre>';
        echo $key;
        echo '</pre>';
    }
    $blog_array[] = array(
        'name' => $story['name'],
        'date' => $story['published_at'],
        'content' => $content,
    );
}
?>

<pre>
    <?php var_dump($blog_array); ?>
</pre>