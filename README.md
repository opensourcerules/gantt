#Gantt dashboard


##Getting Started
These instructions will get you a copy of the project up and running on your local machine.


##Prerequisites
PHP >=7.0
Create a new MySql database with user and password. **The user must have all privileges**.


##Setting up the database connection
Edit _app/config/config.php_ and enter your database details
Example:
    ```'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'xxx',
        'password'    => 'xxx',
        'dbname'      => 'xxx',```


##Creating database structure
Using **phalcon devtools _change directory_ to the project's folder** and run:
```phalcon migration run```


##Built with
Phalcon 3.3.2
Bootstrap 4.1.0