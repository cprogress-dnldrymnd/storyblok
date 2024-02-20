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

    $contents = $story['content']['introText'];

    foreach ($contents as $key => $content) {
        if ($key == 'content') {
            foreach($content as $con) {
                if($con['type'] == 'paragraph') {
                    
                }
            }
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