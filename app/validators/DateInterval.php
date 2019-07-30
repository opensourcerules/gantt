<?php

namespace GanttDashboard\App\Validators;

use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Callback;
use Phalcon\Validation\Validator\Date as DateValidator;

class DateInterval extends Base
{
    /**
     * Constructs the validations for date
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
            ['start', 'end'],
            new PresenceOf([
                'message' => [
                    'start' => 'The start date is required.',
                    'end' => 'The end date is required.',
                    ]
            ])
        );

        $this->add(
            ['start', 'end'],
            new DateValidator([
                'format'  => [
                    'start' => 'Y-m-d',
                    'end' => 'Y-m-d'
                ],
                'message' => [
                    'start' => 'The start date is invalid.',
                    'end' => 'The end date is invalid.',
                ]
            ])
        );

        $this->add(
            'end',
            new Callback([
                'message' => 'The end date must be after the start date.',
                'callback' => function ($data) {
                    if ($data['start'] > $data['end']) {
                        return false;
                    }

                    return true;
                }
            ])
        );
    }

    /**
     * Calls for validation on date interval
     * @param array $dateInterval
     * @return Base
     */
    public function validateDateInterval(array $dateInterval): Base
    {
        $this->validate($dateInterval);

        return $this;
    }
}
