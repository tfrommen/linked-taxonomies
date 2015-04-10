jQuery( function( $ ) {
	'use strict';

	var $formTable = $( '.form-table' );

	$formTable.find( '[type="radio"]' ).on( 'change', function() {
		var $this = $( this ),
			name = $this.attr( 'name' ),
			parts = name.split( '][' ),
			source = parts[ 0 ].substr( parts[ 0 ].indexOf( '[' ) + 1 ),
			target = parts[ 1 ].substr( 0, parts[ 1 ].length - 1 ),
			$target = $formTable.find( '[type="radio"][name$="[' + target + '][' + source + ']"]' );

		switch ( $this.val() ) {
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
	} );

} );
