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
    'starts_with' => 'community',
]);
$data = $client->getBody();
$stories = $data["stories"];
?>

<?php
$blog_array = array();
require_once("../wp-load.php");

function get_contents($contents, $content_arr = true)
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
                } else if ($con['type'] == 'ordered_list') {
                    $contents_var .= '<ol>';
                }


                if ($content_arr) {

                    foreach ($arr as $ar) {
                        $contents_var .= marks_open($ar);
                        $contents_var .= content_type($ar);
                        $contents_var .= marks_close($ar);
                    }
                } else {

                    $contents_var .= marks_open($con);

                    $contents_var .= content_type($con);

                    $contents_var .= marks_close($con);
                }

                if ($con['type'] == 'paragraph') {
                    $contents_var .= '</p>';
                } else if ($con['type'] == 'heading') {
                    $contents_var .= '</h' . $con['attrs']['level'] . '>';
                } else if ($con['type'] == 'bullet_list') {
                    $contents_var .= '</ul>';
                } else if ($con['type'] == 'ordered_list') {
                    $contents_var .= '</ol>';
                }
            }
        }
    }
    return $contents_var;
}

function content_type($ar)
{
    $contents_var = '';

    if ($ar['type'] == 'text') {
        $contents_var .= $ar['text'];
    } else if ($ar['type']  == 'image') {
        $filename =  str_replace(".jpeg", ".jpg", $ar['attrs']['src']);
        $filename =  str_replace(".JPG", ".jpg", $filename);

        $contents_var .= '<span class="blog-image"><img src="https://ten87.theprogressteam.co.uk/wp-content/uploads/2024/02/' . basename($filename) . '"/></span>';
    } else if ($ar['type'] == 'list_item') {
        foreach ($ar['content'] as $key => $content2) {
            $contents_var .= '<li>';

            $contents_var .= call_user_func('get_contents', $content2, false);

            $contents_var .= '</li>';
        }
    }

    return $contents_var;
}

function marks_open($con)
{
    $contents_var = '';
    if ($con['marks'][0]['type'] == 'bold') {
        $contents_var .= '<strong>';
    } else if ($con['marks'][0]['type']  == 'link') {
        $contents_var .= '<a href="' . $con['marks'][0]['attrs']['href'] . ' " target="' . $con['marks'][0]['attrs']['target'] . ' ">';
    }

    return $contents_var;
}

function marks_close($con)
{
    $contents_var = '';

    if ($con['marks'][0]['type'] == 'bold') {
        $contents_var .= '</strong>';
    } else if ($con['marks'][0]['type']  == 'link') {
        $contents_var .= '</a>';
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


    $image_player = isset($contents['player'][0]['image']['filename']) ? $contents['player'][0]['image']['filename'] : false;



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

    if ($image_player) {
        $contents_var .= '<span class="blog-image"><img src="' . $image_player . '"/></span>';
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

    $blog_array[] = array(
        'post_title' => $story['content']['name'],
        'post_content' => $contents_var,
    );
}
?>


<pre>
    <?php var_dump(get_post_meta(5165)) ?>
    <?php
    update_post_meta(527, 'qodef_team_member_social_icons', 'a:5:{i:0;a:9:{s:22:"qodef_team_member_icon";s:12:"font-awesome";s:32:"qodef_team_member_icon-dripicons";s:0:"";s:36:"qodef_team_member_icon-elegant-icons";s:0:"";s:35:"qodef_team_member_icon-font-awesome";s:16:"fab fa-instagram";s:31:"qodef_team_member_icon-ionicons";s:0:"";s:34:"qodef_team_member_icon-linea-icons";s:0:"";s:35:"qodef_team_member_icon-linear-icons";s:13:"lnr lnr-alarm";s:27:"qodef_team_member_icon_link";s:1:"#";s:29:"qodef_team_member_icon_target";s:0:"";}i:1;a:9:{s:22:"qodef_team_member_icon";s:12:"font-awesome";s:32:"qodef_team_member_icon-dripicons";s:0:"";s:36:"qodef_team_member_icon-elegant-icons";s:0:"";s:35:"qodef_team_member_icon-font-awesome";s:17:"fab fa-soundcloud";s:31:"qodef_team_member_icon-ionicons";s:0:"";s:34:"qodef_team_member_icon-linea-icons";s:0:"";s:35:"qodef_team_member_icon-linear-icons";s:13:"lnr lnr-alarm";s:27:"qodef_team_member_icon_link";s:1:"#";s:29:"qodef_team_member_icon_target";s:0:"";}i:2;a:9:{s:22:"qodef_team_member_icon";s:12:"font-awesome";s:32:"qodef_team_member_icon-dripicons";s:0:"";s:36:"qodef_team_member_icon-elegant-icons";s:0:"";s:35:"qodef_team_member_icon-font-awesome";s:18:"fa fa-globe-africa";s:31:"qodef_team_member_icon-ionicons";s:0:"";s:34:"qodef_team_member_icon-linea-icons";s:0:"";s:35:"qodef_team_member_icon-linear-icons";s:13:"lnr lnr-alarm";s:27:"qodef_team_member_icon_link";s:1:"#";s:29:"qodef_team_member_icon_target";s:0:"";}i:3;a:9:{s:22:"qodef_team_member_icon";s:12:"font-awesome";s:32:"qodef_team_member_icon-dripicons";s:0:"";s:36:"qodef_team_member_icon-elegant-icons";s:0:"";s:35:"qodef_team_member_icon-font-awesome";s:15:"fab fa-bandcamp";s:31:"qodef_team_member_icon-ionicons";s:0:"";s:34:"qodef_team_member_icon-linea-icons";s:0:"";s:35:"qodef_team_member_icon-linear-icons";s:13:"lnr lnr-alarm";s:27:"qodef_team_member_icon_link";s:0:"";s:29:"qodef_team_member_icon_target";s:0:"";}i:4;a:9:{s:22:"qodef_team_member_icon";s:12:"font-awesome";s:32:"qodef_team_member_icon-dripicons";s:0:"";s:36:"qodef_team_member_icon-elegant-icons";s:0:"";s:35:"qodef_team_member_icon-font-awesome";s:14:"fab fa-spotify";s:31:"qodef_team_member_icon-ionicons";s:0:"";s:34:"qodef_team_member_icon-linea-icons";s:0:"";s:35:"qodef_team_member_icon-linear-icons";s:13:"lnr lnr-alarm";s:27:"qodef_team_member_icon_link";s:1:"#";s:29:"qodef_team_member_icon_target";s:0:"";}}');
    ?>
    <?php var_dump(get_post_meta(5165, 'qodef_team_member_social_icons', true)) ?>
</pre>

<?php


foreach ($blog_array as $blog) {
    // Create post object

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 1,
        'meta_query' => array(
            array(
                'key' => '_post_title',
                'value' => $blog['post_title']
            ),
        ),
    );

    /*
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            echo get_the_title();


            $my_post = array(
                'ID'           => get_the_ID(),
                'post_content'  => $blog['post_content'],
                'post_category' => array($blog['post_category'])
            );

            wp_update_post($my_post);

            echo '<br>';
        }
        wp_reset_postdata();
    } else {
        echo 'not found for ' . $blog['post_title'];
        echo '<br>';
    }
*/
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