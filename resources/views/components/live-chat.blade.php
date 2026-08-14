@if(setting('live_chat_enabled', false) && setting('chat_plugin_script'))
    {!! setting('chat_plugin_script') !!}
@endif