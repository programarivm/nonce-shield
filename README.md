## Nonce Shield

[![Build Status](https://travis-ci.org/programarivm/nonce-shield.svg?branch=master)](https://travis-ci.org/programarivm/nonce-shield)
[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

![CSRF Shield](/resources/nonce-shield.jpg?raw=true)

This is a simple, framework-agnostic library inspired by [WordPress nonces](https://codex.wordpress.org/WordPress_Nonces) that helps you protect your PHP web apps from CSRF attacks. Nonce Shield nonces are temporary tokens that uniquely identify urls, using the current session identifier as a hash.

For example, the nonce below:

    624fd48ceb3eddfb716572d765576e23

Identifies this URI temporarily (until the session is renewed):

    /url-to-protect/something.php

Nonce Shield accepts all HTTP methods (`GET`, `POST`, `PUT`, `PATCH` and `DELETE`), but is specially suitable for when you want to protect GET resources that perform sensitive operations on the server side -- update a user, remove a comment, etc -- as the ones shown next.

`/user/update.php?id=3452&_nonce_shield_token=693904c4e937577ed2589ea54e56a8d5`

`/comment/remove.php?id=3452&_nonce_shield_token=6bee0c3437199bf2e5ca1de872a9cefd`

> **Side Note**: If you are not a big fan of sending tokens in GET requests, have a look at [CSRF Shield](https://github.com/programarivm/csrf-shield) which is a OWASP-friendly CSRF protector that won't disclose tokens.

### 1. Where Is the Token Appended?

According to the HTTP method being used:

| HTTP Method   |  Nonce                          |
|---------------|---------------------------------|
| GET           | `$_GET['_nonce_shield_token']`  |
| POST          | `$_POST['_nonce_shield_token']` |
| PUT           | `$_SERVER['HTTP_X_CSRF_TOKEN']` |
| PATCH         | `$_SERVER['HTTP_X_CSRF_TOKEN']` |
| DELETE        | `$_SERVER['HTTP_X_CSRF_TOKEN']` |


### 2. Security

Nonce Shield assumes there is an `.env` file in your app's root folder with a `NONCE_KEY` set -- otherwise it will throw an `UnsecureNonceKeyException`.

    NONCE_KEY=5ZLXPORAl39jMH5ujR53jNZ3uLpNcz9跡

The `NONCE_KEY` is used as a salt when hashing the url. This value is at least 32 characters long, and must contain at least one number, one lowercase letter, one uppercase letter and a non-alphanumeric character.

### 3. `NonceShield\Nonce` Methods

#### 3.1. `getToken()`

Gets a nonce token.

```php
$nonce = (new Nonce)->getToken('/comment/remove.php?id=3452');
```
#### 3.2. `htmlInput()`

Returns an HTML input tag with the nonce token embedded.

```php
echo (new Nonce)->htmlInput('/comment/remove.php');
```

Here is an example:

    <input type="hidden" name="_nonce_shield_token" id="_nonce_shield_token" value="6bee0c3437199bf2e5ca1de872a9cefd" />

#### 3.3. `validateToken()`

Validates the incoming nonce token -- if not valid will respond with a `405` status code (`Method Not Allowed`).

```php
(new Nonce)->validateToken();
```

### 4. License

The GNU General Public License.

### 5. Contributions

Would you help make this library better? Contributions are welcome.

- Feel free to send a pull request
- Drop an email at info@programarivm.com with the subject "CSRF Shield Contributions"
- Leave me a comment on [Twitter](https://twitter.com/programarivm)
- Say hello on [Google+](https://plus.google.com/+Programarivm)

Many thanks.
