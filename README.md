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

### Step 4: Database, Pusher, Nexmo, and Slack channels have addition steps.

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


the ability to easily created styled responsive images (scaled, cropped, greyscale) in Symfony3.
This bundle allows for the management and presentation of images in various styles (scaled, cropped, grey scale etc etc)
and sizes.
Art directed responsive images, with picture or sizes/srcset, can also be generated.
Define break points, map them to images styles to create responsive images and css.

The bundle uses flysystem filesystem abstraction layer giving you control over where images are stored.
Eventas are used to dirvie the system, giving more flexibiltiy and extensibility, can control when images are generated, eg perhaps this should be queued
Images can be created from predefined styles or on the fly
supports retina 2x 1.5x images

ResponsiveImageBundle adds the ability to easily created styled responsive images (scaled, cropped, greyscale) in Symfony3.

How it works?
- Users that implement NotifiableInterface interface can be subscribed to different notification 'channels'
    NotifiableInterface::getSubscribedChannels()
- Channels include, database, email, pusher, nexio (sms), custom channels can be defined easily
- Notifications are classes, which can be generated with:
    php bin/console notification:create  
    php bin/console notification:create-database-notification 
- Each notification can be sent via one or more channels
- Each channel has a formatter (twig, templates etc) and a dispatcher
- It's all event driven
- Broadcast is a notification sent to an external service eg a slack channel, mail chimp list, drip, hipchat
- notifiableFactory for creating recipients on the fly

## config
```
notification:
    channels:
        mail:
            default_sender: 'dan@oioi.com'
        database:
            entity: 'AppBundle:Notification'
        pusher:
            auth_key: 3
            secret: 2
            app_id: 1
            cluster: 'eu' # Set default value
            encrypted: true # Set default value
            channel_name: 'private-app_channel_' # will get suffux of user id for private channel. Must begin with 'private-'!
            event: 'notification-event'
        nexmo:
            api_key: abc
            api_secret: 123
            from: "Dan"
        slack:
    broadcasts:
        the_broadcast_name:
            slack: # type
                webhook: 'https://hooks.slack.com/services/xxx/yyy/jhjhsd
```

## Channels

## Notifications

## Commands

Can use a command to generate a notification object, eg NewMemberNotification. 
This command will also generate twig templates for each enabled channel

```
  notification:create
  notification:create-database-notification  
```



## sending notifications
```
$notification = new NewMemberNotification();
$this->get('notification')->send($notification, [$user]);
        
```

## twig 

```
<script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        {#
        // available twig functions

        http://symfony.com/doc/current/templating/global_variables.html
        Add them all as global twig variables

        {{ notification_new_pusher_js() }}
        {{ notification_new_pusher_channel_js(app.user) }}
        {{ notification_pusher_channel_name() }}

        {{ notification_pusher_auth_endpoint }}
        {{ notification_pusher_auth_key }}
        {{ notification_pusher_app_id }}
        {{ notification_pusher_event }}
        {{ notification_pusher_cluster }}
        {{ notification_pusher_encrypted }}

        #}
        
        // var channel = pusher.subscribe('{{ notification_pusher_channel_name(app.user) }}');
        {{ notification_new_pusher_js() }}
        
        {{ notification_new_pusher_channel_js(app.user) }}
        channel.bind('{{ notification_pusher_event }}', function (data) {
            alert(data.message);
        });
    </script>
```