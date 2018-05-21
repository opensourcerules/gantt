<?php

namespace GanttDashboard\App\Validators;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\Callback;
use GanttDashboard\App\Models\Workers;

class Worker extends Validation
{
    /**
     * Constructs the validations for model
     * @param Workers $workerModel
     */
    public function __construct(Workers $workerModel)
    {
        $this->add(
            'submit',
            new PresenceOf([
                'cancelOnFail' => true,
            ])
        );

        $this->add(
            'firstName',
            new PresenceOf([
                'message' => 'The First Name is required.',
            ])
        );

        $this->add(
            'lastName',
            new PresenceOf([
                'message' => 'The Last Name is required.',
            ])
        );

        $this->add(
            'email',
            new PresenceOf([
                'message' => 'The e-mail is required.',
            ])
        );

        $this->add(
            'email',
            new Callback([
                'callback' => function ($data) {
                    if (strlen($data['email']) > 0) {
                        return new Email([
                            'message' => 'The e-mail is not valid.',
                        ]);
                    }

                    return true;
                }
            ])
        );

        $this->add(
            'email',
            new Callback([
                'callback' => function ($data) use ($workerModel) {
                    if (false === isset($data['id'])) {
                        new Uniqueness([
                            'model'   => $workerModel,
                            'message' => 'This email is already registered.',
                        ]);
                    }

                    return true;
                }
            ])
        );
    }
}
