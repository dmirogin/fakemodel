<?php

namespace tests\resolvers;

use dmirogin\fakemodel\resolvers\StatesResolver;
use PHPUnit\Framework\TestCase;
use tests\data\BaseModel;

class StatesResolverTest extends TestCase
{
    public function testResolve()
    {
        $resolver = new StatesResolver();
        $resolver->setDefinitions([
            BaseModel::class => [
                'default' => [
                    'field' => 42,
                    'title' => function () {
                        return 'string';
                    }
                ]
            ]
        ]);

        $result = $resolver->resolve(BaseModel::class, ['default']);

        $this->assertSame(42, $result['field']);
        $this->assertSame('string', $result['title']);
    }
}
