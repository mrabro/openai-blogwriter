const { registerBlockType } = wp.blocks;

(function( $ ) {
	'use strict';

	registerBlockType("openai/blog-outlines", {
		title: "Write Blog outlines",
		description: "To generate blog outlines based on your title",
		icon: 'format-image',
		category: 'text',
		attributes: {
			topic: {
				type: 'string'
			},
			outlines: {
				type: 'string'
			},
			outlinesDisplay:{
				type: 'boolean',
				default: false
			}
		},
		edit({ attributes, setAttributes}){
			
			function updateTopic(e){
				setAttributes({topic: e.target.value});
			}
			function fetchOutlines(e){
				e.preventDefault();
				let data = {
					action: 'fetch_outlines',
					topic: attributes.topic
				}
				$.ajax({
                    url: admin.ajax,
                    type: 'post',
                    data: data,
                    success: function (response) {
                        if(response.status == undefined){
							response = response.replace(/\"/g, "");
							response = response.replace(/\n/g, "<br/>");
							response = response.replace(/\\n/g, "<br/>");
							console.log(response);
							$(".textarea_block").html(response);
							// setAttributes({outlines: response});
							// setAttributes({outlinesDisplay: true});
						}
                    }
                });
			}
			return <div>
						<input placeholder="Enter your Topic" onChange={updateTopic} type='text' value={attributes.topic}/>
						<button onClick={fetchOutlines} class='btn btn-primary'>Fetch</button><br/>
						<div class='textarea_block'>
						</div>
					</div>
		},
		save({attributes}){
			$("body").on('DOMSubtreeModified', ".outlines_save", function() {
				console.log($(".outlines_save").html());
			});
			<div class="outlines_save">
				{attributes.outlines}
			</div>
		}
	});

})( jQuery );
