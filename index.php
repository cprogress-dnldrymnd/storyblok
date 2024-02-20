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
$page = $_GET['page'] ? $_GET['page'] : false;
$client->getStories([
    'page' => $page,
    'per_page' => 20,
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

                $arr = $con['content'];
                if ($con['type'] == 'paragraph') {
                    $contents_var .= '<p>';
                } else if ($con['type'] == 'heading') {
                    $contents_var .= '<h' . $con['attrs']['level'] . '>';
                } else if ($con['type'] == 'bullet_list') {
                    $contents_var .= '<ul>';
                }
                foreach ($arr as $ar) {

                    if ($ar['marks'][0]['type'] == 'bold') {
                        $contents_var .= '<strong>';
                    } else if ($ar['marks'][0]['type']  == 'link') {
                        $contents_var .= '<a href="' . $ar['marks'][0]['attrs']['href'] . ' " target="' . $ar['marks'][0]['attrs']['target'] . ' ">';
                    }


                    if ($ar['type'] == 'text') {
                        $contents_var .= $ar['text'];
                    } else if ($ar['type']  == 'image') {
                        $filename =  str_replace(".jpeg", ".jpg", $ar['attrs']['src']);
                        $filename =  str_replace(".JPG", ".jpg", $filename);
                        $contents_var .= '<span class="blog-image"><img src="https://ten87.theprogressteam.co.uk/wp-content/uploads/2024/02/' . basename($filename) . '"/></span>';
                    } else if ($ar['type'] == 'list_item') {
                        $contents_var .= '..';
                    }


                    if ($ar['marks'][0]['type'] == 'bold') {
                        $contents_var .= '</strong>';
                    } else if ($ar['marks'][0]['type']  == 'link') {
                        $contents_var .= '</a>';
                    }
                }
                if ($con['type'] == 'paragraph') {
                    $contents_var .= '</p>';
                } else if ($con['type'] == 'heading') {
                    $contents_var .= '</h' . $con['attrs']['level'] . '>';
                } else if ($con['type'] == 'bullet_list') {
                    $contents_var .= '</ul>';
                }
            }
        }
    }
    return $contents_var;
}


function get_contents_toplist($contents)
{
    $contents_var = '';

    $contents_arr = $contents['text'];
    $headline = isset($contents['headline']) ? $contents['headline'] : false;
    $subHeadline2 = isset($contents['subHeadline2']) ? $contents['subHeadline2'] : false;
    $subHeadline1 = isset($contents['subHeadline1']) ? $contents['subHeadline1'] : false;
    $spotifyUrl = isset($contents['spotifyUrl']) ? $contents['spotifyUrl'] : false;
    $websiteUrl = isset($contents['websiteUrl']) ? $contents['websiteUrl'] : false;

    $contents_var .= '<div class="top-list-item">';
    if ($headline) {
        $contents_var .= '<h2>';
        $contents_var .= $headline;
        $contents_var .= '</h2>';
    }
    if ($subHeadline1) {
        $contents_var .= '<div class="subheading">';
        $contents_var .= $subHeadline1;
        $contents_var .= '</div>';
    }
    if ($subHeadline2) {
        $contents_var .= '<div class="subheading2">';
        $contents_var .= $subHeadline2;
        $contents_var .= '</div>';
    }
    $contents_var .= get_contents($contents_arr);

    if ($spotifyUrl) {
        $contents_var .= '<div class="spotify-icon">';
        $contents_var .= '<a href="' . $spotifyUrl . '" target="_blank"> SPOTIFY </a>';
        $contents_var .= '</div>';
    }
    if ($websiteUrl) {
        $contents_var .= '<div class="website-icon">';
        $contents_var .= '<a href="' . $websiteUrl . '" target="_blank"> WEBSITE </a>';
        $contents_var .= '</div>';
    }
    $contents_var .= '</div>';

    return $contents_var;
}



foreach ($stories as $story) {
    $featured_image = $story['content']['coverImage']['filename'];
    $introText = $story['content']['introText'];
    $blogPostType = $story['content']['blogPostType'][0]['content'];

    $toplistEntries = $story['content']['blogPostType'][0]['toplistEntries'];
    $outroText = $story['content']['outroText'];

    $contents_var = '';


    $contents_var .= '<p>';
    $contents_var .= '<span class="blog-image"><img src="' . $featured_image . '"/></span>';
    $contents_var .= '</p>';

    $contents_var .= get_contents($introText);
    $contents_var .= get_contents($blogPostType);

    if ($toplistEntries) {
        $contents_var .= '<div class="top-list-item-wrapper">';
        foreach ($toplistEntries as $toplistEntry) {
            $contents_var .= get_contents_toplist($toplistEntry);
        }
        $contents_var .= '</div>';
    }

    $contents_var .= get_contents($outroText);

    $blog_array[] = array(
        'post_title' => $story['content']['title'],
        'post_date' => $story['published_at'],
        'post_content' => $contents_var,
    );
}
?>




<!--
___STORIES
-->

<?php
require_once("../wp-load.php");


foreach ($blog_array as $blog) {
    // Create post object
    $my_post = array(
        'post_title'    => wp_strip_all_tags($blog['post_title']),
        'post_content'  => $blog['post_content'],
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_date' => $blog['post_date'],
    );

    // Insert the post into the database
    // wp_insert_post($my_post);
}

?>

blog_array

<pre>
<?php var_dump($blog_array); ?>
</pre>
_stories
<pre>
<?php var_dump($stories); ?>
</pre>