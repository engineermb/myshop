<?php
class Router{
	private $routes;
	public function __construct(){
	   $routesPath = ROOT . '/config/routes.php';
       $this->routes = include($routesPath);
	}
    
    /*Return request string*/
    private function getURI(){
        if(!empty($_SERVER['REQUEST_URI'])){
            return trim($_SERVER['REQUEST_URI'], '/');
        }       
    }
    
	public function run(){
        //Get request string
        $uri = $this->getURI();
        //Проверка наличия такого запроса в routes.php
        foreach($this->routes as $uriPattern => $path){
            //Сравниваем $uriPattern с $uri
            if(preg_match("~$uriPattern~", $uri)){
                
                //echo '<br> Запрос, который набрли: ' . $uri;
                //echo '<br> Что ищем: ' . $uriPattern;
                //echo '<br> Кто обрабатывает: ' . $path;
                //Получаем внутренний путь из внешнего согласно правилу
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);
                
                //echo '<br><br>Нужно сформировать: ' . $internalRoute;
                
                
                //Опредеяем контролер, экш, параметры
                $segments = explode('/', $internalRoute);
                $controllerName = array_shift($segments) . 'Controller';
                //echo $controllerName. '<br>';
                $controllerName = ucfirst($controllerName);
                //echo $controllerName. '<br>';
                $actionName = 'action' . ucfirst(array_shift($segments));
                //echo $actionName. '<br>';
                
                $params = $segments;
                //print_r($params);
                
                $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';
                if(file_exists($controllerFile)){
                    include_once($controllerFile);
                }
                
                //Создаем обьект, вызываем метод (т. е. action)
                $controllerObject = new $controllerName;
                //$result = $controllerObject->$actionName($params);
                //$result = call_user_func_array(array($controllerObject, $actionName), $params);
                //var_dump($result);
                $controllerObject->$actionName($params);
                if($controllerObject != null){
                    break;
                }
            }
        }
    }
        
}
?>