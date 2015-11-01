var Plugin = Plugin || {};

/* global Plugin */
(
	function( $, Plugin ) {
		"use strict";

		var FormTable = {

			initialize: function() {
				var $formTable = $( '.form-table' );

				if ( $formTable.length ) {
					FormTable.$formTable = $formTable;

					$formTable.on( 'change', '[type="radio"]', Plugin.FormTable.updateLinks );
				}
			},

			updateLinks: function() {
				var $radio = $( this ),
					parts = $radio.attr( 'name' ).split( '][' ),
					source = parts[ 0 ].substr( parts[ 0 ].indexOf( '[' ) + 1 ),
					target = parts[ 1 ].substr( 0, parts[ 1 ].length - 1 ),
					$target = FormTable.$formTable.find( '[type="radio"][name$="[' + target + '][' + source + ']"]' );

				switch ( $radio.val() ) {
					case '0':
						// Re-define possible bidirectional link as unidirectional.
						if ( $target.filter( ':checked' ).val() === '2' ) {
							$target.val( [ '1' ] );
						}
						break;

					case '2':
						// Propagate bidirectional link.
						$target.val( [ '2' ] );
						break;
				}
			}

		};

		Plugin.FormTable = FormTable;

		$( FormTable.initialize );

	}
)( jQuery, Plugin );
