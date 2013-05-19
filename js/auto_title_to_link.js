/**
 * jQuery for plugin Auto Link To Title
 * @version 2013-05-11 
 */
jQuery(document).ready(
	function($){
		
console.log( 'jQuery ready for auto-link-to-title' );

		$( '#link-title-field' ).live(
			'focus',
			function() {
				
				var url   = $('#url-field').val();
				var nonce = $( '#_ajax_linking_nonce' ).val();
				var data  = { 'action' : 'auto_link_title', 'nonce' : nonce, 'url' : url };
				
				if ( undefined !== AutoInsertTitle.ajaxloaderUrl ) {
					$( '#link-title-field' ).css(
							{
								'background-image'    : 'url("'+AutoInsertTitle.ajaxloaderUrl+'")',
								'background-repeat'   : 'no-repeat',
								'background-position' : 'center right'
							}
					);
				}
				
console.log(data);

				$.post(
					ajaxurl,
					data,
					function(result){
						
console.log(result);

						if( undefined !== result.title && '' != result.title ){
							$( '#link-title-field' ).val( result.title );
						}
						
						$( '#link-title-field' ).css( 'background-image', 'none' );
					}
				);
			}
		);
		
		$( '#link-title-field' ).live(
				'blur',
				function() {
					$( '#link-title-field' ).css( 'background-image', 'none' );
				}
		);
	}
);