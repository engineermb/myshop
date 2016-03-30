<?php
class Order{
    public static function save($userName, $userPhone, $userComment, $userId, $products){
        try{
            $db = Db::getConnection();
            
            $sql = 'INSERT INTO product_order (user_name, user_phone, user_comment, user_id, products)'
                    . 'VALUES (:user_name, :user_phone, :user_comment, :user_id, :products)';
            //echo '<pre>';
            //var_dump($products);
            
            $products = json_encode($products);
            
            //var_dump($products);
            
            $result = $db->prepare($sql);
            $result->bindParam(':user_name', $userName, PDO::PARAM_STR);
            $result->bindParam(':user_phone', $userPhone, PDO::PARAM_STR);
            $result->bindParam(':user_comment', $userComment, PDO::PARAM_STR);
            $result->bindParam(':user_id', $userId, PDO::PARAM_STR);
            $result->bindParam(':products', $products, PDO::PARAM_STR);
            
            return $result->execute();
            
        } catch (Exception $ex) {
            $ex->getMessage();
            echo 'order ERROR';
        }
    }
    
    public static function getStatusText($status){
        switch($status){
            case '1':
                return 'Новый заказ';
                break;
            case '2':
                return 'В обработке';
                break;
            case '3':
                return 'Доставляется';
                break;
            case '4':
                return 'Закрыт';
                break;
        }
    }
    
    public static function getOrderById($id){
        try{
            $db = Db::getConnection();
            
            $sql = 'SELECT * FROM product_order WHERE id = :id';
            
            $result = $db->prepare($sql);
            $result->bindParam(':id', $id, PDO::PARAM_INT);
            $result->execute();
            return $result->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            $ex->getMessage();
            echo 'getOrderById ERROR';
        }
    }
    
    public static function getOrdersList(){
        try{
            $db = Db::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'SELECT * FROM product_order';
            $result = $db->query($sql);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            $ex->getMessage();
            echo 'getOrdersList ERROR';
        }
    }
    
    public static function deleteOrderById($id){
        try{
            $db = Db::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = 'DELETE FROM product_order WHERE id = :id';
            
            $result = $db->prepare($sql);
            
            $result->bindParam(':id', $id, PDO::PARAM_STR);
            return $result->execute();
        } catch (Exception $ex) {
            $ex->getMessage();
            echo 'deleteOrderById ERROR';
        }
    }
    
    public static function updateOrderById($id, $status){
        try{
            $db = Db::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = 'UPDATE product_order SET status = :status WHERE id = :id';
            
            $result = $db->prepare($sql);
            $result->bindParam(':status', $status, PDO::PARAM_INT);
            $result->bindParam(':id', $id, PDO::PARAM_INT);
            return $result->execute();
        } catch (Exception $ex) {

        }
    }
}
