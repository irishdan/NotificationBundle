# NotificationBundle

This is a work in progress. 

## config
```
notification:
    mail_channel:
        enabled: true
        default_sender: 'dan@oioi.com'
    database_channel:
        enabled: true
        entity: 'AppBundle:Notification'
    pusher_channel:
        enabled: true
        auth_key: 3
        secret: 2
        app_id: 1
        cluster: 'eu' # Set default value
        encrypted: true # Set default value
        channel_name: 'private-app_channel_' # will get suffux of user id for private channel. Must begin with 'private-'!
        event: 'notification-event'
    nexmo_channel:
        enabled: true
        api_key: abc
        api_secret: 123
        from: "Dan"
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