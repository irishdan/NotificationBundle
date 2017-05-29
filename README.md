# ResponsiveImageBundle

## Before release
- docs
- tests
- review all @TODO's
- make sure all dependencies are correct in composer.json
- create generator for database notification
- email twig templates
- ensure configuration throws no errors if not set
- license all files
- suggested dependency on pusher lib


```
<script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        {#
        // required twig functions

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

        // var pusher = new Pusher('98d43ecdd69ded824074', {
        //     authEndpoint: '/admin/pusher/auth',
        //     cluster: 'eu',
        //     encrypted: true
        // });
        
        // var channel = pusher.subscribe('{{ notification_pusher_channel_name(app.user) }}');
        {{ notification_new_pusher_js() }}
        
        {{ notification_new_pusher_channel_js(app.user) }}
        channel.bind('{{ notification_pusher_event }}', function (data) {
            alert(data.message);
        });
    </script>
```