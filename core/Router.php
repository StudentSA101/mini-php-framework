<?php



/**
 * Class that controls the routing of the framework
 */
class Router
{
    /**
     * Routes array with the verbs that can be used for routes
     *
     * @var array $routes
     */
    public $routes = [
        'POST' => [],
        'GET' => [],
        'PUT' => [],
        'DEL' => [],
    ];
    /**
     * Static Function to load up routes.
     *
     * @param string $file
     * @return string
     */
    public static function load(string $file)
    {
        $router = new static;

        require __DIR__ . $file;

        return $router;
    }
    /**
     * Post Verb
     *
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function post($uri, $controller)
    {
        $this->routes['POST'][$uri] = $controller;
    }
    /**
     * Get Verb
     *
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function get($uri, $controller)
    {
        $this->routes['GET'][$uri] = $controller;
    }
    /**
     * Get put
     *
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function put($uri, $controller)
    {
        $this->routes['PUT'][$uri] = $controller;
    }
    /**
     * Get del
     *
     * @param string $uri
     * @param string $controller
     * @return void
     */

    public function del($uri, $controller)
    {
        $this->routes['DEL'][$uri] = $controller;
    }

    /**
     * Resolve uri to controller
     *
     * @param string $uri
     * @param string $requestType
     * @return string
     */
    public function direct($uri, $requestType, $params)
    {

        if (array_key_exists($uri, $this->routes[$requestType])) {
            $route = explode('@', $this->routes[$requestType][$uri]);
            return $this->callAction(
                $route[0],$route[1],$params
            );
        }
        return $this->callAction(
            ...explode('@', $this->routes[$requestType]['/*'])
        );

    }
    /**
     * Function to call controller class method
     *
     * @param string $controller
     * @param string $action
     * @return object
     */
    protected function callAction($controller, $action, $params = null)
    {

        $controller = 'App\\Controllers\\' . $controller;

        $controller = new $controller;

        if (!method_exists($controller, $action)) {
            throw new Exception(
                "$controller does not responsed to the $action action"
            );
        }

        return $controller->$action($params);
    }
}
