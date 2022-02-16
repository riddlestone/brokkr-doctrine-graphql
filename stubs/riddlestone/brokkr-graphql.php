<?php

namespace Riddlestone\Brokkr\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Laminas\ServiceManager\AbstractPluginManager;

/**
 * Need this until GraphQLTypeManager declares it extends AbstractPluginManager<Type>
 * 
 * @extends AbstractPluginManager<Type>
 */
class GraphQLTypeManager extends AbstractPluginManager {}
