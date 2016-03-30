<?php
class Category{
    /*Return an array of category*/
    
    public static function getCategoriesList(){
        try{
            $db = Db::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $category = [];
            $result = $db->query('SELECT id, name FROM category
                                    ORDER BY sort_order ASC');
            $i = 0;
            while($row = $result->fetch()){
                $category[$i]['id'] = $row['id'];
                $category[$i]['name'] = $row['name'];
                $i++;
            }
            return $category;
        }catch(PDOException $e){
            echo $e->getMessage();
            echo 'category ERROR';
        }
    }
    
    /**
     * Возвращает массив категорий<br>
     * @return array <p>Массив категорий</p>
     */
    public static function getCategoriesListAdmin(){
        try {
            $db = Db::getConnection();
            $result = $db->query('SELECT id, name, sort_order, status FROM category ORDER BY id ASC');
            
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            echo $e->getMessage();
            echo 'getCategoriesListAdmin ERROR';
        }
    }
    
    public static function createCategory($name, $sortOrder, $status){
        try {
            $db = Db::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = 'INSERT INTO category (name, sort_order, status) '
                    . 'VALUES(:name, :sort_order, :status)';
            
            $result = $db->prepare($sql);
            $result->bindParam(':name', $name, PDO::PARAM_STR);
            $result->bindParam(':sort_order', $sortOrder, PDO::PARAM_INT);
            $result->bindParam(':status', $status, PDO::PARAM_INT);
                    
            return $result->execute();
        } catch (Exception $ex) {
            echo $e->getMessage();
            echo 'createCategory ERROR';
        }
    }
    
    public static function getCategoryById($id){
        try{
            $db = Db::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $result = $db->query('SELECT id, name, sort_order, status FROM category WHERE id=' . $id);
            
            return $result->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $ex){
            $ex->getMessage();
            echo 'getCategoryById ERROR';
        }
    }
    
    public static function updateCategoryById($id, $name, $sortOrder, $status){
        try{
            $db = Db::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = 'UPDATE category SET '
                    . 'name = :name, '
                    . 'sort_order = :sort_order, '
                    . 'status = :status '
                    . 'WHERE id = :id';
            $result = $db->prepare($sql);
            $result->bindParam(':id', $id, PDO::PARAM_INT);
            $result->bindParam(':name', $name, PDO::PARAM_INT);
            $result->bindParam(':sort_order', $sortOrder, PDO::PARAM_INT);
            $result->bindParam(':status', $status, PDO::PARAM_INT);
            
            return $result->execute();
        } catch (Exception $ex) {
            $ex->getMessage();
            echo 'updateCategoryById ERROR';
        }
    }
    
    public static function deleteCategoryById($id){
        try{
            $db = Db::getConnection();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = 'DELETE FROM category WHERE id = :id';
            
            $result = $db->prepare($sql);
            $result->bindParam(':id', $id, PDO::PARAM_INT);
            return $result->execute();            
        } catch (Exception $ex) {
            $ex->getMessage();
            echo 'deleteCategoryById ERROR';
        }
    }

}
?>