# GNU Mailman API

__Note:__ This API is incomplete. There are several things in the 
[Mailman documentation](http://docs.mailman3.org/en/latest/) that have not been implemented just because that were not
needed.

_If you would like to contribute to this API, submit a pull request to have your changes reviewed before they are 
merged into master._

## Table of Contents

- [Methods](#methods)
    - [Mailman](#mailman)
    - [Users](#users)
    - [Lists](#lists)
    - [Members](#members)
    - [Domains](#domains)

### Methods <a name="methods"></a>

#### Mailman <a name="mailman"></a>

- domains() : Domains
- lists() : Lists
- users() : Users
- members() : Members

Example to get all lists:
```php
$allLists = $mailman->lists()->all(null, null, true);
```

Example to get lists for a user:
```php
$usersLists = $mailman->members()->find(null, 'email_address');
```

#### Users <a name="users"></a>

- all($count = null, $page = null)
- find($email)
- create($email, $displayName = null, $password = null)
- updateDisplayName($email, $displayName)
- updatePassword($email, $password)
- delete($email)

#### Lists <a name="lists"></a>

- all($count = null, $page = null, $advertised = null)
- find($name)
- members($name, $count = null, $page = null)
- create($name, $style = null)
- delete($domain)

#### Members <a name="members"></a>

- all($count = null, $page = null)
- find($listId = null, $subscriber = null, $role = null)
- subscribe($listId, $subscriber, $displayName = null, $verified = false, $confirmed = false, $approved = false)
- unsubscribe($memberId)

#### Domains <a name="domains"></a>

- all($count, $page)
- find($domain)
- lists($domain, $count = null, $page = null, $advertised = null)
- create($domain, $description = null)
- delete($domain)
