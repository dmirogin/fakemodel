<?php

namespace tests\data;

use dmirogin\fakemodel\resolvers\Resolver;

class TestResolver implements Resolver
{
    /**
     * @inheritdoc
     */
    public function resolve(string $className, array $states = []): array
    {
        return [
            'field' => 42,
            'title' => !empty($states) ? 'states' : 'title'
        ];
    }
}
