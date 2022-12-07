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

    $(document).on("submit", "form#openai_image_form", function(e){
        e.preventDefault();
        $(".openai_image_spinner").css("visibility","visible");
        var data = $(this).serializeArray().reduce(function(obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});
        data.action = "generate_image";
        $.ajax({
            url: admin.ajax,
            type: 'post',
            data: data,
            success: function (response) {
                if(response.status){
                    response.images.forEach(function(image, i){
                        var img = document.createElement('img');
                        img.src = image.url;
                        var btn = document.createElement('button');
                        btn.setAttribute("class", "openai_generated_image");
                        btn.setAttribute("data-image",image.url);
                        btn.innerHTML = "Save image to Library"
                        $(".openai_images").append(img);
                        $(".openai_images").append(btn);
                    });
                }
                $(".openai_image_spinner").css("visibility","hidden");
            }
        });
    });

    $(document).on("click", ".openai_generated_image", function(e){
        console.log($(this).data("image"));
    });

})( jQuery );
