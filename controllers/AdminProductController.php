<?php
class AdminProductController extends AdminBase{
    /*
     * Action для страницы управления товарами
     */
    public function actionIndex(){
        //Проверка доступа
        self::checkAdmin();
        
        //Получаем список товаров
        $productsList = Product::getProductsList();
        
        //Получаем вид
        require_once(ROOT . '/views/admin_product/index.php');
    }
    
    /*
     * Action для страницы "Добавить товар"
     */
    public function actionCreate(){
        self::checkAdmin();
        
        //Получаем список категорий для выпадающего списка
        $categoriesList = Category::getCategoriesListAdmin();
        
        //Обработка формы
        if(isset($_POST['submit'])){
            //Если форма отправлена
            //Получаем данные из формы
            $options['name'] = $_POST['name'];
            $options['code'] = $_POST['code'];
            $options['price'] = $_POST['price'];
            $options['category_id'] = $_POST['category_id'];
            $options['brand'] = $_POST['brand'];
            $options['availability'] = $_POST['availability'];
            $options['availability'] = $_POST['availability'];
            $options['description'] = $_POST['description'];
            $options['description'] = $_POST['description'];
            $options['is_new'] = $_POST['is_new'];
            $options['is_recommended'] = $_POST['is_recommended'];
            $options['status'] = $_POST['status'];
            
            //Флаг ошибки в форме
            $errors = false;
            
            //При необходимости можно валидировать значение нужным образом
            if(!isset($options['name']) || empty($options['name'])){
                $errors[] = 'Заполните поля';
            }
            
            if($errors == false){
                //Добавляем новый товар
                $id = Product::createProduct($options);
                //var_dump($id);
                //echo '<pre>';
                //var_dump($_FILES['image']);
                //exit;
                //Если запись добавлена
                if($id){
                    //Проверяем загружалось ли через форму изображение
                    if(is_uploaded_file($_FILES['image']['tmp_name'])){
                        //Если загружалось, переместим его в нужную папку, дадим новое имя
                        move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/products/{$id}.png");
                    }
                }
                //Перенаправляем пользователя на страницу управления товарами
                header("Location: /admin/product");
            }
        }
        require_once('/views/admin_product/create.php');
    }
    
    public function actionDelete($arr){
        self::checkAdmin();
        $id = $arr[0];
        
        if(isset($_POST['submit'])){
            //Если форма отправлена, удаляем товар
            Product::deleteProductById($id);
            
            //Перенаправляем пользователя
            header("Location: /admin/product");
        }
        require_once(ROOT . '/views/admin_product/delete.php');
        return true;
    }
    
    public function actionUpdate($arr){
        $id = $arr[0];
        self::checkAdmin();
        
        //Получаем список категорий для выпадающего списка
        $categoriesList = Category::getCategoriesListAdmin();
        
        //Получаем данные о конкретном товаре
        $product = Product::getProductById($id);
        
        //Обработка формы
        if(isset($_POST['submit'])){
            $options['name'] = $_POST['name'];
            $options['code'] = $_POST['code'];
            $options['price'] = $_POST['price'];
            $options['category_id'] = $_POST['category_id'];
            $options['brand'] = $_POST['brand'];
            $options['availability'] = $_POST['availability'];
            $options['description'] = $_POST['description'];
            $options['is_new'] = $_POST['is_new'];
            $options['is_recommended'] = $_POST['is_recommended'];
            $options['status'] = $_POST['status'];
            
            //Сохраняем изменения
            if(Product::updateProductById($id, $options)){
                //echo '<pre>';
                //print_r($_FILES['image']);
                
                //Если запись сохранена
                //Проверим загружалось ли через форму изображение
                if(is_uploaded_file($_FILES['image']['tmp_name'])){
                    move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/products/{$id}.png");
                }
                header("Location: /admin/product");
            }
        }
        require_once(ROOT . '/views/admin_product/update.php');
        return true;
    }
}
