# Service client [![Build Status](https://travis-ci.com/alexdodonov/mezon-service-client.svg?branch=master)](https://travis-ci.com/alexdodonov/mezon-mezon-service-client) [![codecov](https://codecov.io/gh/alexdodonov/mezon-mezon-service-client/branch/master/graph/badge.svg)](https://codecov.io/gh/alexdodonov/mezon-mezon-service-client)
## Intro

Mezon provides simple client services based on the Mezon framework.

## Installation

Just print in console

```
composer require mezon/service-client
```

And that's all )

## Setup

First of all you need to create client and set to wich service it must send requests:

```PHP
$client = new \Mezon\Service\ServiceClient('https://some-service.com/');
```