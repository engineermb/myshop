<?php
class User{
    public static function register($name, $email, $password){
       try{
            $db = Db::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = 'INSERT INTO users (name, email, password) 
                    VALUES (:name, :email, :password)';
            
            $result = $db->prepare($sql);
            $result->bindParam(':name', $name, PDO::PARAM_STR);
            $result->bindParam(':email', $email, PDO::PARAM_STR);
            $result->bindParam(':password', $password, PDO::PARAM_STR);
            
            return $result->execute();
            
        }catch(PDOException $e){
            echo $e->getMessage();
            echo 'email ERROR!';
        } 
    }
    
    public static function checkName($name){
        if(strlen($name) >= 2){
            return true;
        }
        return false;
    }
    
    public static function checkPassword($password){
        if(strlen($password) >= 6){
            return true;
        }
        return false;
    }
    
    public static function checkEmail($email){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        }
        return false;
    }
    
    public static function checkPhone($userPhone){
        if(strlen($userPhone) >=11){
            return true;
        }
        return false;
    }
    
    public static function checkEmailExists($email){
        try{
            $db = Db::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = 'SELECT COUNT(*) FROM users WHERE email = :email';
            
            $result = $db->prepare($sql);
            $result->bindParam(':email', $email, PDO::PARAM_STR);
            $result->execute();
            
            if($result->fetchColumn()){
                return true;
            }
            return false;
        }catch(PDOException $e){
            echo $e->getMessage();
            echo 'email ERROR!';
        }
    }
    
    public static function checkUserData($email, $password){
        try{
            $db = Db::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = 'SELECT * FROM users WHERE email = :email AND password = :password';
            
            $result = $db->prepare($sql);
            $result->bindParam(':email', $email, PDO::PARAM_STR);
            $result->bindParam(':password', $password, PDO::PARAM_STR);
            $result->execute();
            
            $user = $result->fetch();
            if($user){
                return $user['id'];
            }
            return false;
        }catch(PDOException $e){
            echo $e->getMessage();
            echo 'email ERROR!';
        }
    }
    
    public static function auth($userId){
        //session_start();
        $_SESSION['user'] = $userId;
    }
    
    public static function checkLogged(){
        //session_start();
        //Если сессия есть вернем идентификатор пользователя
        if(isset($_SESSION['user'])){
            return $_SESSION['user'];
        }
        header("Location: /user/login");
    }
    
    public static function isGuest(){
        //session_start;
        if(isset($_SESSION['user'])){
            return false;
        }
        return true;
    }
    
    public static function getUserById($id){
        if($id){
            try{
                $db = Db::getConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $sql = 'SELECT * FROM users WHERE id = :id';
                
                $result = $db->prepare($sql);
                $result->bindParam(':id', $id, PDO::PARAM_INT);
                $result->execute();
                
                return $result->fetch(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                echo $e->getMessage();
                echo 'userInfo ERROR!';
            }
        }
    }
    
    public static function edit($id, $name, $password){
        try{    
                $db = Db::getConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $sql = 'UPDATE users
                        SET name = :name, password = :password
                        WHERE id = :id';
                
                $result = $db->prepare($sql);
                $result->bindParam(':id', $id, PDO::PARAM_INT);
                $result->bindParam(':name', $name, PDO::PARAM_STR);
                $result->bindParam(':password', $password, PDO::PARAM_STR);
                return $result->execute();
            }catch(PDOException $e){
                echo $e->getMessage();
                echo 'userUpdate ERROR!';
            }
    }
    
}
?>