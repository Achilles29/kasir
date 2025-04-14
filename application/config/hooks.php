<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hook['post_controller_constructor'][] = array(
    'class'    => '',
    'function' => 'corsHeaders',
    'filename' => 'cors.php',
    'filepath' => 'hooks'
);
