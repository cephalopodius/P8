## Authentification guide

# Read the README file before this.

Users are represented by the src\entity\User class. Unicity constrant is active on the email's field and username's field to avoid dupplicata.

```php
/**
 * @ORM\Table("user")
 * @ORM\Entity
 * @UniqueEntity("email")
 */
```

Security config is reachable by the config\packages\security.yml 
User are saved in database by doctrine.
User providers are PHP classes related to Symfony Security that have two jobs:
	-Reload the User from the Session
	-Load the User for some Feature

```yaml
providers:
        users_in_memory: { memory: null }
        doctrine:
          entity:
              class: App:User
              property: username			  
```

A “firewall” is your authentication system: the configuration below it defines how your users will be able to authenticate 
He is set to prevent stranger to acces some website's part. To be authentificated, we use a form, wich we can access by the login route

```yaml
firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        main:
              anonymous: true
              pattern: ^/
              provider: doctrine
              form_login:
                  login_path: login
                  check_path: login_check
                  always_use_default_target_path:  true
                  default_target_path:  /
              logout: ~
```

The stranger can access to this route by the access_control parameter. It is here you can create a new role for your users and setup the new security rule about it.
The /users url is reachable only by user tagged as ROLE_ADMIN.

```yaml
  access_control:
         - { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
	 - { path: ^/tasks, roles: ROLE_USER }
         - { path: ^/users, roles: ROLE_ADMIN }
```

The user's creation form is free to access, however the user edit action is only granted to admin with the ROLE_ADMIN
A ROLE_ADMIN Got also all ROLE_USER right.

```yaml
    role_hierarchy:
        ROLE_ADMIN: ROLE_USER
```

If you want to add a new role, dont forget to add restriction on his reserved method in controller, aswell. This project is using the @Security annotation

```php
 * @Security("is_granted('ROLE_USER')")
```

The deleteTaskAction method in src\Controller\TaskController.php got an other control to respect the following rule ; a task can only be deleted by is creator or an Admin

```php
  if($task->getUser() == $this->getUser() || $this->getUser()->getRoles()== ["ROLE_ADMIN"] ){
```

Finally, remember to add the new role in the form file src\Form\UserType.php. It could be annoying to have a new role but not being able to add it as admin :).

```php
  ->add('roles', ChoiceType::class, [
              'choices' => [
                  'Utilisateur' => 'ROLE_USER',
                  'Administrateur' => 'ROLE_ADMIN'
              ],
```

To change some messages, you just have to change addFlash content in the method inside the controller file, as src\Controller\TaskController.php

```php
  $this->addFlash('success', 'La tâche a bien été supprimée.');
```

To respect the company policy, you have to add test every new method or entity's field. Test are in the tests\AppBundle\ folder.
This project have been tested by using php-unit. You can check the last test coverage generated html file in var/data folder
to make test, don't forget to switch in the dev environment(.env file), then delete vendor folder, make a composer install.
The command to start testing is :

```bash
.\vendor\bin\simple-phpunit tests
```

Here is an example of one test:
```php
   static::assertSame(1, $crawler->filter('input[name="_username"]')->count());
```

assertSame is a function who will take the resulst you are hopin in the first parameter, then in second parameter, what you want to test.
Here , we look if there is 1 input with the name = username, no more no less.
The project MUST have a total code coverage close to 80% minimun.

Also, check your work on code climate.
https://codeclimate.com/quality/
Your score must be an A.


Some information:
Project is using AuthenticationUtils in src\Controller\SecurityController.php
So you can change error message in creating a CustomUserMessageAuthenticationException at vendor\symfony\security-core\Exception
Login's template page is reachable in templates\security\login.html.twig
		
