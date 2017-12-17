<?php

namespace dmirogin\fakemodel\resolvers;

use yii\helpers\ArrayHelper;

class StatesResolver extends BaseDefinitionResolver
{
    /**
     * @inheritdoc
     */
    public function resolve(string $className, array $states = []): array
    {
        $ret = [];

        $modelStates = $this->definitions[$className];
        foreach ($states as $state) {
            if (array_key_exists($state, $modelStates)) {
                $stateDefinition = $this->uncoverFields($modelStates[$state]);
                $ret = ArrayHelper::merge($ret, $stateDefinition);
            }
        }

        return $ret;
    }
}
