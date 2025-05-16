<?php

declare(strict_types=1);

namespace Dotclear\Plugin\Documentation;

use Dotclear\App;
use Dotclear\Helper\Html\Form\{ Div, Label, Note, Para, Select, Text };
use Dotclear\Interface\Core\BlogSettingsInterface;

/**
 * @brief       Documentation module backend behaviors.
 * @ingroup     Documentation
 *
 * @author      Jean-Christian Paul Denis
 * @copyright   AGPL-3.0
 */
class BackendBehaviors
{
    /**
     * Blog pref form.
     */
    public static function adminBlogPreferencesFormV2(BlogSettingsInterface $blog_settings): void
    {
        echo (new Div())
            ->class('fieldset')
            ->items([
                (new Text('h4', My::name()))
                    ->id(My::id() . '_params'),
                (new Para())
                    ->items([
                        (new Select(My::id() . 'root_cat'))
                            ->items(Core::getCategoriesCombo())
                            ->default((string) (int) $blog_settings->get(My::id())->get('root_cat'))
                            ->label((new Label(__('Limit documentation to this category children:'), Label::OL_TF))),
                    ]),
                (new Note())
                    ->class('form-note')
                    ->text(__('Leave this empty to disable this feature.')),
            ])
            ->render();
    }

    /**
     * Blog pref update.
     */
    public static function adminBeforeBlogSettingsUpdate(BlogSettingsInterface $blog_settings): void
    {
        $blog_settings->get(My::id())->put('root_cat', (int) $_POST[My::id() . 'root_cat'] ?: 0, 'integer');
    }
}
