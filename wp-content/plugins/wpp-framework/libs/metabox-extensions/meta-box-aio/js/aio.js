( function ( document ) {
	var data = {
		"mb-admin-columns"          : ['premium',                    'ui', 'admin'            ],
		"mb-custom-table"           : ['premium',            'data'                           ],
		"mb-frontend-submission"    : ['premium', 'popular',         'ui',          'frontend'],
		"mb-revision"               : ['premium',            'data',       'admin'            ],
		"mb-settings-page"          : ['premium', 'popular', 'data',       'admin'            ],
		"mb-term-meta"              : ['premium', 'popular', 'data'                           ],
		"mb-user-meta"              : ['premium', 'popular', 'data'                           ],
		"mb-user-profile"           : ['premium',            'data', 'ui',          'frontend'],
		"meta-box-builder"          : ['premium', 'popular',         'ui', 'admin'            ],
		"meta-box-columns"          : ['premium',                    'ui',                    ],
		"meta-box-conditional-logic": ['premium', 'popular',         'ui'                     ],
		"meta-box-geolocation"      : ['premium',            'data'                           ],
		"meta-box-group"            : ['premium', 'popular', 'data', 'ui'                     ],
		"meta-box-include-exclude"  : ['premium', 'popular',         'ui'                     ],
		"meta-box-show-hide"        : ['premium',                    'ui'                     ],
		"meta-box-tabs"             : ['premium',                    'ui'                     ],
		"meta-box-template"         : ['premium',            'data', 'ui', 'admin'            ],
		"meta-box-tooltip"          : ['premium',                    'ui'                     ],
		"meta-box-updater"          : ['premium',                          'admin'            ],
	};
	var items = Array.prototype.slice.call( document.querySelectorAll( '.extension-list li' ) ),
		filters = document.querySelector( '.filters' );

	function show( item ) {
		item.classList.remove( 'hidden' );
	}

	function hide( item ) {
		item.classList.add( 'hidden' );
	}

	function filter( event ) {
		event.preventDefault();
		items.map( show );

		var filter = event.target.dataset.filter;
		if ( ! filter ) {
			return;
		}
		items.filter( function( item ) {
			var extension = item.querySelector( 'input' ).value;

			return ! data.hasOwnProperty( extension ) || -1 === data[extension].indexOf( filter );
		} ).map( hide );
	}

	filters.addEventListener( 'click', filter, false );
} )( document );