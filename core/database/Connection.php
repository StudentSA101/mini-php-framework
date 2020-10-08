<?php

use Illuminate\Database\Capsule\Manager;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
/**
 * Database Connection
 */
class Connection 
{
    /**
     * Instance of Manager
     *
     * @var Manager $capsule
     */
    private $capsule;

    /**
     * Connects to database ORM
     *
     * @param Manager $database
     */
    public function __construct(Manager $capsule)
    {
        $this->capsule = $capsule;
    }
    /**
     * Returns an instance of eloquent
     * @return Manager
     **/
    public function setup()
    {
        try {
            $this->capsule->addConnection([
                'driver'    => env('DB_DRIVER'),
                'host'      => env('DB_HOST'),
                'database'  => env('DB_DATABASE'),
                'username'  =>  env('DB_USERNAME'),
                'password'  =>  env('DB_PASSWORD'),
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix'    => '',
            ]);
            // Set the event dispatcher used by Eloquent models... (optional)
            $this->capsule->setEventDispatcher(new Dispatcher(new Container));
            // Make this Capsule instance available globally via static methods... (optional)
            $this->capsule->setAsGlobal('database');
            // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
            $this->capsule->bootEloquent();
        } catch (Exception $e) {
            // handle the exception here
        }
        return $this->capsule;   
    }
}