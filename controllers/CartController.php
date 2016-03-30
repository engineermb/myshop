<?php
class CartController{
    public function actionAdd($arr){
        $id = $arr[0];
        
        //Добавляем товар в корзину
        Cart::addProduct($id);
        
        //Возвращаем пользователя на страницу
        $referrer = $_SERVER['HTTP_REFERER'];
        header("Location: " . $referrer);
    }
    
    public function actionAddAjax($arr){
        $id = $arr[0];
        //Добавляем товар в корзину
        echo Cart::addProduct($id);
        return true;
    }
    
    public function actionIndex(){
        $categories = [];
        $categories = Category::getCategoriesList();
        
        //Получаем данные из корзины
        $productsInCart = Cart::getProducts();
        
        if($productsInCart){
            //Получаем полную информацию о товарах для списка
            $productsIds = array_keys($productsInCart);
            $products = Product::getProductsByIds($productsIds);
            
            //Получаем полную стоимость товаров
            $totalPrice = Cart::getTotalPrice($products);
        }
        require_once(ROOT . '/views/cart/index.php');
    }
    
    public function actionDelete($arr){
        //Удалить товар из корзины
        $id = $arr[0];
        if($id){
            if($_SESSION['products']){
                unset($_SESSION['products'][$id]);
            }
        }
        header("Location: /cart/");
    }
    
    public function actionCheckout(){
        //Список категорий для левого меню
        $categories = [];
        $categories = Category::getCategoriesList();
        $result = false;
        
        //Статус успешного оформления заказа
        if(isset($_POST['submit'])){
            //Форма отправлена?
            
            //Выбираем данные формы
            $userName = $_POST['userName'];
            $userPhone = $_POST['userPhone'];
            $userComment = $_POST['userComment'];
            
            //Валидация полей
            $errors = false;
            if(!User::checkName($userName)){
                $errors[] = 'Неправильное имя';
            }
            
            if(!User::checkPhone($userPhone)){
                $errors[] = 'Неправильный номер';
            }
            
            //Форма заполнена корректно?
            if($errors == false){
                //Форма заполнена корректно? - Да
                //Заполняем заказ в базе данных
                
                //Собираем информацию о заказе
                $productsInCart = Cart::getProducts();
                if(User::isGuest()){
                    $userId = false;
                }else{
                    $userId = User::checkLogged();
                }
                
                //Сохраняем заказ в БД
                $result = Order::save($userName, $userPhone, $userComment, $userId, $productsInCart);
                
                if($result){
                    //Оповещаем администратора о новом заказе
                    $adminEmail = 'engineermb@yandex.ru';
                    $message = 'shopmarket order';
                    $subject = 'Новый заказ';
                    mail($adminEmail, $subject, $message);
                    
                    //Очищаем корзину
                    Cart::clear(); 
                }
            }else{
                //Форма заполнена корректно? - Нет
                
                //Итоги: общая стоимость, количество товаров
                $productsInCart = Cart::getProducts();
                $productsIds = array_keys($productsInCart);
                $products = Product::getProductsByIds($productsIds);
                $totalPrice = Cart::getTotalPrice($products);
                $totalQuantity = Cart::countItems();
            }
        }else{
            //Форма отправлена? - Нет
            
            //Получаем данные из корзины
            $productsInCart = Cart::getProducts();
            //В корзине есть товары?
            if($productsInCart == false){
                //В корзине есть товары? - Нет
                //Отправляем пользователя на главную выбирать товары
                header("Location: /");
            }else{
                //В корзине есть товары? - Да
                
                //Итоги: общая стоимость, количество товара
                $productsIds = array_keys($productsInCart);
                $products = Product::getProductsByIds($productsIds);
                $totalPrice = Cart::getTotalPrice($products);
                $totalQuantity = Cart::countItems();
                
                
                $userName = false;
                $userPhone = false;
                $userComment = false;
                
                //Пользователь авторизирован?
                if(User::isGuest()){
                    //Нет
                    //Значения для формы пустые
                }else{
                    //Да, авторизирован
                    //Получаем информацию о пользователе из БД по id
                    $userId = User::checkLogged();
                    $user = User::getUserById($userId);
                    //Подставляем в форму
                    $userName = $user['name'];
                }
            }
        }
        require_once(ROOT . '/views/cart/checkout.php');
        
        return true;
    }
}
