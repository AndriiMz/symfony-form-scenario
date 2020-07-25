<?php

namespace AndriiMz\FormScenario\DependencyInjection;

use AndriiMz\FormScenario\Mutation\MutationManager;
use AndriiMz\FormScenario\Mutation\ScenarioMutationInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ChangeDispatcherCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $context = $container->findDefinition(MutationManager::class);
        $taggedServices = $container->findTaggedServiceIds(ScenarioMutationInterface::TAG);
        $taggedServiceIds = array_keys($taggedServices);
        foreach ($taggedServiceIds as $taggedServiceId) {
            $context->addMethodCall('setMutation', [new Reference($taggedServiceId)]);
        }
    }
}
