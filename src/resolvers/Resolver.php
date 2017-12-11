<?php

namespace dmirogin\fakeme\resolvers;

interface Resolver
{
    /**
     * Get definitions data
     *
     * @return array
     */
    public function getDefinitions(): array;

    /**
     * Set definitions data
     *
     * @param array $definitions
     * @return Resolver For chaining
     */
    public function setDefinitions(array $definitions): self;

    /**
     * Resolve definitions for class
     *
     * @param string $className Full name of class
     * @return array
     */
    public function resolve(string $className): array;
}
