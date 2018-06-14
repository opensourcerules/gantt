<?php

namespace GanttDashboard\App\Validators;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\Callback;
use GanttDashboard\App\Models\Projects;
use Phalcon\Validation\Message\Group as MessageGroup;

class Project extends Validation
{
    /**
     * Constructs the validations for model
     */
    public function __construct()
    {
        $this->add(
            'submit',
            new PresenceOf([
                'cancelOnFail' => true,
            ])
        );

        $this->add(
            'name',
            new PresenceOf([
                'message' => 'The name is required.',
            ])
        );

        $this->add(
            'description',
            new PresenceOf([
                'message' => 'The description is required.',
            ])
        );

        $this->add(
            'name',
            new Callback([
                'callback' => function ($data) {
                    if (false === isset($data['name'])) {
                        new Uniqueness([
                            'model'   => new Projects(),
                            'message' => 'This name is already registered.',
                        ]);
                    }

                    return true;
                }
            ])
        );
    }

    /**
     * Returns true if there are other than submit type errors
     * @param MessageGroup $errors
     * @return bool
     */
    public function hasErrors(MessageGroup $errors): bool
    {
        if (count($errors->filter('submit')) == 1 && $errors->count() == 1) {
            return false;
        }

        return true;
    }
}
