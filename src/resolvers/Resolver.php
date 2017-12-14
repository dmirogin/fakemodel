<?php

namespace dmirogin\fakeme\resolvers;

interface Resolver
{
    /**
     * Resolve definitions for class
     *
     * @param string $className Full name of class
     * @param array $states
     * @return array
     */
    public function resolve(string $className, array $states = []): array;
}
