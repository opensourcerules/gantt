<?php

namespace GanttDashboard\App\Validators;

use Phalcon\Validation\Message;
use Phalcon\Validation\Message\Group;

trait Traits
{
    /**
     * @param Group $messages
     * @return array
     */
    public function buildNoticesForView(Group $messages)
    {
        $notices = [];

        /**
         * @var Message $message
         */
        foreach ($messages as $message) {
            $notices[$message->getField()] = $message->getMessage();
        }

        return $notices;
    }
}
