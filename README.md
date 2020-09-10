# website
Website is a blogging site created using Symfony frameworks.
The purpose was to document/organise notes of things that I learned.
The project is still in development and will be updated frequently.

There are couple of things that I am still considering:
1. How to make the notes more organised and easy to read.
2. I am not a good writer, I like bullet points and diagrams. I want these featured here.
3. Should I make this open and let everyone join in?
4. Can anyone post comments here?
5. Security is still an issue here.

## development environment
I am using Ms Visual Code in Windows 10 with help of Git Bash terminal.
I find Git Bash is more reliable than windows terminal as it behaving more like a MacOS terminal.

## project requirements
PHP Composer.
XAMPP to manage Apache and MySQL driver.
Postman.

## steps and libraries
**generate the project**

`composer create-project symfony/skeleton website`

**managing assets like css, js in <script> <style>**
    
`composer require symfony/asset`

**route management**

`composer require annotations`
    - js routing
    `composer require friendsofsymfony/jsrouting-bundle`
    `php bin/console assets:install --symlink public`

    - example
    Symfony annotations route
    ```
    @Route("/page/{value}",                     => http url
            default={"value"="something"},      => give  a default value to route var
            requirement={"value"="alt1|alt2"}   => limits value to alt1 / alt2
            name={"_page_name"}                 => name that can be called using path() / 
                                                    return $this->redirect($this->generateUrl('_page_name', array(
                                                        'param1' => 'value1',
                                                        ...
                                                        )));
                                                => in JavaScript with help from fosjsrouting library
                                                    window.location.href = Routing.generate('_page_name', {
                                                        'param1': 'value1',
                                                        ...
                                                        });
            options={"expose":true}             => expose this route, so ajax can access it)
    ```
**twig template + form library**

`composer require twig`
`composer require form`

**Doctrin ORM**

    - `composer require doctrine maker`
        automatically give you :
        - composer require symfony/maker-bundle --dev
        - composer require symfony/orm-pack
    - setup a database
        edit database username, password, specification(location, type of database used) in .env file
    - this command will connect to database and create a new database for us
        `php bin/console doctrine:database:create`
    - will prompt us to create an Entity class and it's list of column and create a table in database that reflects it
        `php bin/console make:entity Blog`
    - run these command after updating entity class
        these command will track all and made the changes to database
        `php bin/console doctrine:migrations:diff`
        `php bin/console doctrine:migrations:migrate`
    - we can do query from terminal
        `php bin/console doctrine:query:sql 'SELECT * FROM blog'`
    - authentication library
        -`composer require security`
        -`php bin/console make:auth`
        -`php bin/console make:user`

**Creating fixtures**
    - this is a sanity check tools that help us checks things
        'composer require --dev doctrine/doctrine-fixtures-bundle'
        'php bin/console make:fix'
    - run a Fixture
        'php bin/console doctrine:fixtures:load'
    - helper bundles
        'composer require sensio/generator-bundle'

**dump() with proper styling for Symfony 5**
'composer require symfony/debug-bundle'
