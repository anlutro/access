# Access - RBAC for Laravel 4 [![Build Status](https://travis-ci.org/anlutro/access.png?branch=master)](https://travis-ci.org/anlutro/access)

My stab at an RBAC system for Laravel 4.

This is probably extremely query intensive and I have not made many attempts to optimize the number of queries ran/in-memory caching being done.

I wrote this with the intention of using it on small systems with a low number of concurrent users. It is made for systems where you need to control permissions on row-basis rather than just some generalized roles and permissions.

## Contribution

Bug reports, suggestions and code improvements are highly welcome. Use the github issue system! If you just want to have a chat, look for me in #laravel on freenode.

## Installation

### Requirements
PHP 5.4 or higher
Laravel 4.1 or higher

### Install
`composer require anlutro/access`

Check packagist.org or the github tag list for the latest stable release, or use dev-master if you like living on the edge.

### Copy migrations
Copy migrations from `vendor/anlutro/access/src/migrations` to your app's migration directory. Alternatively, run them with `php artisan migrate --package anlutro/access` if you just want to play around with the system - copying the migration files manually is recommended for production setups.

### Create your user model
Because you probably want to put your own functions and fields on the User model/table, you create the user model yourself. There are two ways to do this and ensure it works with the RBAC system - inheritance (extending a base class) or traits.

```php
class MyUser extends anlutro\Access\Models\User {}

class MyUser extends Eloquent implements anlutro\Access\Interfaces\SubjectInterface
{
	use anlutro\Access\Traits\UserSubject;
}
```

You are responsible for creating the user table. Remember to update your `app/config/auth.php` file to reflect your model.

### Create one or more resource models
Again you can do this with inheritance or traits:

```php
class MyResource extends anlutro\Access\Models\Resource {}

class MyResource extends Eloquent implements anlutro\Access\Interfaces\ResourceInterface
{
	use anlutro\Access\Traits\ResourceSubject;
}
```

You are responsible for creating any resource tables.

## Usage

First, we need to create some permissions.

```php
use anlutro\Access\Models\Permission;
$lowPermission = Permission::create(['name' => 'Normal Permission']);
$highPermission = Permission::create(['name' => 'High Level Permission']);
```

Then, let's assign some permissions to actions on one of our resource models.

```php
MyResource::addGlobalPermissionTo('show', $lowPermission);
MyResource::addGlobalPermissionTo('create', $lowPermission);
MyResource::addGlobalPermissionTo('create', $highPermission);
```

Let's create a couple of roles. This step is optional, permissions can be added to users directly if you like.

```php
use anlutro\Access\Models\Role;
$userRole = Role::create(['name' => 'User Role']);
$userRole->addPermission($lowPermission);
$adminRole = Role::create(['name' => 'Admin Role']);
$adminRole->addPermission($lowPermission);
$adminRole->addPermission($highPermission);
```

Let's assign the user role to one of our users.

```php
$user = User::first();
$user->addRole($userRole);
```

Now, the user should have access to show, but not create a MyResource.

```php
$resource = MyResource::first();
var_dump($user->hasPermissionTo('show', $resource));
$resource = new MyResource;
var_dump($user->hasPermissionTo('create', $resource));
```

If we assign the user the admin role, however, he should have access to create as well.

```php
$user->addRole($adminRole);
var_dump($user->hasPermissionTo('create', $resource));
```

Most of the time you'll be running these checks against the currently logged in user. The Access facade has some handy shorthand functions for this.

```php
use anlutro\Access\Access;
Access::allowed('show', $resource);
Access::allowed('create', $resource);
```

## License

The contents of this repository is released under the [MIT license](http://opensource.org/licenses/MIT).