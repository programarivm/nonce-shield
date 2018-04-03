## Nonce Shield

[![Build Status](https://travis-ci.org/programarivm/nonce-shield.svg?branch=master)](https://travis-ci.org/programarivm/nonce-shield)
[![Packagist](https://img.shields.io/packagist/dt/programarivm/nonce-shield.svg)](https://packagist.org/packages/programarivm/nonce-shield)
[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

![CSRF Shield](/resources/nonce-shield.jpg?raw=true)

This is a simple, framework-agnostic library inspired by [WordPress nonces](https://codex.wordpress.org/WordPress_Nonces) that helps you protect your PHP web apps from CSRF attacks. Nonce Shield nonces are temporary tokens that uniquely identify urls, using the current session identifier as a hash.

For example, the nonce below:

    $2y$11$vcnb1ci9hm0gpb88gn48vuA2CKPTFwh8N5O4aH9mobJfsRt7us09y

Identifies this URI temporarily (until the session is renewed):

    /url-to-protect/something.php

Nonce Shield accepts all HTTP methods (`GET`, `POST`, `PUT`, `PATCH` and `DELETE`), but is specially suitable for when you want to protect GET resources that perform sensitive operations on the server side -- update a user, remove a comment, etc -- as the ones shown next.

`/user/update.php?id=3452&_nonce_shield_token=$2y$11$dkihrvmrerja3v787sgh3eyQDM9zb2enMxwEE7OPGfzLdvHrAZ52q`

`/comment/remove.php?id=3452&_nonce_shield_token=$2y$11$pqkld10rrrd23kv3ou010u5nEvDHdx5IecuSuIN94nOYiMDydzvkq`

> **Side Note**: If you are not a big fan of sending tokens in GET requests, have a look at [CSRF Shield](https://github.com/programarivm/csrf-shield) which is a OWASP-friendly CSRF protector that won't disclose tokens.

### 1. Install

Via composer:

    $ composer require programarivm/nonce-shield

### 2. Where Is the Token Appended?

Depends on the HTTP method being used:

| HTTP Method   |  Nonce                          |
|---------------|---------------------------------|
| GET           | `$_GET['_nonce_shield_token']`  |
| POST          | `$_POST['_nonce_shield_token']` |
| PUT           | `$_SERVER['HTTP_X_CSRF_TOKEN']` |
| PATCH         | `$_SERVER['HTTP_X_CSRF_TOKEN']` |
| DELETE        | `$_SERVER['HTTP_X_CSRF_TOKEN']` |

### 2. `NonceShield\Nonce` Methods

#### 2.1. `getToken()`

Gets a nonce token.

```php
$nonce = (new Nonce)->getToken('/comment/remove.php?id=3452');
```
#### 2.2. `htmlInput()`

Returns an HTML input tag with the nonce token embedded.

```php
echo (new Nonce)->htmlInput('/comment/remove.php');
```

Here is an example:

    <input type="hidden" name="_nonce_shield_token" id="_nonce_shield_token" value="$2y$11$pqkld10rrrd23kv3ou010u5nEvDHdx5IecuSuIN94nOYiMDydzvkq" />

#### 2.3. `validateToken()`

Validates the incoming nonce token -- if not valid will respond with a `405` status code (`Method Not Allowed`).

```php
(new Nonce)->validateToken();
```

### 3. License

The GNU General Public License.

### 4. Contributions

Would you help make this library better? Contributions are welcome.

- Feel free to send a pull request
- Drop an email at info@programarivm.com with the subject "CSRF Shield Contributions"
- Leave me a comment on [Twitter](https://twitter.com/programarivm)
- Say hello on [Google+](https://plus.google.com/+Programarivm)

Many thanks.
