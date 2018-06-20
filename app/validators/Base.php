<?php

namespace GanttDashboard\App\Validators;

use Phalcon\Validation;

class Base extends Validation
{
    /**
     * Returns true if there are other than submit type errors
     * @return bool
     */
    public function hasErrors(): bool
    {
        $errors = $this->getMessages();

        if (count($errors->filter('submit')) == 1 && $errors->count() == 1) {
            return false;
        }

        return true;
    }
<<<<<<< HEAD
}
=======

}
>>>>>>> 0b82754602349e9e1403d619afd3bd5bd3f31fb0
