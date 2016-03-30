<?php
class Db{
    public static function getConnection(/*$dbname = false*/){
        $paramsPath = ROOT . '/config/db_params.php';
        $params = include($paramsPath);
       // if(!$dbname){
            $str = "mysql:host={$params['host']};dbname={$params['dbname']}";
        //}else{
           // $str = "mysql:host={$params['host']};dbname={$dbname}";
       // }
        
        $db = new PDO($str, $params['user'], $params['password']);
        //$db->exec('set names utf-8');
        return $db;
    }
}
?>