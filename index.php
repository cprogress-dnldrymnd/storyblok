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
    'per_page' => 10,
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


function set_image($image_url, $post_id)
{
    // Add Featured Image to Post
    $image_name       = basename($image_url);
    $upload_dir       = wp_upload_dir(); // Set upload folder
    $image_data       = file_get_contents($image_url); // Get image data
    $unique_file_name = wp_unique_filename($upload_dir['path'], $image_name); // Generate unique name
    $filename         = basename($unique_file_name); // Create image file name

    // Check folder permission and define file location
    if (wp_mkdir_p($upload_dir['path'])) {
        $file = $upload_dir['path'] . '/' . $filename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }

    // Create the image  file on the server
    file_put_contents($file, $image_data);

    // Check image file type
    $wp_filetype = wp_check_filetype($filename, null);

    // Set attachment data
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title'     => sanitize_file_name($filename),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    // Create the attachment
    $attach_id = wp_insert_attachment($attachment, $file, $post_id);

    // Include image.php
    require_once('../wp-admin/includes/image.php');

    // Define attachment metadata
    $attach_data = wp_generate_attachment_metadata($attach_id, $file);

    // Assign metadata to attachment
    wp_update_attachment_metadata($attach_id, $attach_data);

    // And finally assign featured image to post
    return set_post_thumbnail($post_id, $attach_id);
}

function social($url, $class)
{
    return array(
        'qodef_team_member_icon' => 'font-awesome',
        'qodef_team_member_icon-font-awesome' => $class,
        'qodef_team_member_icon_link' => $url
    );
}

foreach ($stories as $story) {

    $qodef_team_member_social_icons = array();

    $instagramUrl = isset($story['content']['instagramUrl']['url']) ? $story['content']['instagramUrl']['url'] : false;
    $spotifyUrl = isset($story['content']['spotifyUrl']['url']) ? $story['content']['spotifyUrl']['url'] : false;
    $bandcampUrl = isset($story['content']['bandcampUrl']['url']) ? $story['content']['bandcampUrl']['url'] : false;
    $website1Url = isset($story['content']['website1Url']['url']) ? $story['content']['website1Url']['url'] : false;
    $website2Url = isset($story['content']['website2Url']['url']) ? $story['content']['website2Url']['url'] : false;
    $website3Url = isset($story['content']['website3Url']['url']) ? $story['content']['website3Url']['url'] : false;
    $soundcloudUrl = isset($story['content']['soundcloudUrl']['url']) ? $story['content']['soundcloudUrl']['url'] : false;
    $residentAdvisorUrl = isset($story['content']['residentAdvisorUrl']['url']) ? $story['content']['residentAdvisorUrl']['url'] : false;



    if ($instagramUrl) {
        $qodef_team_member_social_icons[] = social($instagramUrl, 'fab fa-instagram');
    }


    if ($spotifyUrl) {
        $qodef_team_member_social_icons[] = social($spotifyUrl, 'fab fa-spotify');
    }


    if ($bandcampUrl) {
        $qodef_team_member_social_icons[] = social($bandcampUrl, 'fab fa-bandcamp');
    }

    if ($website1Url) {
        $qodef_team_member_social_icons[] = social($website1Url, 'fa fa-globe-africa');
    }

    if ($website2Url) {
        $qodef_team_member_social_icons[] = social($website2Url, 'fa fa-globe-africa');
    }

    if ($website3Url) {
        $qodef_team_member_social_icons[] = social($website3Url, 'fa fa-globe-africa');
    }

    if ($soundcloudUrl) {
        $qodef_team_member_social_icons[] = social($soundcloudUrl, 'fab fa-soundcloud');
    }


    if ($residentAdvisorUrl) {
        $qodef_team_member_social_icons[] = social($residentAdvisorUrl, 'fa fa-ad');
    }


    $blog_array[] = array(
        'post_title' => $story['content']['name'],
        'post_content' => $story['content']['text'],
        'post_image' => $story['content']['image']['filename'],
        'meta_input' => array(
            'qodef_team_member_role' => $story['content']['profession'],
            'qodef_team_member_social_icons' => $qodef_team_member_social_icons,
            'uuid' =>  $story['uuid']
        )
    );
}
?>


<pre>
    <?php var_dump(get_post_meta(525)) ?>
    <?php var_dump(get_post_meta(525, 'qodef_team_member_social_icons', true)) ?>
</pre>

<?php


foreach ($blog_array as $blog) {

    $my_post = array(
        'post_type' => 'team',
        'post_title' => $blog['post_title'],
        'post_status' => 'publish',
        'post_content'  => $blog['post_content'],
        'post_category' => array($blog['post_category']),
        'meta_input' => $blog['meta_input']
    );



    // Insert the post into the database
    $post_id = wp_insert_post($my_post);

    set_image($blog['post_image'], $post_id);
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