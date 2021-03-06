# Service client [![Build Status](https://travis-ci.com/alexdodonov/mezon-service-client.svg?branch=master)](https://travis-ci.com/alexdodonov/mezon-mezon-service-client) [![codecov](https://codecov.io/gh/alexdodonov/mezon-mezon-service-client/branch/master/graph/badge.svg)](https://codecov.io/gh/alexdodonov/mezon-mezon-service-client) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexdodonov/mezon-service-client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alexdodonov/mezon-service-client/?branch=master)
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

And since then you can connect to this service:

```PHP
client->connect('login', 'password');
```

## Default methods

You already know about one out-of-the box method - connect. But there are more of them.