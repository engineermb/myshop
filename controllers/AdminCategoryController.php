<?php
class AdminCategoryController extends AdminBase{
    
    /**
     * Action для страницы "Управление категориями"
     */
    public function actionIndex(){
        self::checkAdmin();
        
        //Получаем список категорий
        $categoriesList = Category::getCategoriesListAdmin();
        
        require_once(ROOT . '/views/admin_category/index.php');
        return true;
    }
    
    /**
     * Action для страницы "Добавить категорию"
     */
    public function actionCreate(){
        self::checkAdmin();
        
        if(isset($_POST['submit'])){
            //Если форма отправлена
            //Получаем данные из формы
            $name = $_POST['name'];
            $sortOrder = $_POST['sort_order'];
            $status = $_POST['status'];
            
            //флаг ошибки в форме
            $errors = false;
            
            //При необходимости можно валидировать значения нужным образом
            if(!isset($name) || empty($name)){
                $errors[] = 'Заполните поля';
            }
            
            if($errors == false){
                //Если нет ошибок
                if(Category::createCategory($name, $sortOrder, $status))
                        header("Location: /admin/category/");
            }
        }
        require_once(ROOT . '/views/admin_category/create.php');
        return true;
    }
    
    public function actionUpdate($arr){
        $id = $arr[0];
        
        self::checkAdmin();
        
        //Получаем данные о конкретной категории
        $category = Category::getCategoryById($id);
        
        if(isset($_POST['submit'])){
            $name = $_POST['name'];
            $sortOrder = $_POST['sort_order'];
            $status = $_POST['status'];
            
            //Сохраняем изменения
            if(Category::updateCategoryById($id, $name, $sortOrder, $status))
                    header("Location: /admin/category");
        }
        require_once(ROOT . '/views/admin_category/update.php');
    }
    
    public function actionDelete($arr){
        self::checkAdmin();
        
        $id = $arr[0];
        if((isset($_POST['submit']))){
            
            if(Category::deleteCategoryById($id))
                header("Location: /admin/category");
        }
        require_once(ROOT . '/views/admin_category/delete.php');
    }
}
