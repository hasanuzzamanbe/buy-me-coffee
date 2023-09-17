<?php

namespace buyMeCoffee\Builder\EditorBlocks;

use BuyMeCoffee\Classes\Vite;

class EditorBlocks
{
    public function register()
    {
        $this->loadBlockEditorAssets();
    }

    public function registerBlocks()
    {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }

        if (get_user_option('rich_editing') !== 'true') {
            return;
        }

        $this->loadBlockEditorAssets();
    }

    public function loadBlockEditorAssets()
    {
        add_action('enqueue_block_editor_assets', function () {
            Vite::enqueueScript(
                'buymecoffee_block_editor',
                ('js/Editor/gutenBlock/gutenBlock.jsx'),
                array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-components'),
                WPM_BMC_VERSION,
                true
            );
        });

    }

}