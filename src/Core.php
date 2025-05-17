<?php

declare(strict_types=1);

namespace Dotclear\Plugin\Documentation;

use Dotclear\App;
use Dotclear\Database\MetaRecord;
use Dotclear\Helper\Html\Form\Option;
use Dotclear\Helper\Html\Html;

/**
 * @brief       Documentation module core class.
 * @ingroup     Documentation
 *
 * @author      Dotclear team
 * @copyright   AGPL-3.0
 */
class Core
{
    /**
     * @return  array<string, string>
     */
    public static function getLicenses(): array
    {
        return [
            'by-nc-sa/3.0' => 'Creative Commons Attribution NonCommercial ShareAlike 3.0 License',
        ];
    }

    public static function getLicense(): string
    {
        $license = My::settings()->get('license') ?: 'by-nc-sa/3.0';

        return in_array($license, self::getLicenses()) ? $license : 'by-nc-sa/3.0';
    }

    public static function getLicenseTitle(): string
    {
        return self::getLicenses()[self::getLicense()];
    }

    public static function getCategories(bool $with_empty = false): MetaRecord
    {
        return App::blog()->getCategories(App::task()->checkContext('BACKEND') ? [] : ['start' => self::getRootCategory(), 'without_empty' => !$with_empty]);
    }

    public static function getRootCategory(): int
    {
        return (int) (My::settings()->get('root_cat') ?: 0);
    }

    public static function getRootCategoryUrl(): string
    {
        return self::hasRootCategory() ? App::blog()->url() . App::url()->getURLFor('category', Html::sanitizeURL(App::blog()->getCategories(['cat_id' => self::getRootCategory()])->f('cat_url'))) : '';
    }

    public static function isRootCategory(int|string $id): bool
    {
        return self::getRootCategory() === (int) $id;
    }

    public static function hasRootCategory(): bool
    {
        return self::getRootCategory() !== 0;
    }

    /**
     * Returns an hierarchical categories combo.
     *
     * @return     array<Option>   The categories combo.
     */
    public static function getCategoriesCombo(): array
    {
        $categories_combo = [new Option(__('Do not use documentation'), '')];
        $root_cat         = self::getRootCategory();
        $rs               = self::getCategories();
        $level            = self::hasRootCategory() ? 1 : 0;

        while ($rs->fetch()) {
            if (!App::task()->checkContext('BACKEND') && self::isRootCategory($rs->f('cat_id'))) {
                continue;
            }
            $option = new Option(
                str_repeat('&nbsp;', (int) (($rs->level - $level) * 4)) . Html::escapeHTML($rs->cat_title),
                (string) $rs->cat_id
            );
            if ($rs->level - $level) {
                $option->class('sub-option' . ($rs->level - $level));
            }
            $categories_combo[] = $option;
        }

        return $categories_combo;
    }

    public static function isDocumentationCategory(int|string $cat_id): bool
    {
        $rs = self::getCategories();
        while ($rs->fetch()) {
            if (((int) $cat_id) == ((int) $rs->f('cat_id'))) {

                return true;
            }
        }

        return false;
    }
}
