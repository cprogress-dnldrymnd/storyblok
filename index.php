
<style>
    img {
        width: 100px;
    }
</style>
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
                        } else if ($ar['marks'][0]['type']  == 'link') {
                            $contents_var .= '<a href="' . $ar['marks'][0]['attrs']['href'] . ' " target="' . $ar['marks'][0]['attrs']['target'] . ' ">';
                        }


                        if ($ar['type'] == 'text') {
                            $contents_var .= $ar['text'];
                        } else if ($ar['type']  == 'image') {
                            $contents_var .= '<span class="blog-image"><img src="' . $ar['attrs']['src'] . '"/></span>';
                        }


                        if ($ar['marks'][0]['type'] == 'bold') {
                            $contents_var .= '</strong>';
                        } else if ($ar['marks'][0]['type']  == 'link') {
                            $contents_var .= '</a>';
                        }
                    }
                    $contents_var .= '</p>';
                }
            }
        }
    }
    return $contents_var;
}


foreach ($stories as $story) {
    $featured_image = $story['content']['coverImage'];
    $contents = $story['content']['introText'];
    $blogPostType = $story['content']['blogPostType'][0]['content'];

    $contents_var = '';

    $contents_var .= '<p>';
    $contents_var .= '<span class="blog-image"><img src="' . $featured_image['filename'] . '"/></span>';
    $contents_var .= '</p>';


    $contents_var .= get_contents($contents);
    $contents_var .= get_contents($blogPostType);



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
<!--
___STORIES
<pre>
    <?php var_dump($stories); ?>
</pre>--?

