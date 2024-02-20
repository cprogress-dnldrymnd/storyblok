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
]);
$data = $client->getBody();
$stories = $data["stories"];
?>

<?php
$blog_array = array();

function get_contents($contents)
{
    $contents_var = '';
    foreach ($contents as $key => $content) {
        if ($key == 'content') {
            foreach ($content as $con) {
                if ($con['type'] == 'paragraph') {

                    $arr = $con['content'];

                    $contents_var .= '<p>';
                    foreach ($arr as $ar) {
                        if ($ar['marks'][0]['type'] == 'bold') {
                            $contents_var .= '<strong>';
                        }
                        $contents_var .= $ar['text'];
                        if ($ar['marks'][0]['type'] == 'bold') {
                            $contents_var .= '</strong>';
                        }
                    }
                    $contents_var .= '</p>';
                }
            }
        }
    }
}
foreach ($stories as $story) {
    $featured_image = $story['content']['coverImage'];
    $contents = $story['content']['introText'];
    $blogPostType = $story['content']['blogPostType'][0]['content'];

    $contents_var = '';

    $contents_var .= '<p>';
    $contents_var .= 'img src="' . $featured_image['filename'] . '"/';
    $contents_var .= '</p>';


    $contents_var .= get_contents($contents);



    foreach ($blogPostType as $key => $content) {
        if ($key == 'content') {
            // echo '<pre>';
            //  var_dump($content);
            // echo '</pre>';

            foreach ($content as $con) {
                if ($con['type'] == 'paragraph') {

                    $arr = $con['content'];

                    $contents_var .= '<p>';
                    foreach ($arr as $ar) {
                        if ($ar['marks'][0]['type'] == 'bold') {
                            $contents_var .= '<strong>';
                        }
                        $contents_var .= $ar['text'];
                        if ($ar['marks'][0]['type'] == 'bold') {
                            $contents_var .= '</strong>';
                        }
                    }
                    $contents_var .= '</p>';
                }
            }
        }
    }

    $blog_array[] = array(
        'name' => $story['content']['title'],
        'date' => $story['published_at'],
        'content2' => $contents_var,
        'content' => $contents,
    );
}
?>
blog_array
<pre>
    <?php var_dump($blog_array); ?>
</pre>

___STORIES
<pre>
    <?php var_dump($stories); ?>
</pre>