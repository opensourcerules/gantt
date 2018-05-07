Gantt dashboard


Getting Started
These instructions will get you a copy of the project up and running on your local machine.


Prerequisites
Create a new MySql database with user and password. The user must have all privileges.


Setting up the database connection
Edit 'app/config/config.php' and enter your database details
Example:
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'phalcon',
        'password'    => 'l.$u5/aKj@b7',
        'dbname'      => 'gantt',


Creating database structure
Using phalcon devtools change directory to the project's folder and run:
    'phalcon migration run'


Built with
Phalcon 3.3.2 - The web framework used


Authors
Pantea Marius
See also the list of contributors who participated in this project.