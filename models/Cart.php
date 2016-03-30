<?php
class Cart{
    public static function addProduct($id){
        $id = intval($id);
        
        $productsInCart = [];
        
        //Если в корзине уже есть товары (хранятся в сессии)
        if(isset($_SESSION['products'])){
            //То заполним наш массив товарами
            $productsInCart = $_SESSION['products'];
        }
        
        //Если товар есть в корзине, но был выбран еще раз
        if(array_key_exists($id, $productsInCart)){
            $productsInCart[$id]++;
        }else{
            //Добавляем новый товар в корзину
            $productsInCart[$id] = 1;
        }
        
        $_SESSION['products'] = $productsInCart;
        
        return self::countItems();
    }
    
    public static function countItems(){
        if(isset($_SESSION['products'])){
            $count = 0;
            foreach ($_SESSION['products'] as $quantity) {
                $count = $count + $quantity;
            }
            return $count;
        }else{
            return 0;
        }
    }
    
    public static function getProducts(){
        if(isset($_SESSION['products'])){
            return $_SESSION['products'];
        }
        return false;
    }
    
    public static function getTotalPrice($products){
        if($products){
            $productsInCart = self::getProducts();
            //echo '<pre>';
            //print_r($products);
            //print_r($productsInCart);
            if($productsInCart){
                $total = 0;
                foreach ($products as $item){
                    $total +=$item['price'] * $productsInCart[$item['id']];
                }
            }
            return $total;
        }    
    }
    
    public static function clear(){
        if(isset($_SESSION['products'])){
            unset($_SESSION['products']);
        }
    }
    
    public static function delete($id){
        if($id){
            if($_SESSION['products']){
                unset($_SESSION['products'][$id]);
            }
        }
    }
}
