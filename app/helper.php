<?php

// redirect funtion
function redirect($url)
{
    header('Location: ' . $url);
    exit;
}
// end of redirect function

// view directory function
function view($dir, $data = [], $defaultData = [])
{
    $data = array_merge($data,$defaultData);
    extract($data);
    $dir = str_replace('\\', DIRECTORY_SEPARATOR, $dir);
    $dir = str_replace('.', DIRECTORY_SEPARATOR, $dir);
    $file = APP_ROOT . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $dir . '.php';
    if (file_exists($file)) {
        return require $file;
    }
    throw new Exception('View not found. '. $file);
}
// end of view directory function

// include function
function include_file($file){
    include(APP_ROOT.'/views/'.$file);
}