<?php
/**
 * Class to Handle Requests
 */
class Request
{
    /**
     * function to grab the uri
     *
     * @return void
     */
    public static function uri()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
    /**
     * function to grab the method
     *
     * @return void
     */
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    /**
     * function to grab the query params
     *
     * @return void
     */
    public static function params()
    {
        return $_SERVER['QUERY_STRING'] ?? null;
    }
}
