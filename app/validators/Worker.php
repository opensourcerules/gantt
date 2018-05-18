<?php

namespace GanttDashboard\App\Validators;

use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\Callback;
use Phalcon\Validation\Validator\StringLength;
use GanttDashboard\App\Models\Workers;

class Worker extends Base
{
    /**
     * Constructs the validations for model
     * @param Workers $workerModel
     */
    public function __construct(Workers $workerModel)
    {

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
                    if ('' !== $data['email']) {
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
            new Uniqueness([
                'model'   => $workerModel,
                'message' => 'This email is already registered.',
            ])
        );

        $this->add(
            'password',
            new Callback([
                    'callback' => function ($data) {
                        if ('1' === $data['admin']) {
                            return new StringLength([
                                'min'            => 6,
                                'max'            => 20,
                                'messageMinimum' => 'Password must be at least 6 characters long.',
                                'messageMaximum' => 'Password must be at most 20 characters long.',
                            ]);
                        }

                        return true;
                    }
            ])
        );
    }

    /**
     * Calls for validation on $worker
     * @param array $worker
     * @return array
     */
    public function validation(array $worker): array
    {
        return $this->buildErrorsForView($this->validate($worker));
    }
}
