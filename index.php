<?php
/**
 * Created by PhpStorm.
 * User: wikid
 * Date: 23.05.2017
 * Time: 21:17
 */
require_once './vendor/autoload.php';
set_time_limit(30000);
$generator = new \Controller\Generator();
print_r(json_encode($generator->generate_discount_codes(10, 5,70,'AABB')));
