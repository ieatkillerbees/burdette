#Burdette
A Rate-Limiting Library for PHP 5.4+
------------------------------------
[![Build Status](https://travis-ci.org/squinones/burdette.svg?branch=master)](https://travis-ci.org/squinones/burdette) [![Code Climate](https://codeclimate.com/github/squinones/burdette/badges/gpa.svg)](https://codeclimate.com/github/squinones/burdette) [![Test Coverage](https://codeclimate.com/github/squinones/burdette/badges/coverage.svg)](https://codeclimate.com/github/squinones/burdette)

###What does this do?
This library is primarily designed for APIs that wish to limit how many requests a client can make over a given period
of time. In theory it can be used to throttle any kind of behavior. If you find a neat use case, we'd love to hear 
about it!

### Usage
At a basic level, you have a strategy, which is just a class that implements a specific rate-limiting policy. Strategies operate on buckets which maintain the state for any given identity. The bucket contains tokens which are replenished periodically. When a bucket has no more tokens to give, the identity has run up against the rate limit and should be stopped.

Let's use a practical example. Say you have an API endpoint /foo and you want to limit it so that any given IP address may only access /foo 1 time per hour.

In this case, the IP address is the identity. We'll employ a `TimeBlockStrategy` to implement the policy we want. Ignore the reference to a bucket repository for the moment. It will be explained later.
```php
use Burdette\Identities\StringIdentity;
use Burdette\Strategies\TimeBlockStrategy;

$identity = new StringIdentity($client_ip);
$strategy = new TimeBlockStrategy($bucket_repository);
$strategy->setReplenishmentRate(100, TimeBlockStrategy::HOURLY);

$token = $strategy->newToken($identity);
echo $token->isAllowed() . "\n";
echo $strategy->getNextReplenishmentTime();

$new_token = $strategy->newToken($identity);
echo $token->isAllowed() . "\n";
echo $strategy->getNextReplenishmentTime();
```

Running this code we should see something like...
```
true
Sat, 06 Dec 2014 22:00:00 -0500

false
Sat, 06 Dec 2014 22:00:00 -0500
```

###Check back soon
This is a new project! Check back soon for more information!

###What's with the name?
It's an homage to the late, great Josh Burdette, an icon of the Washington, DC music scene. If you would like to know
more about Josh, the University of Maryland (his alma mater) hosts a 
[memorial page](http://www.joshburdettememorial.umd.edu/about.html) for him.
