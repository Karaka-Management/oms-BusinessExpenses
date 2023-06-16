<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\BusinessExpenses\Admin
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\BusinessExpenses\Admin;

use phpOMS\Module\StatusAbstract;

/**
 * Status class.
 *
 * @package Modules\BusinessExpenses\Admin
 * @license OMS License 2.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Status extends StatusAbstract
{
    /**
     * Path of the file
     *
     * @var string
     * @since 1.0.0
     */
    public const PATH = __DIR__;
}
