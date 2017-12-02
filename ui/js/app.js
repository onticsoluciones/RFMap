(function(serverUrl)
{
    var ui = {
        pluginContainer: $("#plugin-container")
    };
    
    function loadPluginsConfiguration(callback)
    {
        $
            .when(
                $.getJSON(serverUrl + "/plugin"),
                $.getJSON(serverUrl + "/task")
            )
            .done(function(pluginResult, taskResult)
            {
                var plugins = {};

                pluginResult[0].forEach(function(plugin)
                {
                    plugins[plugin.name] = {
                        name: plugin.name,
                        enabled: plugin.enabled,
                        rescheduleAfter: null
                    };
                });

                taskResult[0].forEach(function(task)
                {
                    plugins[task.plugin].rescheduleAfter = task.reschedule_after;
                });

                var pluginArray = Object.keys(plugins).map(function (key) { return plugins[key]; });
                callback(pluginArray);
            });
    }
    
    function renderPluginsConfiguration(configuration)
    {
        configuration.forEach(function(plugin)
        {
            var node = $("#plugin-template").clone().removeClass("collapse");
            node.find(".plugin-name").html(plugin.name);
            node.find(".plugin-status").prop("checked", plugin.enabled).click(function()
            {
                updatePluginState(plugin.name, this.checked);
            });
            node.find(".plugin-interval").prop("value", plugin.rescheduleAfter).change(function()
            {
                updateTaskSchedule(plugin.name, this.value);
            });
            node.appendTo(ui.pluginContainer);
        });
    }
    
    function updatePluginState(name, enable)
    {
        $.ajax({
            url: serverUrl + "/plugin/" + name + "/state",
            type: "PUT",
            data: enable ? '1' : '0',
            contentType: 'text/plain',
            processData: false
        });
    }
    
    function updateTaskSchedule(name, interval)
    {
        $.ajax({
            url: serverUrl + "/task/" + name + "/interval",
            type: "PUT",
            data: interval,
            contentType: 'text/plain',
            processData: false
        });
    }
    
    loadPluginsConfiguration(renderPluginsConfiguration);
    
})("http://localhost:9000");