<?php

declare(strict_types=1);

namespace Dotclear\Plugin\Documentation;

use ArrayObject;
use Dotclear\App;
use Dotclear\Helper\Html\Form\Div;
use Dotclear\Helper\Html\Form\Img;
use Dotclear\Helper\Html\Form\Li;
use Dotclear\Helper\Html\Form\Link;
use Dotclear\Helper\Html\Form\None;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Ul;
use Dotclear\Helper\Html\Html;
use Dotclear\Helper\Network\Http;

/**
 * @brief       Documentation module template specifics.
 * @ingroup     Documentation
 *
 * @author      Jean-Christian Paul Denis
 * @copyright   AGPL-3.0
 */
class FrontendTemplate
{
    /**
     * Generic filter helper.
     *
     * @param   ArrayObject<string, mixed>  $attr       The attributes
     */
    private static function filter(ArrayObject $attr, string $res): string
    {
        return '<?php echo ' . sprintf(App::frontend()->template()->getFilters($attr), $res) . '; ?>';
    }

    /**
     * @param   ArrayObject<string, mixed>  $attr       The attributes
     */
    public static function DocumentationIf(ArrayObject $attr, string $content): string
    {
        $if   = [];
        $sign = fn ($a): string => (bool) $a ? '' : '!';

        $operator = isset($attr['operator']) ? App::frontend()->template()->getOperator($attr['operator']) : '&&';

        if (isset($attr['has_root_cat'])) {
            $if[] = $sign($attr['has_root_cat']) . '(' . My::class . "::settings()->get('root_cat') != '')";
        }

        if (isset($attr['is_root_cat'])) {
            $if[] = $sign($attr['is_root_cat']) . '(' . My::class . "::settings()->get('root_cat') == (App::frontend()->context()->categories?->f('cat_id') ?? (App::frontend()->context()->posts?->f('cat_id') ?? '-1')))";
        }

        return $if === [] ?
            $content :
            '<?php if(' . implode(' ' . $operator . ' ', $if) . ') : ?>' . $content . '<?php endif; ?>';
    }

    /**
     * @param   ArrayObject<string, mixed>  $attr       The attributes
     */
    public static function DocumentationCategoriesList(ArrayObject $attr): string
    {
        return self::filter($attr, self::class . '::getCatgoriesList(' .
            '$with_empty = ' . (empty($attr['with_empty']) ? 'false' : 'true') . ',' .
            '$with_posts = ' . (empty($attr['with_posts']) ? 'false' : 'true') .
            ')');
    }

    /**
     * @param   ArrayObject<string, mixed>  $attr       The attributes
     */
    public static function DocumentationLicenseBadge(ArrayObject $attr): string
    {
        return self::filter($attr, self::class . '::getLicenseBadge()');
    }

    public static function getCatgoriesList(bool $with_empty, bool $with_posts): string
    {
        $rs = Core::getCategories($with_empty);
        if ($rs->isEmpty()) {

            return '';
        }

        $res = '';

        $ref_level = $level = $rs->level - 1;
        while ($rs->fetch()) {
            if ($rs->level > $level) {
                $res .= str_repeat('<ul><li>', (int) ($rs->level - $level));
            } elseif ($rs->level < $level) {
                $res .= str_repeat('</li></ul>', (int) -($rs->level - $level));
            }

            if ($rs->level <= $level) {
                $res .= '</li><li>';
            }

            $res .= '<a href="' . App::blog()->url() . App::url()->getURLFor('category', $rs->cat_url) . '">' .
            Html::escapeHTML($rs->cat_title) . '</a>';

            if ($with_posts) {
                $posts = App::blog()->getPosts(['no_content' => true, 'cat_id' => $rs->f('cat_id'), 'order' => 'post_url ASC']);
                while ($posts->fetch()) {
                    $res .= '<br />- <a href="' . $posts->getURL() . '">' . Html::escapeHTML($posts->f('post_title')) . '</a>';
                }
            }

            $level = $rs->level;
        }

        if ($ref_level - $level < 0) {
            $res .= str_repeat('</li></ul>', (int) -($ref_level - $level));
        }

        return '<div class="documentation-categories">' . $res . '</div>';
    }

    public static function getLicenseBadge(): string
    {
        return (new Para())
            ->class('documentation-license')
            ->items([
                (new Link())
                    ->href(sprintf('http://creativecommons.org/licenses/%s/', Core::getLicense()))
                    ->items([
                        (new Img(sprintf('http://i.creativecommons.org/l/%s/80x15.png', Core::getLicense())))
                            ->title(sprintf('This work is licensed under a %s', Core::getLicenseTitle()))
                            ->alt(Core::getLicenseTitle()),
                    ]),
            ])
            ->render();
    }
}
