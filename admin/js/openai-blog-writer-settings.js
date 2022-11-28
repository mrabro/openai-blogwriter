(function( $ ) {
	'use strict';
	$(document).on("submit", "form#openai_form", function(e){
        e.preventDefault();
        $(".openai_spinner").css("visibility","visible");
        var data = $(this).serializeArray().reduce(function(obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});
        data.action = "generate_blog";
        $.ajax({
            url: admin.ajax,
            type: 'post',
            data: data,
            success: function (response) {
                if(response.status){
                    $("textarea#openai_result").text(response.blog);
                    $("textarea#openai_result").after('<button class="btn btn-default" id="openai_save_post">Save post to draft</button>');
                }
                $(".openai_spinner").css("visibility","hidden");
            }
        });
    });

    $(document).on("click", "#openai_save_post", function(e){
        e.preventDefault();
        let post = $("textarea#openai_result").text();
        let title = $("input#openai_title").val();
        let data = {
            post,
            title,
            action: "openai_save_post"
        };
        $.ajax({
            url: admin.ajax,
            type: 'post',
            data: data,
            success: function (response) {
                if(response.status){
                    window.location.href = response.post
                }
            }
        });
        console.log(post);
    });

})( jQuery );
