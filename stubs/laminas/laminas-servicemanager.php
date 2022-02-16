<?php

namespace Laminas\ServiceManager;

use Laminas\ServiceManager\Exception\InvalidServiceException;

/**
 * Need this until AbstractPluginManager support generics itself
 *
 * @template T
 */
abstract class AbstractPluginManager extends ServiceManager implements PluginManagerInterface
{
    /**
     * @var null|class-string<T>
     */
    protected $instanceOf;

    /**
     * @param string $name
     * @param T $service
     */
    public function setService($name, $service) {}

    /**
     * @param string $name Service name of plugin to retrieve.
     * @param null|array<mixed> $options Options to use when creating the instance.
     * @return T
     * @throws Exception\ServiceNotFoundException
     * @throws InvalidServiceException
     */
    public function get($name, ?array $options = null) {}
}
