<?php

declare(strict_types=1);

namespace Dotclear\Plugin\Documentation;

use Dotclear\App;
use Dotclear\Helper\Process\TraitProcess;

/**
 * @brief       Documentation module frontend process.
 * @ingroup     Documentation
 *
 * @author      Jean-Christian Paul Denis
 * @copyright   AGPL-3.0
 */
class Frontend
{
    use TraitProcess;

    public static function init(): bool
    {
        return self::status(My::checkContext(My::FRONTEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        App::frontend()->template()->addBlocks([
            'DocumentationIf' => FrontendTemplate::DocumentationIf(...),
        ]);
        App::frontend()->template()->addValues([
            'DocumentationCategoriesList' => FrontendTemplate::DocumentationCategoriesList(...),
            'DocumentationLicenseBadge'   => FrontendTemplate::DocumentationLicenseBadge(...),
        ]);

        App::behavior()->addBehaviors([
            'urlHandlerBeforeGetData'           => FrontendBehaviors::urlHandlerBeforeGetData(...),
            'templatePrepareParams'             => FrontendBehaviors::templatePrepareParams(...),
            'publicHeadContent'                 => FrontendBehaviors::publicHeadContent(...),
        ]);

        return true;
    }
}
