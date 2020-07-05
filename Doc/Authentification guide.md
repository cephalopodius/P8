Authentification guide

Users are represented by the src\entity\User class. Unicity constrant is active on the email's field to avoid dupplicata.
```php
/**
 * @ORM\Table("user")
 * @ORM\Entity
 * @UniqueEntity("email")
 */
```
Security config is reachable by the config\packages\security.yml 
User are saved in database by doctrine. Unicity attribute is on the username's field.

```yaml
providers:
        users_in_memory: { memory: null }
        doctrine:
          entity:
              class: App:User
              property: username
```		  


A firewall is set to prevent stranger to acces some website's part. To be authentificated, we use a form, wich we can access by the login route


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
The stranger can access to this route by the access_control parameter

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
Project is using AuthenticationUtils in src\Controller\SecurityController.php
So you can change error message in creating a CustomUserMessageAuthenticationException at vendor\symfony\security-core\Exception

Login's page is reachable in templates\security\login.html.twig
		
