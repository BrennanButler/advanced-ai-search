# Services

## PostDataService
The PostDataService class is responsible for retrieving data for a particular post.

### Extending

#### Filters
***
`woo_search_post_data_title`

Filter the post title provided to all records.
##### Arguments
- `$title` - the title of the post
- `$post` - The `WP_Post` object or post ID
```php
add_filter("woo_search_post_data_title", function( $title, $post))
```
***
`woo_search_post_data_url`

Filter the post url provided to all records
##### Arguments
- `$permalink` - the permalink of the post
- `$post` - The `WP_Post` object or post ID
```php
add_filter("woo_search_post_data_title", function( $permalink, $post))
```
***
`woo_search_post_data_post_author`

Filter the post author name provided to all records.
##### Arguments
- `$authorName` - the permalink of the post
- `$post` - The `WP_Post` object or post ID
```php
add_filter("woo_search_post_data_post_author", function( $authorName, $post){
    
})
```
***
`woo_search_post_data_excerpt`

Filter the post excerpt provided to all records.
##### Arguments
- `$excpert` - the excerpt of the post
- `$post` - The `WP_Post` object or post ID
```php
add_filter("woo_search_post_data_excerpt", function( $excerpt, $post){

    // ...Filter

    return $excerpt;
})
```
***
`woo_search_post_data_word_count`

Filter the post word count provided to all records.
##### Arguments
- `$wordCount` - the excerpt of the post
- `$post` - The `WP_Post` object or post ID
```php
add_filter("woo_search_post_data_word_count", function( $wordCount, $post) {

    // ...Filter

    return $wordCount;
})
```
