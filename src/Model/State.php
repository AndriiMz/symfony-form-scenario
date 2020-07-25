<?php

namespace AndriiMz\FormScenario\Model;

use Symfony\Component\Form\FormInterface;

class State
{
    /**
     * @var FormInterface
     */
    public $form;

    /**
     * @var array
     */
    public $errors = [];

    /**
     * @var mixed
     */
    public $entity;

    /**
     * @var bool
     */
    public $isSubmitted = false;

    /**
     * @var integer
     */
    public $createdId;
}
