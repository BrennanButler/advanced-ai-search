<?php
/**
 * 
 */

 namespace Test;
if (!(defined('WP_CLI') && \WP_CLI)) {
    return;
}


class Post {
    public function test() {
        \WP_CLI::log("Hello I'm post");
    }
}

add_filter("algolia_record_types", function($recordTypes) {
    $recordTypes["post"] = Post::class;
    return $recordTypes;
});

class Product {
    public function test() {
        \WP_CLI::log("Hello I'm product");
    }
}
add_filter("algolia_record_types", function($recordTypes) {
    $recordTypes["product"] = Product::class;
    return $recordTypes;
});


function getSupportedIndicies() {

    $postTypes = get_post_types();

    $postTypeIndicies = array_map(function($type) {
        return Post::class;
    }, $postTypes);


    // Remove unsupported post types
    unset($postTypeIndicies["revision"]);
    unset($postTypeIndicies["attachment"]);
    unset($postTypeIndicies["nav_menu_item"]);
    unset($postTypeIndicies["custom_css"]);
    unset($postTypeIndicies["customize_changeset"]);
    unset($postTypeIndicies["oembed_cache"]);
    unset($postTypeIndicies["user_request"]);
    unset($postTypeIndicies["wp_block"]);
    unset($postTypeIndicies["wp_template"]);
    unset($postTypeIndicies["wp_template_part"]);
    unset($postTypeIndicies["wp_global_styles"]);
    unset($postTypeIndicies["wp_navigation"]);
    unset($postTypeIndicies["wp_font_family"]);
    unset($postTypeIndicies["wp_font_face"]);

    // Add proper Woo support
    if ( isset( $postTypeIndicies["product"] ) ) {
        $postTypeIndicies["product"] = Product::class;
    }
    
    return $postTypeIndicies;
};

add_filter("algolia_indicies", function($indicies) {

    $indicies["wank"] = "hah";
    return $indicies;
});



class SearchCLI {

    public function test() {

        $test = array();

        $recordTypes = apply_filters("algolia_record_types", []);

        \WP_CLI::log("CLI is working!!");
        \WP_CLI::log(print_r($recordTypes, true));
        \WP_CLI::log("test");

        $record = new $recordTypes["product"];
        $record->test();

        $indicies = apply_filters("algolia_indicies", getSupportedIndicies());
        \WP_CLI::log(print_r($indicies, true));

        $integration = new $indicies["integration"];
        $integration->test();
    }

    public function push_index() {

        /**
         * 1. Loop through all available indicies
         *  1.1 Configure the index
         *  1.2 Loop through all records of each index
         *  1.3 Save all records to index
         */

        
    }
}

\WP_CLI::add_command('algolia', __NAMESPACE__ . '\SearchCLI');