<?php


namespace Nitixx\Controllers;


use LeagueWrap\Api;
use LeagueWrap\CacheInterface;

class ApiManager
{

    /**
     * @var Api The initialised API
     */
    private static $instance;

    public static function getAPI()
    {
        if (static::$instance == null) {
            $config =  include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config'. DIRECTORY_SEPARATOR . 'api.php';

            static::$instance = new Api($config['key']);
            static::$instance->remember(null, new Cache());
            static::$instance->attachStaticData(true);
        }
        return static::$instance;
    }

    /**
     * Private constructor
     */
    private function __construct()
    {
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }
}

class Cache implements CacheInterface
{
    /**
     * @inheritDoc
     */
    public function set($response, $key, $seconds)
    {
        apc_store($key, $response, $seconds);
    }

    /**
     * @inheritDoc
     */
    public function has($key)
    {
        return apc_exists($key);
    }

    /**
     * @inheritDoc
     */
    public function get($key)
    {
        return apc_fetch($key);
    }

}