<?php
include_once ROOT . '/models/Category.php';
include_once ROOT . '/models/Product.php';

class ProductController{
    public function actionView($arr){
        $productId = $arr[0];
        $categories = [];
        $categories = Category::getCategoriesList();
        
        $product = [];
        $product = Product::getProductById($productId);
        //print_r($product);
        //$productList = Product::getLatestProducts();
        require_once(ROOT . '/views/product/view.php');
    }
}
?>