<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\BusinessExpenses\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\BusinessExpenses\Models;

use phpOMS\Stdlib\Base\Enum;

/**
 * Permission category enum.
 *
 * @package Modules\BusinessExpenses\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class PermissionCategory extends Enum
{
    public const EXPENSE = 1;

    public const EXPENSE_NOTE = 2;
}
