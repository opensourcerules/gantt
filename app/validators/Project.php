<?php

namespace GanttDashboard\App\Validators;

use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\Callback;
use GanttDashboard\App\Models\Projects;

class Project extends Base
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
            ['name', 'description'],
            new PresenceOf([
                'message' => [
                    'name' => 'The name is required.',
                    'description' => 'The description is required.'
                    ],
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
}
