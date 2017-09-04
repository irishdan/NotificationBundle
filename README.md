# NotificationBundle

[![Build Status](https://travis-ci.org/irishdan/NotificationBundle.svg?branch=master)](https://travis-ci.org/irishdan/NotificationBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/irishdan/NotificationBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/irishdan/NotificationBundle/?branch=master)

## Overview

This NotificationBundle allows for the easy creation and and sending of messages to users or other sources, via multiple channels.
Its also enables easy broadcasting of data or messages via.

Out of the box with minimal configuration notifications can be sent to single or groups of users,
via, email, SMS, slack and websockets (pusher).

Combine this bundle with FoundationInk Bundle to send beautiful html emails for any event
Combine a pusher channel with taostr for instant and attractive notifications
Combine a pusher channel and a database channel for simple direct messaging.

## Basic setup

Out of the box, NotificationImage bundle should work with minimal configuration.

### Step 1: Download and enable the bundle

Download with composer
```
composer require irishdan/notification-bundle
```
Enable the bundle in the kernel
```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new IrishDan\ResponsiveImageBundle\ResponsiveImageBundle(),
    );
}
```
### Step 2: Configure some Notification Channels

Out of the box the bundle supports several channels, including:
- [mail]() (Email)
- [database]()
- [slack]()
- [nexmo]() (SMS)
- [pusher]() (Websockets)
- [logger]()

To enable a channel simple add its configuration to your config.yml

```yml
# app/config/config.yml
notification:
    mail:
        default_sender: 'hi@nomadapi.io'
    database:
        entity: 'AppBundle:Notification'
    pusher:
        app_id: "12"
        auth_key: "1111SECURE222KEY"
        secret: "SeCrEt"
        cluster: "eu"
        encrypted: true
        event: 'notification'
        channel_name: 'private-direct_' # This is a private channel
    slack:
    nexmo:
        api_key: 7654321
        api_secret: oiCHOIoi
        from: "YourApp"
    logger:
        severity: 'info'
```

It's also possible to create [custom channels]() or [alter an existing channel's behavior]()

### Step 4: Database, Pusher, Nexmo, and Slack channels have additional steps.

Some channels require additional steps

#### Database channel

The Database channel essentially persists a Doctrine entity to the database. 
A generator is provided to create the entity.

```bash
php bin/console notification:create-database-notification 
```
#### Pusher channel
[Pusher]() is a a third party service with a decent free package. You need valid pusher credendials to use the channel.

The pusher PHP library is required also. Install with composer
```bash
composer require pusher/pusher-php-server

```
If you are using private channels (HIGHLY RECOMMENDED), the pusher authentication route is needed.
Import the route into your project

```yml
# app/config/routing.yml
notification_pusher_auth:
    resource: "@NotificationBundle/Resources/config/routing.yml"

```
Pusher requires a javascript library and additional  to interact with pusher channel you have defined. 
Twig functions are provided which generate the required javascript

```twig
{% block javascripts %}
    <script src="https://js.pusher.com/4.0/pusher.min.js"></script>
    <script>
      
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        {{ notification_new_pusher_js() }}

        {{ notification_new_pusher_channel_js(app.user) }}

        channel.bind('{{ notification_pusher_event }}', function (data) {
            // The data object contains your notification data
            console.log(data);
            // Add custom js to react to the the notifcation.
            // A good solution is to use toastr to display the notification.
        });  
    </script>
{% endblock %}

```

#### Nexmo channel

[Nexmo]() Is a third party SMS service. You need valid credentials to use this channel.

#### Slack shannel

[Slack]() 

### Step 5: Subscribe Users to these channels

In order for users to be sent notifications through the channels you have configured they must be subscribed to each channel
and have certain data available.

Assuming your User class is AppBundle\Entity\User, implement the required interfaces:
@TODO: Improve this

```
<?php

namespace AppBundle\Entity;

use IrishDan\NotificationBundle\Notification\NotifiableInterface;
use IrishDan\NotificationBundle\PusherableInterface;
use IrishDan\NotificationBundle\SlackableInterface;
use IrishDan\NotificationBundle\TextableInterface;
use IrishDan\NotificationBundle\EmailableInterface;

class User implements UserInterface, NotifiableInterface, EmailableInterface, TextableInterface, PusherableInterface, SlackableInterface, DatabaseNotifiableInterface

    // For convenience use the
    use FullyNofifiabletrait();

```

### Step 6: Generate Notification objects

Each Notification is a separate Object. 
So for example you might have a NewMemberNotification() object and a NewPaymentReceivedNotification() object.

To create a new Notification object use the provided generator.

```bash
php bin/console notification:create
```
### Step 7: Edit the Notification content

Uses twig...

### Step 8: Send Notifications

```php
<?php
/** $user NotifiableInterface */
$user = $this->getUser();

/** $notification NotificationInterface */
$notification = new NewMemberNotification();

// The notification.manager service is used to send notifications
$this->get('notification.manager')->send($notification, $user);

// You can send to multiple users also.
$this->get('notification.manager')->send($notification, [$user1, $user2]);

// You can pass extra data into the notification also
// This will be available in the data array
// and also in twig templates as {{ data.date }}
$data = [
    'date' => new \DateTime(),
];
$this->get('notification.manager')->send($notification, $user, $data);
```
