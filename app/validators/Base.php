<?php

namespace GanttDashboard\App\Validators;

use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Message\Group;

abstract class Base extends Validation
{
    /**
     * Builds array of errors for view, from object
     * @param Group $messages
     * @return array
     */
    protected function buildErrorsForView(Group $messages): array
    {
        $errors = [];

        /**
         * @var Message $message
         */
        foreach ($messages as $message) {
            $errors[$message->getField()] = $message->getMessage();
        }

        return $errors;
    }
}
