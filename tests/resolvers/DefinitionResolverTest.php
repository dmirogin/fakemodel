<?php

namespace tests\resolvers;

use dmirogin\fakeme\resolvers\DefinitionResolver;
use Faker\Generator;
use tests\data\BaseModel;

class DefinitionResolverTest extends \PHPUnit\Framework\TestCase
{
    public function testResolveFunction()
    {
        $resolver = $this->getResolverWithDefinitions(
            function (Generator $faker) {
                return [
                    'title' => $faker->word,
                    'text' => $faker->text
                ];
            }
        );

        $result = $resolver->resolve(BaseModel::class);

        $this->assertNotEmpty($result['title']);
        $this->assertNotEmpty($result['text']);
    }

    public function testResolveArray()
    {
        $resolver = $this->getResolverWithDefinitions(
            [
                'title' => 'title'
            ]
        );

        $result = $resolver->resolve(BaseModel::class);

        $this->assertSame('title', $result['title']);
    }

    public function testResolveArrayAndRelation()
    {
        $resolver = $this->getResolverWithDefinitions(
            [
                'title' => function () {
                    return 'title';
                }
            ]
        );

        $result = $resolver->resolve(BaseModel::class);

        $this->assertSame('title', $result['title']);
    }

    public function testResolveFunctionAndRelation()
    {
        $resolver = $this->getResolverWithDefinitions(
            function (Generator $faker) {
                return [
                    'title' => function () {
                        return 'title';
                    },
                ];
            }
        );

        $result = $resolver->resolve(BaseModel::class);

        $this->assertSame('title', $result['title']);
    }

    private function getResolverWithDefinitions($data)
    {
        $resolver = new DefinitionResolver();
        $resolver->setDefinitions([
            BaseModel::class => $data
        ]);

        return $resolver;
    }
}
