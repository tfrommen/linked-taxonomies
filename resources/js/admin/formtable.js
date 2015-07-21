/* global jQuery */
;( function( Plugin, $ ) {
	"use strict";

	Plugin.FormTable = {
		initialize: function() {
			this.$formTable = $( '.form-table' ).on( 'change', '[type="radio"]', function() {
				Plugin.FormTable.updateLinks( $( this ) );
			} );
		},
		updateLinks: function( $radio ) {
			var parts = $radio.attr( 'name' ).split( '][' ),
				source = parts[ 0 ].substr( parts[ 0 ].indexOf( '[' ) + 1 ),
				target = parts[ 1 ].substr( 0, parts[ 1 ].length - 1 ),
				$target = this.$formTable.find( '[type="radio"][name$="[' + target + '][' + source + ']"]' );

			switch ( $radio.val() ) {
				case '0':
					// Define possible bidirectional link to unidirectional now.
					if ( $target.filter( ':checked' ).val() == '2' ) {
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

	$( function() {
		Plugin.FormTable.initialize();
	} );

} )( Plugin, jQuery );
