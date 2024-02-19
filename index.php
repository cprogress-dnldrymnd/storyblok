<?php
// Require composer autoload
require 'vendor/autoload.php';
// Use the Storyblok\ManagementClient class
use Storyblok\ManagementClient;
// Use the ManagementClient class
$managementClient = new ManagementClient('your-storyblok-oauth-token');