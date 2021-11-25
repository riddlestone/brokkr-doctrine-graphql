<?php

namespace Riddlestone\Brokkr\DoctrineGraphQL\Test\Unit;

use Riddlestone\Brokkr\DoctrineGraphQL\ConfigProvider;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Riddlestone\Brokkr\DoctrineGraphQL\ConfigProvider
 */
class ConfigProviderTest extends TestCase
{
    /**
     * @covers \Riddlestone\Brokkr\DoctrineGraphQL\ConfigProvider::__invoke
     */
    public function test__invoke()
    {
        $config = (new ConfigProvider())();
        $this->assertArrayHasKey('graphql_fields', $config);
        $this->assertIsArray($config['graphql_fields']);
        $this->assertArrayHasKey('graphql_types', $config);
        $this->assertIsArray($config['graphql_types']);
    }

    /**
     * @covers \Riddlestone\Brokkr\DoctrineGraphQL\ConfigProvider::getGraphQLFieldConfig
     */
    public function testGetGraphQLFieldConfig()
    {
        $config = (new ConfigProvider())->getGraphQLFieldConfig();
        $this->assertArrayHasKey('abstract_factories', $config);
        $this->assertIsArray($config['abstract_factories']);
    }

    /**
     * @covers \Riddlestone\Brokkr\DoctrineGraphQL\ConfigProvider::getGraphQLTypeConfig
     */
    public function testGetGraphQLTypeConfig()
    {
        $config = (new ConfigProvider())->getGraphQLTypeConfig();
        $this->assertArrayHasKey('abstract_factories', $config);
        $this->assertIsArray($config['abstract_factories']);
    }
}
