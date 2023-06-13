<?php
/**
 * Karaka
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

use Modules\Admin\Models\AccountMapper;
use Modules\Media\Models\MediaMapper;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;
use Modules\Editor\Models\EditorDocMapper;

/**
 *  mapper class.
 *
 * @package Modules\BusinessExpenses\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of Expense
 * @extends DataMapperFactory<T>
 */
final class ExpenseMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'bizexpenses_expense_id'          => ['name' => 'bizexpenses_expense_id',    'type' => 'int',    'internal' => 'id'],
        'bizexpenses_expense_status'      => ['name' => 'bizexpenses_expense_status', 'type' => 'int', 'internal' => 'status'],
        'bizexpenses_expense_description' => ['name' => 'bizexpenses_expense_description', 'type' => 'string', 'internal' => 'description'],
        'bizexpenses_expense_approved'    => ['name' => 'bizexpenses_expense_approved', 'type' => 'bool', 'internal' => 'approved'],
        'bizexpenses_expense_approvedby'    => ['name' => 'bizexpenses_expense_approvedby', 'type' => 'bool', 'internal' => 'approvedBy'],
        'bizexpenses_expense_paid'        => ['name' => 'bizexpenses_expense_paid', 'type' => 'bool', 'internal' => 'paid'],
        'bizexpenses_expense_net'         => ['name' => 'bizexpenses_expense_net', 'type' => 'Serializable', 'internal' => 'net'],
        'bizexpenses_expense_gross'       => ['name' => 'bizexpenses_expense_gross', 'type' => 'Serializable', 'internal' => 'gross'],
        'bizexpenses_expense_taxp'        => ['name' => 'bizexpenses_expense_taxp', 'type' => 'Serializable', 'internal' => 'taxP'],
        'bizexpenses_expense_created'     => ['name' => 'bizexpenses_expense_created', 'type' => 'DateTimeImmutable', 'internal' => 'createdAt'],
        'bizexpenses_expense_start'       => ['name' => 'bizexpenses_expense_start', 'type' => 'DateTime', 'internal' => 'start'],
        'bizexpenses_expense_end'         => ['name' => 'bizexpenses_expense_end', 'type' => 'DateTime', 'internal' => 'end'],
        'bizexpenses_expense_type'        => ['name' => 'bizexpenses_expense_type', 'type' => 'int', 'internal' => 'type'],
        'bizexpenses_expense_from'        => ['name' => 'bizexpenses_expense_from', 'type' => 'int', 'internal' => 'from'],
        'bizexpenses_expense_country'     => ['name' => 'bizexpenses_expense_country', 'type' => 'string', 'internal' => 'country'],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'elements' => [
            'mapper'       => ExpenseElementMapper::class,
            'table'        => 'bizexpenses_expense_element',
            'self'         => 'bizexpenses_expense_element_expense',
            'external'     => null,
        ],
        'files'        => [
            'mapper'   => MediaMapper::class,
            'table'    => 'bizexpenses_expense_media',
            'external' => 'bizexpenses_expense_media_dst',
            'self'     => 'bizexpenses_expense_media_src',
        ],
        'notes' => [
            'mapper'   => EditorDocMapper::class,       /* mapper of the related object */
            'table'    => 'bizexpenses_expense_note',         /* table of the related object, null if no relation table is used (many->1) */
            'external' => 'bizexpenses_expense_note_doc',
            'self'     => 'bizexpenses_expense_note_expense',
        ],
    ];

    /**
     * Belongs to.
     *
     * @var array<string, array{mapper:class-string, external:string, column?:string, by?:string}>
     * @since 1.0.0
     */
    public const BELONGS_TO = [
        'from' => [
            'mapper'     => AccountMapper::class,
            'external'   => 'bizexpenses_expense_from',
        ],
        'approvedBy' => [
            'mapper'     => AccountMapper::class,
            'external'   => 'bizexpenses_expense_approvedby',
        ],
    ];

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:class-string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [
        'type' => [
            'mapper'     => ExpenseTypeMapper::class,
            'external'   => 'bizexpenses_expense_type',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'bizexpenses_expense';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'bizexpenses_expense_id';

    /**
     * Model to use by the mapper.
     *
     * @var class-string<T>
     * @since 1.0.0
     */
    public const MODEL = Expense::class;
}
