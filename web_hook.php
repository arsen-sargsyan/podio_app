<?php
/**
 * Created by PhpStorm.
 * User: Arsen
 * Date: 2015-08-28
 * Time: 6:14 PM
 */
//composer autoloader
require_once 'vendor/autoload.php';

//load controller
require_once 'controller.php';

// load config file
$config = require_once 'config.php';

$controller = new Controller();

$controller->init_podio($config);

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

    $item_id = $_POST['item_id'];
    $item = PodioItem::get($item_id);
    $item_file =  $item->files[0];
    $file = PodioFile::get($item_file->file_id);
    file_put_contents(__DIR__.'/temp/'.$item_file->name, $file->get_raw());
    $controller->init_pdf_renderer();

    \PhpOffice\PhpWord\Autoloader::register();


    // Creating the new document...
    $phpWord = new \PhpOffice\PhpWord\PhpWord();

    // Read contents
    $source = __DIR__ . '/temp/'.$item_file->name;
    $phpWord = \PhpOffice\PhpWord\IOFactory::load($source);

    $file_name_exploded =  explode('.',$item_file->name);

    //Save pdf file
    $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
    $xmlWriter->save(__DIR__.'/temp/'.$file_name_exploded[0].'.pdf');
    $uploadedFile = PodioFile::upload(__DIR__ . '/temp/'.$file_name_exploded[0].'.pdf', $file_name_exploded[0].'.pdf');
    PodioFile::attach($uploadedFile->file_id, array('ref_type' => 'item', 'ref_id' => $item_id));
    file_put_contents($file, $string, FILE_APPEND | LOCK_EX);

	break;

}