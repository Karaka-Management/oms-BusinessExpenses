<?php
/**
 * Jingga
 *
 * PHP Version 8.1
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
 * Expense status enum.
 *
 * @package Modules\BusinessExpenses\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class ExpenseStatus extends Enum
{
    public const DRAFT = 1;

    public const OPEN = 2;

    public const APPROVED = 3;

    public const DENIED = 3;
}
