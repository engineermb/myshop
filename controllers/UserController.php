<?php
class UserController{
    public function actionRegister(){
        $name = '';
        $email = '';
        $password = '';
        $result = false;
        if(isset($_POST['submit'])){
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
                        
            $errors =false;
            
            if(!User::checkName($name)){
                $errors[] = 'Имя не должно быть короче 2-х символов';
            }
            
            if(!User::checkEmail($email)){
                $errors[] = 'Неправильный email!';
            }
            
            if(!User::checkPassword($password)){
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }
            
            if(User::checkEmailExists($email)){
                $errors[] = 'Такой emeil уже существует';
            }
            
            if($errors == false){
                //Save user
                $result = User::register($name, $email, $password);
            }
        }

        require_once(ROOT . '/views/user/register.php');
        //header("Location: ". ROOT . '/views/user/register.php');
        return true;
    }
    
    public function actionLogin(){
        $email = '';
        $password = '';
        if(isset($_POST['submit'])){
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $errors = false;
            
            //Валидация полей
            if(!User::checkEmail($email)){
                $errors[] = 'Неправильный email';
            }
            if(!User::checkPassword($password)){
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }
            
            //Проверяем существует ли пользователь
            $userId = User::checkUserData($email, $password);
            
            if($userId == false){
                //Если данные не правильные - показать ошибку
                $errors[] = 'Неправильные данные для входа';
            }else{
                //Если данные правильные, включаем сессию
                User::auth($userId);
                
                //Перенаправляем пользователя в закрытую часть - кабинет
                header("Location: /cabinet/");
            }
        }
        require_once(ROOT . '/views/user/login.php');
    }
    
    public function actionLogout(){
        unset($_SESSION['user']);
        header("Location: /");
    } 
}
?>