<?php

namespace dmirogin\fakeme;

use dmirogin\fakeme\resolvers\DefinitionResolver;
use yii\base\BaseObject;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class ModelBuilder extends BaseObject
{
    /**
     * @var DefinitionResolver
     */
    private $definitionResolver;

    /**
     * @var string
     */
    private $className;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->definitionResolver = new DefinitionResolver();
    }

    /**
     * Create the model with attributes
     *
     * @param array|null $attributes
     * @return Model
     */
    public function make(?array $attributes = []): Model
    {
        /** @var Model $model */
        $model = new $this->className;

        $fakeAttributes = $this->definitionResolver->resolve($this->className);

        $model->setAttributes(ArrayHelper::merge($fakeAttributes, $attributes), false);

        return $model;
    }

    /**
     * Create and persist in db the model with attributes
     *
     * @param array|null $attributes
     * @return ActiveRecord
     */
    public function create(?array $attributes = []): ActiveRecord
    {
        $this->abortIfNotActiveRecord();

        /** @var ActiveRecord $model */
        $model = $this->make($attributes);

        $model->save(false);

        return $model;
    }

    /**
     * Throw an error if model is not activeRecord
     *
     * @throws \InvalidArgumentException
     */
    private function abortIfNotActiveRecord(): void
    {
        if (!is_subclass_of($this->className, ActiveRecord::class)) {
            throw new \InvalidArgumentException('Model must be ActiveRecord');
        }
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     * @return ModelBuilder
     */
    public function setClassName($className): self
    {
        $this->className = $className;

        return $this;
    }

    /**
     * @return array
     */
    public function getDefines(): array
    {
        return $this->definitionResolver->getDefinitions();
    }

    /**
     * @param array $defines
     * @return ModelBuilder
     */
    public function setDefines(array $defines): self
    {
        $this->definitionResolver->setDefinitions($defines);

        return $this;
    }
}
