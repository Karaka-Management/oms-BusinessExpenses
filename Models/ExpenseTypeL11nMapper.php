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

use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;
use phpOMS\Localization\BaseStringL11n;

/**
 * Expense type l11n mapper class.
 *
 * @package Modules\BusinessExpenses\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of BaseStringL11n
 * @extends DataMapperFactory<T>
 */
final class ExpenseTypeL11nMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'bizexpenses_expense_type_l11n_id'    => ['name' => 'bizexpenses_expense_type_l11n_id',    'type' => 'int',    'internal' => 'id'],
        'bizexpenses_expense_type_l11n_title' => ['name' => 'bizexpenses_expense_type_l11n_title', 'type' => 'string', 'internal' => 'content', 'autocomplete' => true],
        'bizexpenses_expense_type_l11n_type'  => ['name' => 'bizexpenses_expense_type_l11n_type',  'type' => 'int',    'internal' => 'ref'],
        'bizexpenses_expense_type_l11n_lang'  => ['name' => 'bizexpenses_expense_type_l11n_lang',  'type' => 'string', 'internal' => 'language'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'bizexpenses_expense_type_l11n';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'bizexpenses_expense_type_l11n_id';

    /**
     * Model to use by the mapper.
     *
     * @var class-string<T>
     * @since 1.0.0
     */
    public const MODEL = BaseStringL11n::class;
}
