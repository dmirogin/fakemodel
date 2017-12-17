<?php

namespace tests\data;

use dmirogin\fakemodel\resolvers\Resolver;

class SecondTestResolver implements Resolver
{

    /**
     * @inheritdoc
     */
    public function resolve(string $className, array $states = []): array
    {
        return [
            'field' => 42,
            'title' => 'inherited'
        ];
    }
}
