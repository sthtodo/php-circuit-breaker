<?php

namespace Ejsmont\CircuitBreaker\Storage\Adapter;

class ApcuAdapter extends BaseAdapter
{
    /**
     * Configure instance
     *
     * @param Integer $ttl          How long should circuit breaker data persist (between updates)
     * @param String  $cachePrefix  Value has to be string. If empty default cache key prefix is used.
     */
    public function __construct($ttl = 3600, $cachePrefix = false) {
        parent::__construct($ttl, $cachePrefix);
    }

    /**
     * Helper method to make sure that extension is loaded (implementation dependent)
     *
     * @throws Ejsmont\CircuitBreaker\Storage\StorageException if extension is not loaded
     * @return void
     */
    protected function checkExtension()
    {
        if (!function_exists("apcu_store")) {
            throw new StorageException("APCu extension not loaded.");
        }
    }

    /**
     * Loads item by cache key.
     *
     * @param string $key
     * @return mixed
     *
     * @throws Ejsmont\CircuitBreaker\Storage\StorageException if storage error occurs, handler can not be used
     */
    protected function load($key)
    {
        return apcu_fetch($key);
    }

    /**
     * Save item in the cache.
     *
     * @param string $key
     * @param string $value
     * @param int $ttl
     * @return void
     *
     * @throws Ejsmont\CircuitBreaker\Storage\StorageException if storage error occurs, handler can not be used
     */
    protected function save($key, $value, $ttl)
    {
        $result = apcu_store($key, $value, $ttl);
        if ($result === false) {
            throw new StorageException("Failed to save apcu key: $key");
        }
    }
}