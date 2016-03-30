<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
abstract class AdminBase{
 /* 
 * To changeLicense Headers in Project
 */ 
    public static function checkAdmin(){
        //Авторизирован ли пользователь. Если нет, он будет перенаправлен
        $userId = User::checkLogged();
        
        //Получаем информацию о текущем пользователе
        $user = User::getUserById($userId);
        
        //Если текущий пользователь admin, пускаем его в админ панель
        if($user['role'] == 'admin'){
            return true;
        }
        
        //Иначе завершаем работу с сообщением об закрытом доступе
        die('Access denied');
    }
}
