$(document).ready(function(){
       $(".autosuggest").keyup(function(){
           var search_term = $(this).attr("value");
           alert(search_term);
           $.post('http://localhost/traian3/new-pdo/ajax/extract_data_post', {search_term:search_term}, function (data){
                   //alert(data);
               $('.result').html(data);
               $('.result li').click(function(){
                   var result_value = $(this).text();
                   $('.autosuggest').attr('value', result_value);
                   $('.result').html('');
               });
           });
        });
    });

