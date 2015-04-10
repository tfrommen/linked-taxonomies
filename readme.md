# Linked Taxonomies

Have you ever had to work with two or more taxonomies that basically share the same terms? And then you got tired of propagating all the changes you made on one taxonomy's terms to the other taxonomies?

This is exactly when _Linked Taxonomies_ kicks in.

There are several good reasons for having individual taxonomies that consist of the same set of terms. One is if these taxonomies are registered for different sets of post types. Or _object_ types, to be more precise, as you can also use taxonomies and terms on users. Another good reason is using one _source_ taxonomy that is visible to certain user roles, and link one or more completely hidden _target_ taxonomies that you only use automatically in the background.

## Installation

1. [Download ZIP](https://github.com/tfrommen/linked-taxonomies/archive/master.zip).
1. Upload contents to the `/wp-content/plugins` directory on your web server.
1. Activate the plugin through the _Plugins_ menu in WordPress.
1. Find the new _Taxonomies_ menu item in the _Settings_ menu in your WordPress backend.

## Usage

What this plugin is all about is providing a means to link specific taxonomies, either unidirectionally or bidirectionally, and synchronize their terms. And doing this is quite simple. On the _Taxonomies_ admin page, you can set up links for your taxonomies. Hit the _Save Changes_ button, and you're done already. Any change on a linked taxonomy's terms will automatically be propagated to all linked taxonomies.

### Filters

In order to customize certain aspects of the plugin, it provides you with several filters. For each of these, a short description as well as a code example on how to alter the default behavior is given below. Just put the according code snippet in your theme's `functions.php` file or your _customization_ plugin, or to some other appropriate place.

#### `edit_linked_taxonomies_capability`

Editing linked taxonomies is restricted to a certain capability, which is by default `manage_options`.

```php
/**
 * Filter the capability required to edit the linked taxonomies.
 *
 * @param string $capability Capability required to edit the linked taxonomies.
 */
add_filter( 'edit_linked_taxonomies_capability', function() {
	
	return 'manage_categories';
} );
```

#### `linkable_taxonomies`

Depending on how exactly you want to work with the plugin, you may want to define which taxonomies are linkable. This filter provides the array of taxonomies queried according to the args. Feel free to remove whichever taxonomy you don't want to be available for linking. If all you would like to do is to set query args that will be passed to the `get_taxonomies` function, please have a look at the `linked_taxonomies_get_taxonomies_args` filter.

```php
/**
 * Customize the taxonomies that are available for linking.
 *
 * @param array $taxonomies Taxonomies available for linking.
 */
add_filter( 'linkable_taxonomies', function( $taxonomies ) {

	// Remove taxonomies that are not built in but public
	foreach ( $taxonomies as $key => $taxonomy ) {
		if ( ! $taxonomy->_builtin && $taxonomy->public ) {
			unset( $taxonomies[ $key ] );
		}
	}
	
	return $taxonomies;
} );
```

#### `linked_taxonomies_get_taxonomies_args`

If you want to alter the (by default empty) set of query args that are used for querying all linkable taxonomies, this filter is a good starting point. For more complex conditions/checks, please have a look at the more powerful `linkable_taxonomies` filter that provides the array of taxonomies available for linking.

```php
/**
 * Customize the args for getting all taxonomies.
 *
 * @param array $args Taxonomies args.
 */
add_filter( 'linked_taxonomies_get_taxonomies_args', function() {

	// Only list taxonomies that are hierarchical and show their individual tag cloud
	return array(
		'hierarchical'  => 1,
		'show_tagcloud' => 1,
	);
} );
```

#### `list_linked_taxonomies_capability`

Accessing the plugin's settings page is restricted, too. In order to distinguish between users who are only allowed to list linked taxonomies, and users, who are able to edit linked taxonomies, there are two individual capabilities. The default for accessing the settings page is `manage_categories`.

```php
/**
 * Filter the capability required to list the linked taxonomies.
 *
 * @param string $capability Capability required to list the linked taxonomies.
 */
add_filter( 'list_linked_taxonomies_capability', function() {
	
	return 'manage_options';
} );
```

## Contribution

If you have a feature request, or if you have developed the feature already, please feel free to use the Issues and/or Pull Requests section.

Of course, you can also provide me with translations if you would like to use the plugin in another not yet included language.
