<?php
class Product{
    const SHOW_BY_DEFAULT = 2;
    
    /**Return an array of product**/
    public static function getLatestProducts($count = self::SHOW_BY_DEFAULT, $page = 1){
        try{
            $count = intval($count);
            $db = Db::getConnection();
            $productList = [];
            $result = $db->query('SELECT id, name, price, image, is_new
                                    FROM product
                                    WHERE status = "1"
                                    ORDER BY id DESC
                                    LIMIT ' . $count );
            $i = 0;
            while($row = $result->fetch()){
                $productList[$i]['id'] = $row['id'];
                $productList[$i]['name'] = $row['name'];
                $productList[$i]['image'] = $row['image'];
                $productList[$i]['price'] = $row['price'];
                $productList[$i]['is_new'] = $row['is_new'];
                $i++;
            }
            
            return $productList;
        }catch(PDOException $e){
            $e->getMessage();
            echo 'product ERROR';
        }
    }
    
    public static function getRecomendedProducts(){
        try {
            $db = Db::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = 'SELECT id, name, price, image, is_new
                                    FROM product
                                    WHERE status = "1" AND is_recommended = "1"
                                    ORDER BY id DESC';
            $result = $db->query($sql);
            
            $productList = $result->fetchAll(PDO::FETCH_ASSOC);
            return $productList;
            
        } catch (Exception $ex) {
            echo $ex->getMessage();
            echo 'recomendedProduct ERRO';
        }
    }
    
    public static function getProductListByCategory($categoryId = false, $page = 1){
        if($categoryId){
            try{
                
                $page = intval($page);
                $offset = ($page - 1) * self::SHOW_BY_DEFAULT;
                $db = Db::getConnection();
                $products = [];
                $result = $db->query("SELECT id, name, price, image, is_new
                                        FROM product
                                        WHERE status = '1' AND category_id = '$categoryId'
                                        ORDER BY id ASC
                                        LIMIT ". self::SHOW_BY_DEFAULT
                                        . " OFFSET " . $offset);
                                        
                $i = 0;
                while($row = $result->fetch()){
                    $products[$i]['id'] = $row['id'];
                    $products[$i]['name'] = $row['name'];
                    $products[$i]['image'] = $row['image'];
                    $products[$i]['price'] = $row['price'];
                    $products[$i]['is_new'] = $row['is_new'];
                    $i++;
                }
                
                return $products;
            }catch(PDOException $e){
                echo $e->getMessage();
                echo 'categoryId ERROR';
            } 
        }
    }
    
    public static function getProductById($productId){
        if($productId){
            try{
                $db = Db::getConnection();
                $products = [];
                $result = $db->query("SELECT id, name, code, price, category_id, brand, image, availability,
                                        description, image, is_new, description, is_new, is_recommended, status
                                        FROM product
                                        WHERE id=". $productId);
                
                return $products = $result->fetch(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                echo $e->getMessage();
                echo 'product ERRO';
            }
        }
    }
    
    public static function getTotalProductsInCategory($categoryId){
        if($categoryId){
            try{
                $db = Db::getConnection();
                
                $result = $db->query("SELECT count(id) AS count
                                        FROM product
                                        WHERE status ='1' AND category_id=". $categoryId);
                $row = $result->fetch(PDO::FETCH_ASSOC);
                return $row;
            }catch(PDOException $e){
                echo $e->getMessage();
                echo 'product ERRO';
            }
        }
    }
    
    public static function getProductsByIds($idsArr){
        if($idsArr){
            try{
                $products = [];
                
                $db = Db::getConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $idsString = implode(',', $idsArr);
                
                $sql = "SELECT * FROM product WHERE status='1' AND id IN ($idsString)";
                
                $result = $db->query($sql);
                
                $i = 0;
                while($row = $result->fetch(PDO::FETCH_ASSOC)){
                    $products[$i]['id'] = $row['id'];
                    $products[$i]['code'] = $row['code'];
                    $products[$i]['name'] = $row['name'];
                    $products[$i]['price'] = $row['price'];
                    $i++;
                }
                
                return $products;
            }catch(PDOException $e){
                echo $e->getMessage();
                echo 'productByIds ERRO';
            }
        }        
    }
    
    public static function getProductsList(){
        try {
             $db = Db::getConnection();
             $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             
             $result = $db->query('SELECT id, name, price, code FROM product ORDER BY id ASC');
             return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            echo $e->getMessage();
            echo 'getProductList ERRO';
        }
    }
    
    
    /**
     * Удаляет товар с указаным id
     * @param integer $id <p>id товара<p>
     * @return boolean <p>Результат: выполнения метода</p>
     */
    public static function deleteProductById($id){
        try{
            $db = Db::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = 'DELETE FROM product WHERE id = :id';
            
            $result = $db->prepare($sql);
            $result->bindParam(':id', $id, PDO::PARAM_INT);
            return $result->execute();
        } catch (Exception $ex) {
            echo $e->getMessage();
            echo 'deleteProduct ERRO'; 
        }
    }
    
    /**
     * Добавляет новый товар
     * @param array $options <p>Массив с инф. о товаре</p>
     * @return ineger <p>id добавленной в таблицу записи</p>
     */
    public static function createProduct($options){
        try{
            $db = Db::getConnection();
            
            $sql = 'INSERT INTO product (name, code, price, category_id, brand,'
                    . 'availability, description, is_new, is_recommended, status)'
                    . ' VALUES(:name, :code, :price, :category_id, :brand,'
                    . ':availability, :description, :is_new, :is_recommended, :status)';
            
            $result = $db->prepare($sql);
            $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
            $result->bindParam(':code', $options['code'], PDO::PARAM_STR);
            $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
            $result->bindParam(':category_id', $options['category_id'], PDO::PARAM_INT);
            $result->bindParam(':brand', $options['brand'], PDO::PARAM_STR);
            $result->bindParam(':availability', $options['availability'], PDO::PARAM_INT);
            $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
            $result->bindParam(':is_new', $options['is_new'], PDO::PARAM_INT);
            $result->bindParam(':is_recommended', $options['is_recommended'], PDO::PARAM_INT);
            $result->bindParam(':status', $options['status'], PDO::PARAM_INT);
            
            if($result->execute()){
                //Если запрос выполнен успешно, возвращаем id добавленной записи
                return $db->lastInsertId();
            }
            return 0;
        } catch (Exception $ex) {
            echo $ex->getMessage();
            echo 'createProduct ERRO'; 
        }
    }
    
    public static function updateProductById($id, $options){
        try{
            $db = Db::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'UPDATE product '
                    . 'SET '
                    . 'name = :name, '
                    . 'code = :code, '
                    . 'price = :price, '
                    . 'category_id = :category_id, '
                    . 'brand = :brand, '
                    . 'availability = :availability, '
                    . 'description = :description, '
                    . 'is_new = :is_new, '
                    . 'is_recommended = :is_recommended, '
                    . 'status = :status '
                    . 'WHERE id = :id';

            $result = $db->prepare($sql);
            $result->bindParam(':id', $id, PDO::PARAM_INT);
            $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
            $result->bindParam(':code', $options['code'], PDO::PARAM_STR);
            $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
            $result->bindParam(':category_id', $options['category_id'], PDO::PARAM_INT);
            $result->bindParam(':brand', $options['brand'], PDO::PARAM_STR);
            $result->bindParam(':availability', $options['availability'], PDO::PARAM_INT);
            $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
            $result->bindParam(':is_new', $options['is_new'], PDO::PARAM_INT);
            $result->bindParam(':is_recommended', $options['is_recommended'], PDO::PARAM_INT);
            $result->bindParam(':status', $options['status'], PDO::PARAM_INT);
            return $result->execute(); 
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        
    }
    
    public static function getTotalPrice($productQuantity, $products){
                if($products){
            if($productQuantity){
                $total = 0;
                foreach ($products as $item){
                    $total +=$item['price'] * $productQuantity[$item['id']];
                }
            }
            return $total;
        } 
    }
    
    public static function getImage($id){
        //Название изображения пустышки
        $noImage = 'no-image.png';
        
        //Путь к папке с товарами
        $path = '/upload/images/products/';
        
        //Путь к изображению товара
        $pathToProductImage = $path . $id . '.png';
        
        if(file_exists($_SERVER['DOCUMENT_ROOT'] . $pathToProductImage)){
            //Если изображение для товара существует
            //Возвращаем путь изображения товара
            return $pathToProductImage;
        }
        return $path . $noImage;
    }
    
}
?>