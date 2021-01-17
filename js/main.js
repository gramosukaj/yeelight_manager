function change_color_request(action, current_yeelight, color = null, brightness = null)
    {
        $.post(
            'request/commands.php',
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
            'request/commands.php',
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

function add_yeelight()
    {
        $.post(
            'request/post.php',
            {
                name: $('#newName').val(),
                ip_address : $('#newAdresseip').val(),
                type: $('#newType option:selected').val()
            },
            
            function(data)
            {
                if(data)
                {
                    $('#newName').val('');
                    $('#newAdresseip').val('');
                    $('#newType').val('0');
                    reload_yeelight_list();
                }
                else
                {
                    console.log("add yeelight not data");5
                }
            },
            'text'
        );
    }

function delete_yeelight(delete_id)
    {
        if(confirm("Êtes-vous sur de vouloir le supprimer ?"))
        {
            $.post(
                'request/delete.php',
                {
                    id: delete_id
                },

                function(data)
                {
                    if(data)
                    {
                        reload_yeelight_list();
                    }
                    else
                    {
                        console.log("delete yeelight not data");
                    }
                },
                'text'
            );
        }
    }

function edit_yeelight(edit_id)
    {
        if(confirm("Êtes-vous sur de vouloir modifier le produit ?"))
        {
            let editName = '#editName' + edit_id;
            let editAddressip = '#editAddressip' + edit_id;
            let editType = '#editType' + edit_id;
            $.post(
                'request/put.php',
                {
                    id: edit_id,
                    name: $(editName).val(),
                    ip_address: $(editAddressip).val(),
                    type: $(editType).val()
                },
                
                function(data)
                {
                    if(data)
                    {
                        reload_yeelight_list();
                    }
                    else
                    {
                        console.log("delete yeelight not data");
                    }
                },
                'text'
            );
        }
    }

function dark_mode()
    {
        console.log('dark_mode enabled');
    }

function reload_yeelight_list()
    {
        $('#listYeelight').load('request/list_yeelight.php');
    }
         