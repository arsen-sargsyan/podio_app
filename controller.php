<?php

/**
 * Created by PhpStorm.
 * User: Arsen
 * Date: 2015-08-28
 * Time: 5:31 PM
 */
class Controller
{
    public $config_errors = [];
    public $doc_mime_list = [
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/msword'
    ];

    public function validate_config($source){
        $config_keys = [
            'env',
            'client_id',
            'client_secret',
            'app_id',
            'app_token',
        ];

        if(is_array($source)){
            foreach($config_keys as $item){
                if (isset($source[$item])) {
                    if(!strlen($source[$item]) > 0){
                        $this->config_errors[] = sprintf('Config field %s shouldn\'t be empty',$item);
                    }
                }else{
                    $this->config_errors[] = sprintf('Config field %s is missing',$item);
                }
            }

        }else{
            $this->config_errors[] =  'Wrong config file!';
        }

        return empty($this->config_errors);
    }
}