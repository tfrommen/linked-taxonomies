/* globals module, require */
module.exports = function( grunt ) {
	'use strict';

	var configObject = {
		config: {
			file         : 'linked-taxonomies.php',
			glotpress_url: 'http://translate.tfrommen.de',
			languages    : 'languages/',
			name         : 'Linked Taxonomies',
			path         : require( 'path' ),
			repository   : 'https://github.com/tfrommen/linked-taxonomies',
			scripts_path : 'src/assets/js/',
			scripts_src  : 'resources/js/',
			slug         : 'linked-taxonomies',
			src_path     : 'src/',
			styles_path  : 'src/assets/css/',
			styles_src   : 'resources/scss/',
			textdomain   : 'linked-taxonomies'
		},

		// https://github.com/nDmitry/grunt-autoprefixer
		autoprefixer: {
			options: {
				browsers: [
					'Android >= 2.1',
					'Chrome >= 21',
					'Explorer >= 7',
					'Firefox >= 17',
					'iOS >= 3',
					'Opera >= 12.1',
					'Safari >= 5.0'
				]
			},
			styles : {
				expand: true,
				cwd   : '<%= config.styles_path %>',
				dest  : '<%= config.styles_path %>',
				src   : [
					'*.css',
					'!*.min.css'
				]
			}
		},

		// https://github.com/gruntjs/grunt-contrib-concat
		concat: {
			options: {
				separator: '\n'
			},
			admin  : {
				src : [
					'<%= config.scripts_src %>admin.js',
					'<%= config.scripts_src %>admin/*.js'
				],
				dest: '<%= config.scripts_path %>admin.js'
			}
		},

		// https://github.com/gruntjs/grunt-contrib-cssmin
		cssmin: {
			styles: {
				options: {
					processImport: true
				},
				expand : true,
				cwd    : '<%= config.styles_path %>',
				dest   : '<%= config.styles_path %>',
				ext    : '.min.css',
				src    : [
					'*.css',
					'!*.min.css'
				]
			}
		},

		// https://github.com/markoheijnen/grunt-glotpress
		glotpress_download: {
			languages: {
				options: {
					domainPath: '<%= config.src_path %><%= config.languages %>',
					url       : '<%= config.glotpress_url %>',
					slug      : '<%= config.slug %>',
					textdomain: '<%= config.textdomain %>'
				}
			}
		},

		// https://github.com/gruntjs/grunt-contrib-jshint
		jshint: {
			options: {
				jshintrc: true
			},
			grunt  : {
				src: [ 'Gruntfile.js' ]
			},
			scripts: {
				expand: true,
				cwd   : '<%= config.scripts_src %>',
				src   : [
					'**/*.js',
					'!**/*.min.js'
				]
			}
		},

		// https://github.com/suisho/grunt-lineending
		lineending: {
			options: {
				eol      : 'lf',
				overwrite: true
			},
			scripts: {
				expand: true,
				cwd   : '<%= config.scripts_path %>',
				dest  : '<%= config.scripts_path %>',
				src   : [ '*.js' ]
			},
			styles : {
				expand: true,
				cwd   : '<%= config.styles_path %>',
				dest  : '<%= config.styles_path %>',
				src   : [ '*.css' ]
			}
		},

		// https://github.com/cedaro/grunt-wp-i18n
		makepot: {
			pot: {
				options: {
					cwd        : '<%= config.src_path %>',
					domainPath : '<%= config.languages %>',
					mainFile   : '<%= config.file %>',
					potComments: 'Copyright (C) {{year}} <%= config.name %>\nThis file is distributed under the same license as the <%= config.name %> package.',
					potFilename: '<%= config.textdomain %>.pot',
					potHeaders : {
						poedit                 : true,
						'report-msgid-bugs-to' : '<%= config.repository %>/issues',
						'x-poedit-keywordslist': true
					},
					processPot : function( pot ) {
						var exclude = [
							'Plugin Name of the plugin/theme',
							'Plugin URI of the plugin/theme',
							'Author of the plugin/theme',
							'Author URI of the plugin/theme',
							'translators: do not translate'
						];

						// Skip translations with the above defined meta comments
						for ( var translation in pot.translations[ '' ] ) {
							if ( !pot.translations[ '' ].hasOwnProperty( translation ) ) {
								continue;
							}

							if ( 'undefined' === typeof pot.translations[ '' ][ translation ].comments.extracted ) {
								continue;
							}

							if ( exclude.indexOf( pot.translations[ '' ][ translation ].comments.extracted ) >= 0 ) {
								delete pot.translations[ '' ][ translation ];
							}
						}

						return pot;
					}
				}
			}
		},

		// https://github.com/gruntjs/grunt-contrib-sass
		sass: {
			styles: {
				expand : true,
				cwd    : '<%= config.styles_src %>',
				dest   : '<%= config.styles_path %>',
				ext    : '.css',
				options: {
					style      : 'expanded',
					lineNumbers: false,
					noCache    : true
				},
				src    : [ '*.scss' ]
			}
		},

		// https://github.com/gruntjs/grunt-contrib-uglify
		uglify: {
			scripts: {
				expand: true,
				cwd   : '<%= config.scripts_path %>',
				dest  : '<%= config.scripts_path %>',
				src   : [
					'*.js',
					'!*.min.js'
				],
				rename: function( destBase, destPath ) {
					// Fix files with multiple dots
					destPath = destPath.replace( /(\.[^\/.]*)?$/, '.min.js' );

					return configObject.config.path.join( destBase || '', destPath );
				}
			}
		},

		// https://github.com/gruntjs/grunt-contrib-watch
		watch: {
			options: {
				dot     : true,
				spawn   : true,
				interval: 2000
			},
			grunt  : {
				files: 'Gruntfile.js',
				tasks: [ 'jshint:grunt' ]
			},
			scripts: {
				files: '<%= config.scripts_src %>**/*.js',
				tasks: [ 'jshint:scripts', 'concat', 'uglify', 'lineending:scripts' ]
			},
			styles : {
				files: [ '<%= config.scss_src %>**/*.scss' ],
				tasks: [ 'sass', 'autoprefixer', 'cssmin', 'lineending:styles' ]
			}
		}
	};

	configObject.jshint.dev = grunt.util._.merge(
		configObject.jshint.scripts,
		{
			options: {
				devel: true,
				force: true
			}
		}
	);

	grunt.initConfig( configObject );

	// https://github.com/sindresorhus/load-grunt-tasks
	require( 'load-grunt-tasks' )( grunt );

	grunt.registerTask( 'grunt', [ 'jshint:grunt' ] );
	grunt.registerTask( 'languages', [ 'makepot', 'glotpress_download' ] );
	grunt.registerTask( 'lineendings', [ 'lineending' ] );
	grunt.registerTask( 'scripts', [ 'jshint:scripts', 'concat', 'uglify', 'lineending:scripts' ] );
	grunt.registerTask( 'styles', [ 'sass', 'autoprefixer', 'cssmin', 'lineending:styles' ] );

	grunt.registerTask( 'devScripts', [ 'jshint:dev', 'concat', 'uglify', 'lineending:scripts' ] );

	grunt.registerTask( 'test', [ 'jshint' ] );
	grunt.registerTask( 'default', [ 'languages', 'devScripts', 'styles' ] );
	grunt.registerTask( 'production', [ 'languages', 'scripts', 'styles' ] );
};
