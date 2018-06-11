<?php

namespace GanttDashboard\App\Validators;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Message;
use GanttDashboard\App\Services\Project as ProjectService;


class WorkerProject extends Validation
{
    /**
     * Constructs the validations
     */
    public function __construct()
    {
        $this->add(
            'submit',
            new PresenceOf([
                'cancelOnFail' => true,
            ])
        );
    }

    /**
     * Executed before validation
     *
     * @param array $data
     * @param object $entity
     * @param \Phalcon\Validation\Message\Group $messages
     * @return void
     */
    public function beforeValidation($data, $entity, $messages): void
    {
        if (true === isset($data['submit'])) {
            $allRegisteredProjectsIds = ProjectService::getAllProjectsIds();
            $projectsIds = [];

            if (false === isset($data['projects'])) {
                $messages->appendMessage(
                    new Message(
                        'The projects field is required.',
                        'projects'
                    )
                );

                return;
            }

            foreach ($data['projects'] as $key => $idReasonName) {
                $name = $idReasonName['name'];

                if (true === isset($idReasonName['value'])) {
                    if (false === isset($idReasonName['reason'])) {
                        $messages->appendMessage(
                            new Message(
                                'The field projects[' . $key . '][reason] is missing for unassigned project number ' .
                                ($key + 1) . ' from the table',
                                $name
                            )
                        );
                    }

                    if (false === isset($idReasonName['id'])) {
                        $messages->appendMessage(
                            new Message(
                                'The field projects[' . $key . '][id] is missing for unassigned project number ' .
                                ($key + 1) . ' from the table',
                                $name
                            )
                        );
                    }

                    $id = $idReasonName['id'];

                    if ($idReasonName['reason'] === '') {
                        $messages->appendMessage(
                            new Message(
                                'Missing reason for project: ' . $name,
                                $name
                            )
                        );
                    }

                    if (strlen($idReasonName['reason']) > 255) {
                        $messages->appendMessage(
                            new Message(
                                'Reason must be at most 255 characters long for project: ' . $name,
                                $name
                            )
                        );
                    }

                    if (false === in_array($idReasonName['id'], $allRegisteredProjectsIds, true)) {
                        $messages->appendMessage(
                            new Message(
                                'Unregistered project id: ' . $id  . 'for project:' . $name,
                                $name
                            )
                        );
                    }

                    if (true === in_array($idReasonName['id'], $projectsIds, true)) {
                        $messages->appendMessage(
                            new Message(
                                'Project id must be unique. Found multiple id like: ' . $id,
                                'projects'
                            )
                        );
                    }

                    $projectsIds[] = $id;
                    continue;
                }

                if ($idReasonName['reason'] !== '') {
                    $messages->appendMessage(
                        new Message(
                            'Unnecessary reason for unassigned project ' . $name,
                            $name
                        )
                    );
                }
            }

            if (count($projectsIds) == 0) {
                $messages->appendMessage(
                    new Message(
                        'At least one project must be selected and its reason filled.',
                        'projects'
                    )
                );
            }
        }

        return;
    }
}
