var App = App || {};

App.initialize = function() {
    App.ws = new WSpace($('#workspace'), PlacementFactory);
    var $placement = null;
    $('#addPlacement').click(function() {
       $placement = App.ws.addPlacement();
    });

    $('#save').click(function() {
       var data = JSON.stringify(App.ws.export());
        $.ajax({
            type: 'POST',
            beforeSend: function (request) {
                request.setRequestHeader('X-OCTOBER-REQUEST-HANDLER', 'onSave');
            },
            data: 'data=' + data,
            processData: false,
            success: function(a, b) {
                console.log('success', a, b);
            },
            error: function(e) {
                console.log(e);
            }
        });
    });

    $('#restore').click(function() {
        App.ws.restore(JSON.parse(localStorage.getItem('state')));
    });

}

$(App.initialize);