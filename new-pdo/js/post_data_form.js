$('form.ajax').on('submit', function(){
    var that = $(this);
        url = that.attr('action');
        type = that.attr('method');
        data = {};
        
        that.find('[name]').each(function(index, value){
            var that = $(this);
                name = that.attr('name');
                value = that.val();
                
                data[name] = value;
        });
        
        $.ajax({
           url: url,
           type: type,
           data: data,
           success: function(response) {
               //console.log(response);
               document.getElementById('message').innerHTML = response;
            }
        });
        return false;
});

/*
$("form.ajax").submit(function(evt) {
    evt.preventDefault();

    var url = $(this).attr('action');
    var postData = $(this).serialize();

    $.post(url, postData, function(response){
            document.getElementById('message').innerHTML = response;
       
    });
});
*/