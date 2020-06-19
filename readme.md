## Lumen PHP Framework
[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://poser.pugx.org/laravel/lumen-framework/d/total.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/lumen-framework/v/stable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/lumen-framework/v/unstable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://poser.pugx.org/laravel/lumen-framework/license.svg)](https://packagist.org/packages/laravel/lumen-framework)

# IMOK API
API for IMOK web / desktop and mobile app

## Usage

    Login / Logout
    --------------
    POST    api/auth/login                  => login ( requires mail & password )
    POST    api/auth/logout                 => logout
    POST    api/refresh                     => regenerate token and invalidates current token
    POST    api/me                          => displays connected employee informations
    
    Customers
    ---------
    GET     api/customers/                  => get all customers
    GET     api/customers/id                => get one customer by id
    GET     api/customers/find/argument     => get customers containing argument in mail / firstname / lastname
    POST    api/customers/                  => create new customer
    PUT     api/customers/id                => update customer

    Estates
    ---------
    GET     api/estates/                  => get all estates
    GET     api/estates/id                => get one estate by id
    POST    api/estates/search            => get estates containing argument in attribute
    POST    api/estates/create            => create new estate
    PUT     api/estates/id                => update estate

    Appointments
    ----------
    GET      appointments/                  => get all appointments
    GET      appointments/c/e/date          => get appointment by customer/employee/date
    GET      appointments/attr/arg          => get estates containing argument in attribute
    POST     appointments/                  => create new estate
    PUT      appointments/id                => update estate
