/**
 * jQuery for plugin Auto Link To Title
 * @version 2013-05-11 
 */
jQuery(document).ready(
	function($){
		$( '#url-field' ).live(
			'blur',
			function() {
				if ( undefined !== AutoInsertTitle.ajaxloaderUrl ) {
					$( '#link-title-field' ).css(
							{
								'background-image'    : 'url("'+AutoInsertTitle.ajaxloaderUrl+'")',
								'background-repeat'   : 'no-repeat',
								'background-position' : 'center right'
							}
					);
				}

				$.post(
					ajaxurl,
					{ 'action' : 'auto_link_title', 'nonce' : $( '#_ajax_linking_nonce' ).val(), 'url' : $(this).val() },
					function(result){
						if( undefined !== result.title ){
							$( '#link-title-field' ).val( result.title );
						}
						
						$( '#link-title-field' ).css( 'background-image', 'none' );
					}
				);
			}
		);
	}
);