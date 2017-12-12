<?php

namespace dmirogin\fakeme\resolvers;

/**
 * Resolve definitions for specified class
 */
abstract class Resolver
{
    /**
     * @var array
     */
    protected $definitions;

    /**
     * Get definitions data
     *
     * @return array
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    /**
     * Set definitions data
     *
     * @param array $definitions
     * @return Resolver For chaining
     */
    public function setDefinitions(array $definitions): self
    {
        $this->definitions = $definitions;

        return $this;
    }

    /**
     * Resolve definitions for class
     *
     * @param string $className Full name of class
     * @param array $parameters
     * @return array
     */
    abstract public function resolve(string $className, array $parameters = []): array;

    /**
     * Go through array and call callable fields
     *
     * @param array $data
     * @return array
     */
    protected function uncoverFields(array $data): array
    {
        foreach ($data as $key => $value) {
            if (\is_callable($value)) {
                $data[$key] = $value();
            }
        }

        return $data;
    }
}
