<?php

namespace tests\resolvers;

use dmirogin\fakeme\resolvers\FakerResolver;
use tests\data\BaseModel;

class FakerResolverTest extends \PHPUnit\Framework\TestCase
{
    public function testResolveCallable()
    {
        $resolver = new FakerResolver();
        $resolver->setDefinitions([
            BaseModel::class => function () {
                return [
                    'field' => 42,
                    'title' => function () {
                        return 'string';
                    }
                ];
            }
        ]);

        $result = $resolver->resolve(BaseModel::class);

        $this->assertSame(42, $result['field']);
        $this->assertSame('string', $result['title']);
    }

    public function testResolveArray()
    {
        $resolver = new FakerResolver();
        $resolver->setDefinitions([
            BaseModel::class => [
                'field' => 42,
                'title' => function () {
                    return 'string';
                }
            ]
        ]);

        $result = $resolver->resolve(BaseModel::class);

        $this->assertSame(42, $result['field']);
        $this->assertSame('string', $result['title']);
    }
}
