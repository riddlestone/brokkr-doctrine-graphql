<?php

namespace Riddlestone\Brokkr\DoctrineGraphQL;

class Module
{
    public function getConfig(): array
    {
        return (new ConfigProvider())();
    }
}
