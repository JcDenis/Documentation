<?php

declare(strict_types=1);

namespace Dotclear\Plugin\Documentation;

use Dotclear\App;
use Dotclear\Core\Process;

/**
 * @brief       Documentation module frontend process.
 * @ingroup     Documentation
 *
 * @author      Jean-Christian Paul Denis
 * @copyright   AGPL-3.0
 */
class Frontend extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::FRONTEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        App::behavior()->addBehaviors([
            'publicPostBeforeGetPosts'          => FrontendBehaviors::publicPostBeforeGetPosts(...),
            'publicCategoryBeforeGetCategories' => FrontendBehaviors::publicCategoryBeforeGetCategories(...),
            'templatePrepareParams'             => FrontendBehaviors::templatePrepareParams(...),
        ]);

        return true;
    }
}
