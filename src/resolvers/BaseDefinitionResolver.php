<?php

namespace dmirogin\fakeme\resolvers;

/**
 * Resolve definitions for specified class
 */
abstract class BaseDefinitionResolver implements Resolver
{
    /**
     * @var array
     */
    protected $definitions;

    /**
     * Set definitions data
     *
     * @param array $definitions
     * @return BaseDefinitionResolver For chaining
     */
    public function setDefinitions(array $definitions): self
    {
        $this->definitions = $definitions;

        return $this;
    }

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
