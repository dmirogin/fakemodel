<?php

namespace dmirogin\fakemodel\resolvers;

use Faker\Factory;

class FakerResolver extends BaseDefinitionResolver
{
    /**
     * @inheritdoc
     */
    public function resolve(string $className, array $states = []): array
    {
        $fakeAttributes = [];

        $modelDefinitions = $this->definitions[$className];
        if ($modelDefinitions !== null) {
            if (\is_array($modelDefinitions)) {
                $fakeAttributes = $modelDefinitions;
            } elseif (\is_callable($modelDefinitions)) {
                $fakeAttributes = $this->getFakedAttributes($modelDefinitions);
            }
        }

        $fakeAttributes = array_merge($fakeAttributes, $this->uncoverFields($fakeAttributes));

        return $fakeAttributes;
    }

    /**
     * Get data from callable, injecting faker generator
     *
     * @param callable $modelDefineCallback
     * @return array
     */
    protected function getFakedAttributes(callable $modelDefineCallback): array
    {
        $faker = Factory::create();
        return $modelDefineCallback($faker);
    }
}
