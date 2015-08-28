<?php
/**
 * Created by PhpStorm.
 * User: Arsen
 * Date: 2015-08-28
 * Time: 6:14 PM
 */
//composer autoloader
require_once 'vendor/autoload.php';

// load config file
$config = require_once 'config.php';

// Log file
$file = __DIR__."/webhook.log";

// Setup client and authenticate
Podio::setup($config['client_id'], $config['client_secret']);
Podio::authenticate_with_app($config['app_id'], $config['app_token']);

// Big switch statement to handle the different events

switch ($_POST['type']) {

  // Validate the webhook. This is a special case where we verify newly created webhooks.
  case 'hook.verify':
    PodioHook::validate($_POST['hook_id'], array('code' => $_POST['code']));

  // An item was created
  case 'item.create':
    $string = gmdate('Y-m-d H:i:s') . " item.create webhook received. ";
    $string .= "Post params: ".print_r($_POST, true) . "\n";
    file_put_contents($file, $string, FILE_APPEND | LOCK_EX);
    $item = PodioItem::get($_POST['item_id']);
//    echo '<pre>';print_r($item->files[0]);die;
	break;

}