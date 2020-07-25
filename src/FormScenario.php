<?php

namespace AndriiMz\FormScenario;

use AndriiMz\FormScenario\Mutation\MutationManager;
use AndriiMz\FormScenario\Mutation\ScenarioMutationInterface;
use AndriiMz\FormScenario\Model\State;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FormScenario
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var MutationManager
     */
    private $mutationManager;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ContainerInterface $container
     * @param MutationManager $mutationManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ContainerInterface $container,
        MutationManager $mutationManager
    ) {
        $this->entityManager = $entityManager;
        $this->container = $container;
        $this->mutationManager = $mutationManager;
    }


    /**
     * @param Request $request
     * @param string $className
     * @param string $formTypeClass
     * @param int $id
     * @param array $context
     *
     * @return State
     */
    public function run(
        Request $request,
        string $className,
        string $formTypeClass,
        int $id,
        array $context = []
    ): State {
        $state = new State();

        $object = new $className();
        if (!empty($id)) {
            $object = $this->entityManager
                ->getRepository($className)
                ->find($id);
        }

        $form = $this->createForm(
            $formTypeClass,
            $object
        );

        $state->form = $form;
        $state->entity = &$object;

        if($request->isMethod(Request::METHOD_POST)) {
            try {
                $form->submit(
                    $request->request->get(
                        $form->getName()
                    )
                );

                $context[Request::class] = $request;

                if ($form->isSubmitted()) {
                    $object = $form->getData();
                    $object = $this->mutationManager->mutate(
                        ScenarioMutationInterface::PRE_PERSIST,
                        $object,
                        $context
                    );

                    $this->entityManager->persist($object);
                    $object = $this->mutationManager->mutate(
                        ScenarioMutationInterface::PRE_FLUSH,
                        $object,
                        $context
                    );

                    $this->entityManager->flush();
                    $state->isSubmitted = true;
                    $state->createdId = $object->getId();
                }
            } catch (\Exception $e) {
                $state->errors[] = $e->getMessage();
            }
        }

        return $state;
    }

    /**
     * @param $type
     * @param null $data
     * @param array $options
     *
     * @return FormInterface
     */
    private function createForm($type, $data = null, array $options = []): FormInterface
    {
        /** @var FormFactoryInterface $formFactory */
        $formFactory = $this->container->get('form.factory');

        return $formFactory->create($type, $data, $options);
    }
}
