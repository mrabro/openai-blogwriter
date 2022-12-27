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
                        btn.setAttribute("data-title",data['openai[prompt]']);
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
        e.preventDefault();
        let data = {
            image: $(this).data("image"),
            title: $(this).data('title'),
            action: "save_image_to_library"
        }
        $.ajax({
            url: admin.ajax,
            type: 'post',
            data: data,
            success: function (response) {
                if(response.status){
                    alert("Image Saved");
                }
                // $(".openai_image_spinner").css("visibility","hidden");
            }
        });
    });

    $(document).on("click", "#generate-post-tags", function(e){
        e.preventDefault();
        let that = this;
        $(that).prop('disabled', true);
        $(that).text('loading..');
        let data = {
            post_id: $(this).data("id"),
            generate_blog_tags_meta_box: $('input#generate_blog_tags_meta_box').val(),
            action: "generate_tags_ajax_handler"
        };
        $.ajax({
            url: admin.ajax, // The WordPress AJAX URL
            type: 'POST',
            data,
            success: function(response) {
              $(that).prop('disabled', false);
              $(that).text('Re Generate Tags');
              if(response.status){
                let container = document.getElementById('tags_container');
                console.log(response.tags);
                for (const tag of response.tags) {
                    
                    // Create a checkbox element
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.value = tag;
                    checkbox.id = tag;
                  
                    // Create a label element
                    const label = document.createElement('label');
                    label.htmlFor = tag;
                    label.textContent = tag;
                  
                    // Add the checkbox and label to the container
                    container.appendChild(checkbox);
                    container.appendChild(label);
                }
                const button = document.createElement('button');
                button.type = 'button';
                button.textContent = 'Add selected tags to post';

                // Add the button to the container
                container.appendChild(button);

                // Add an event listener to the button
                button.addEventListener('click', function() {
                    button.classList.add('loading');
                    button.disabled = true;
                    // Set the button's text content to "Loading..."
                    button.textContent = 'Loading...';
                    // Get the selected tags
                    const selectedTags = [];
                    for (const checkbox of container.querySelectorAll('input[type="checkbox"]:checked')) {
                        selectedTags.push(checkbox.value);
                    }
                    let tagData = {
                        tags: selectedTags,
                        action: 'add_tags_ajax_handler',
                        post_id: response.post_id
                    }
                    console.log(tagData);
                    $.ajax({
                        url: admin.ajax, // The WordPress AJAX URL
                        type: 'POST',
                        data: tagData,
                        success: function(response) {
                            if(response.status){
                                location.reload();
                            } else {
                                alert(response.msg);
                            }
                            button.classList.remove('loading');
                            button.textContent = 'Add selected tags to post';
                            button.disabled = false;
                        }
                    });
                });
              }
            }
          });
    })

})( jQuery );
