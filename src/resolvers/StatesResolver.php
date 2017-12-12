<?php

namespace dmirogin\fakeme\resolvers;

use yii\helpers\ArrayHelper;

class StatesResolver extends Resolver
{
    /**
     * @inheritdoc
     */
    public function resolve(string $className, array $parameters = []): array
    {
        $ret = [];

        $modelStates = $this->definitions[$className];
        foreach ($parameters as $state) {
            if (array_key_exists($state, $modelStates)) {
                $stateDefinition = $this->uncoverFields($modelStates[$state]);
                $ret = ArrayHelper::merge($ret, $stateDefinition);
            }
        }

        return $ret;
    }
}
