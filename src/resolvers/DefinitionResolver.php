<?php

namespace dmirogin\fakeme\resolvers;

use Faker\Factory;

class DefinitionResolver
{
    /**
     * @var array
     */
    private $definitions;

    /**
     * @return array
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    /**
     * @param array $definitions
     * @return DefinitionResolver
     */
    public function setDefinitions(array $definitions): self
    {
        $this->definitions = $definitions;

        return $this;
    }

    /**
     * Resolve definitions for class
     *
     * @param string $className
     * @return array|mixed
     */
    public function resolve(string $className)
    {
        $fakeAttributes = [];

        $modelDefinitions = $this->definitions[$className];
        if ($modelDefinitions !== null) {
            if (\is_array($modelDefinitions)) {
                $fakeAttributes = $modelDefinitions;
            } elseif (\is_callable($modelDefinitions)) {
                $fakeAttributes = $this->getDataFromCallable($modelDefinitions);
            }
        }

        $fakeAttributes = array_merge($fakeAttributes, $this->getRelationAttributes($fakeAttributes));

        return $fakeAttributes;
    }

    /**
     * Get data from callable, injecting faker generator
     *
     * @param callable $modelDefineCallback
     * @return array
     */
    protected function getDataFromCallable(callable $modelDefineCallback): array
    {
        $faker = Factory::create();
        return $modelDefineCallback($faker);
    }

    /**
     * Get array of attributes that declared as callback and perform them
     *
     * @param array $attributes
     * @return array
     */
    protected function getRelationAttributes(array $attributes): array
    {
        $ret = [];

        foreach ($attributes as $key => $value) {
            if (($primaryKey = $this->getDataFromFieldCallable($value)) !== null) {
                $ret[$key] = $primaryKey;
            }
        }

        return $ret;
    }

    /**
     * Get value from field callback
     *
     * @param $value
     * @return mixed
     */
    private function getDataFromFieldCallable($value)
    {
        if (\is_callable($value)) {
            return $value();
        }

        return null;
    }
}
