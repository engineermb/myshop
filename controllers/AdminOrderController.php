<?php
class AdminOrderController extends AdminBase{
    
    public function actionIndex(){
        self::checkAdmin();
        
        $ordersList = Order::getOrdersList();
        
        require_once(ROOT . '/views/admin_order/index.php');
    }
    
    public static function actionView($arr){
        self::checkAdmin();
        $id =$arr[0];
        
        //Получаем данные о конкретном заказе
        $order = Order::getOrderById($id);
        
        //Получаем массив с индентификатором и кол-вом товаров
        $productQuantity = json_decode($order['products'], true);
        
        //Получаем массив с индентификаторами товаров
        $productsIds = array_keys($productQuantity);
        
        //Получаем список товаров в заказе
        $products = Product::getProductsByIds($productsIds);
        
        //Общая стоимость заказа
        $totalPrice = Product::getTotalPrice($productQuantity, $products);
        
        require_once(ROOT . '/views/admin_order/view.php');
        return true;
    }
    
    public static function actionDelete($arr){
        self::checkAdmin();
        $id = $arr[0];
        
        //Обработка формы
        if(isset($_POST['submit'])){
            //Если форма отправлена, удаляем заказ
            Order::deleteOrderById($id);
            
            header("Location: /admin/order");
        }
        require_once(ROOT . '/views/admin_order/delete.php');
    }
    
    public static function actionUpdate($arr){
        self::checkAdmin();
        $id = $arr[0];
        $order = Order::getOrderById($id);
        
        if(isset($_POST['status'])){
            $status = $_POST['status'];
            
            if(Order::updateOrderById($id, $status)){
                header('Location: /admin/order');
            }   
        }
        require_once(ROOT . '/views/admin_order/update.php');
    }
}
