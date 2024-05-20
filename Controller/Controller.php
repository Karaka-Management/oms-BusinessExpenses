<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\BusinessExpenses
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\BusinessExpenses\Controller;

use phpOMS\Module\ModuleAbstract;

/**
 * BusinessExpenses class.
 *
 * @package Modules\BusinessExpenses
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Controller extends ModuleAbstract
{
    /**
     * Module path.
     *
     * @var string
     * @since 1.0.0
     */
    public const PATH = __DIR__ . '/../';

    /**
     * Module version.
     *
     * @var string
     * @since 1.0.0
     */
    public const VERSION = '1.0.0';

    /**
     * Module name.
     *
     * @var string
     * @since 1.0.0
     */
    public const NAME = 'BusinessExpenses';

    /**
     * Module id.
     *
     * @var int
     * @since 1.0.0
     */
    public const ID = 1001000000;

    /**
     * Providing.
     *
     * @var string[]
     * @since 1.0.0
     */
    public static array $providing = [];

    /**
     * Dependencies.
     *
     * @var string[]
     * @since 1.0.0
     */
    public static array $dependencies = [];
}
