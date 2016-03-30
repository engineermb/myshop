<?php
include_once ROOT . '/models/Category.php';
include_once ROOT . '/models/Product.php';
include_once ROOT . '/components/Pagination.php';
class CatalogController{
    public function actionIndex(){
        $categories = [];
        $categories = Category::getCategoriesList();
        
        $latestProducts = [];
        $latestProducts = Product::getLatestProducts();
        //var_dump($categories);
        require_once(ROOT . '/views/catalog/index.php');
        return true;
    }
    
    public function actionCategory($params){
        $categoryId = $params[0];
       
        if(count($params) > 1){
            $page = $params[1];
        }else{
            $page = 1;
        }
      
        $categories = [];
        //var_dump($categories = []);
        $categories = Category::getCategoriesList();
        $categoriesProduct = Product::getProductListByCategory($categoryId, $page);
        //$total = Product::getTotalProductsInCategory($categoryId);
        
        //$pagination = new Pagination($total, $page, Product::SHOW_BY_DEFAULT, 'page-');
        
        require_once(ROOT . '/views/catalog/category.php');
        return true;
    }
}
?>