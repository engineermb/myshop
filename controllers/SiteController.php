<?php
include_once ROOT . '/models/Category.php';
include_once ROOT . '/models/Product.php';
class SiteController{
    public function actionIndex(){
        $categories = Category::getCategoriesList();
        
        $latestProducts = Product::getLatestProducts();
        //var_dump($categories);
        $sliderProducts = Product::getRecomendedProducts();
        //echo '<pre>';
        //var_dump($sliderProducts);
        
        require_once(ROOT . '/views/site/index.php');
        return true;
    }
    
    public function actionContact() {
        $userEmail = '';
        $userText = '';
        $result = false;
        
        if(isset($_POST['submit'])){
            $userMail = $_POST['userEmail'];
            $userText = $_POST['userText'];
            
            $errors = false;
            
            //Валидация полей
            if(!User::checkEmail($userEmail)){
                $errors[] = 'Неправильный email';
            }
            
            if($errors == false){
                $mail = 'engineermb@yandex.ru';
                $subject = 'Тема письма';
                $message = 'Текст: ' . $userText . 'От ' . $userEmail;
                $result = mail($mail, $subject, $message); 
            }
        }
        
        var_dump($result);
        
        require_once(ROOT . '/views/site/contact.php');
    }
}
?>