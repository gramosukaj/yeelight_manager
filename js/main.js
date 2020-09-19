function change_color_request(action, current_yeelight, color = null, brightness = null)
    {
        $.post(
            'commands.php',
            {
                action : action,
                current_yeelight : current_yeelight,
                value : [color, brightness]
            },
            
            function(data){
                
                if(data)
                {
                    data = JSON.parse(data);
                    let current_yeelight = data['yeelight'];
                    if(data['power'] == "on")
                    {
                        $('#' + current_yeelight + '_power_status').html("On");
                        $('#' + current_yeelight +'onoffswitch').addClass("checked", "checked");
                    }
                    else
                    {
                        $('#' + current_yeelight + '_power_status').html("Off");
                        $('#' + current_yeelight +'onoffswitch').removeClass("checked");
                    }
                    $('.' + current_yeelight + '_current_brightness').html(data["brightness"]);
                    $('#' + current_yeelight + '_current_brightness').html(data["brightness"]);
                    $('#' + current_yeelight + '_current_mode').html(data["mode"]);
                    $('#' + current_yeelight + '_color_picker').spectrum("option", "color", ''+ data['color']);
                }
                else
                {
                    console.log('not data change color request');
                }
            },
            'text'
        );
    }

function on_off(yeelight)
    {
        $.post(
            'commands.php',
            {
                action: 'power',
                current_yeelight : yeelight,
                value: ''
            },
            
            function(data){
                if(data)
                {
                    data = JSON.parse(data);
                    let current_yeelight = data['yeelight'];
                    if(data['power'] == "on")
                    {
                        $('#' + current_yeelight + '_power_status').html("Allumé");
                        $('#' + current_yeelight +'onoffswitch').addClass("checked", "checked");
                    }
                    else
                    {
                        $('#' + current_yeelight + '_power_status').html("Éteint");
                        $('#' + current_yeelight +'onoffswitch').removeClass("checked");
                    }
                    $('.' + current_yeelight + '_current_brightness').html(data["brightness"]);
                    $('#' + current_yeelight + '_current_brightness').html(data["brightness"]);
                    $('#' + current_yeelight + '_current_mode').html(data["mode"]);
                }
                else
                {
                    console.log("on/off not data");
                }
            },
            'text'
        );
    }
         