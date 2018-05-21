<?php

namespace GanttDashboard\App\Models;

use Phalcon\Mvc\Model;

class Workers extends Model
{
    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id", type="integer", length=11, nullable=false)
     */
    protected $id;

    /**
     *
     * @var string
     * @Column(column="last_name", type="string", length=255, nullable=false)
     */
    protected $lastName;

    /**
     *
     * @var string
     * @Column(column="first_name", type="string", length=255, nullable=false)
     */
    protected $firstName;

    /**
     *
     * @var string
     * @Column(column="email", type="string", length=60, nullable=false)
     */
    protected $email;

    /**
     * Method to set the value of field lastName
     *
     * @param string $lastName
     * @return $this
     */
    public function setLastName($lastName): object
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Method to set the value of field firstName
     *
     * @param string $firstName
     * @return $this
     */
    public function setFirstName($firstName): object
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Method to set the value of field email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email): object
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Returns the value of field lastName
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Returns the value of field firstName
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Returns the value of field email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap(): array
    {
        return [
            'id' => 'id',
            'last_name' => 'lastName',
            'first_name' => 'firstName',
            'email' => 'email'
        ];
    }
}
