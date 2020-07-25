<?php

namespace AndriiMz\FormScenario\Mutation;

class MutationManager
{
    /**
     * @var ScenarioMutationInterface[]
     */
    private $mutations = [];

    public function setMutation(ScenarioMutationInterface $mutation)
    {
        $this->mutations[] = $mutation;
    }

    /**
     * @param string $type
     * @param $entity
     * @param array $context
     *
     * @return mixed
     */
    public function mutate(string $type, $entity, $context = [])
    {
        foreach ($this->mutations as $mutation) {
            if ($mutation->accepts($type, $entity, $context)) {
                $entity = $mutation->mutate($type, $entity, $context);
            }
        }

        return $entity;
    }
}
