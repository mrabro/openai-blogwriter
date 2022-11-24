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
							response = response.replace(/\n/g, "&#13;&#10");
							$(".textarea_block").html('<textarea cols="80" rows="20">'+response.replace(/\"/g, "")+'</textarea>');
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
							{/* <textarea cols="80" rows="20">{attributes.outlines}</textarea> */}
						</div>
					</div>
		},
		save(){}
	});

})( jQuery );
