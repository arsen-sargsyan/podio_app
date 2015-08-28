<?php
/**
 * Created by PhpStorm.
 * User: Arsen
 * Date: 2015-08-28
 * Time: 6:14 PM
 */

$file = fopen(__DIR__.'test.txt','w+');
fwrite($file,print_r($_REQUEST,true));
fclose($file);