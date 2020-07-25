<?php

namespace AndriiMz\FormScenario\Mutation;

interface ScenarioMutationInterface
{
    public const PRE_PERSIST = 'pre_persist';
    public const PRE_FLUSH = 'pre_flush';

    public const TAG = 'form-scenario.mutation';

    /**
     * @param string $type
     * @param $entity
     * @param array $context
     *
     * @return boolean
     */
    public function accepts(string $type, $entity, $context = []): bool;

    /**
     * @param string $type
     * @param $entity
     * @param array $context
     *
     * @return mixed
     */
    public function mutate(string $type, $entity, $context = []);
}
