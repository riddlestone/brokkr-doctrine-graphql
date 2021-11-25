<?php

namespace Riddlestone\Brokkr\DoctrineGraphQL\Test\Unit;

use Riddlestone\Brokkr\DoctrineGraphQL\Module;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Riddlestone\Brokkr\DoctrineGraphQL\Module
 */
class ModuleTest extends TestCase
{
    /**
     * @covers \Riddlestone\Brokkr\DoctrineGraphQL\Module::getConfig
     */
    public function testGetConfig()
    {
        $module = new Module();
        $config = $module->getConfig();
        $this->assertArrayHasKey('graphql_fields', $config);
        $this->assertIsArray($config['graphql_fields']);
        $this->assertArrayHasKey('graphql_types', $config);
        $this->assertIsArray($config['graphql_types']);
    }
}
