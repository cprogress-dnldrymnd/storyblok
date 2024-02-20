<?php
// Require composer autoload
require 'vendor/autoload.php';
// Use the Storyblok\Client class
use Storyblok\Client;
// Use the Client class
$client = new Client('Z5R6TMf4M0FDuypDqcwQIwtt');

// Get all Stories from the article folder
$client->getStories([
    'page' => 1,
    'starts_with' => 'blog',
]);
$data = $client->getBody();
$stories = $data["stories"];
?>

<?php
$blog_array = array();

foreach ($stories as $story) {

    $content = $story['content']['introText'][1];

    foreach ($content as $key => $contentx) {
        if ($key == 'content') {
            echo '<pre>';
            echo $key;
            echo '</pre>';
        }
    }
    $blog_array[] = array(
        'name' => $story['content']['title'],
        'date' => $story['published_at'],
        'content' => $content,
    );
}
?>

<pre>
    <?php var_dump($blog_array); ?>
</pre>