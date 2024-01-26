<?php

namespace AC\Column\User;

use AC\Column;
use AC\Settings;

class LastPost extends Column
{

    public function __construct()
    {
        $this->set_type('column-latest_post');
        $this->set_label(__('Last Post', 'codepress-admin-columns'));
    }

    public function get_value($id)
    {
        $first_post_id = $this->get_raw_value($id);

        if ( ! $first_post_id) {
            return $this->get_empty_char();
        }

        $post = get_post($first_post_id);

        // TODO
        return $this->get_formatted_value($post->ID);
    }

    protected function get_related_post_type(): string
    {
        return (string)$this->get_option('post_type');
    }

    public function get_raw_value($user_id)
    {
        $posts = get_posts([
            'author'      => $user_id,
            'fields'      => 'ids',
            'number'      => 1,
            'post_status' => $this->get_related_post_stati(),
            'post_type'   => $this->get_related_post_type(),
        ]);

        return empty($posts) ? null : $posts[0];
    }

    public function get_related_post_stati(): array
    {
        // TODO
        return (array)$this->get_option('post_status') ?: [];
    }

    protected function register_settings()
    {
        $this->add_setting(new Settings\Column\PostType(true));
        $this->add_setting(new Settings\Column\PostStatus());
        $this->add_setting(new Settings\Column\Post());
    }

}