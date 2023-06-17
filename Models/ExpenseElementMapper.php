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

use Modules\Admin\Models\AccountMapper;
use Modules\Media\Models\MediaMapper;
use Modules\SupplierManagement\Models\SupplierMapper;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;
use phpOMS\Localization\BaseStringL11n;

/**
 *  mapper class.
 *
 * @package Modules\BusinessExpenses\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of BaseStringL11n
 * @extends DataMapperFactory<T>
 */
final class ExpenseElementMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'bizexpenses_expense_element_id'          => ['name' => 'bizexpenses_expense_element_id',    'type' => 'int',    'internal' => 'id'],
        'bizexpenses_expense_element_description' => ['name' => 'bizexpenses_expense_element_description', 'type' => 'string', 'internal' => 'description'],
        'bizexpenses_expense_element_approved'    => ['name' => 'bizexpenses_expense_element_approved', 'type' => 'bool', 'internal' => 'approved'],
        'bizexpenses_expense_element_approvedby'    => ['name' => 'bizexpenses_expense_element_approvedby', 'type' => 'bool', 'internal' => 'approvedBy'],
        'bizexpenses_expense_element_net'         => ['name' => 'bizexpenses_expense_element_net', 'type' => 'Serializable', 'internal' => 'net'],
        'bizexpenses_expense_element_gross'       => ['name' => 'bizexpenses_expense_element_gross', 'type' => 'Serializable', 'internal' => 'gross'],
        'bizexpenses_expense_element_taxp'        => ['name' => 'bizexpenses_expense_element_taxp', 'type' => 'Serializable', 'internal' => 'taxP'],
        'bizexpenses_expense_element_taxr'        => ['name' => 'bizexpenses_expense_element_taxr', 'type' => 'Serializable', 'internal' => 'taxR'],
        'bizexpenses_expense_element_quantity'    => ['name' => 'bizexpenses_expense_element_quantity', 'type' => 'Serializable', 'internal' => 'quantity'],
        'bizexpenses_expense_element_taxid'       => ['name' => 'bizexpenses_expense_element_taxid', 'type' => 'string', 'internal' => 'taxId'],
        'bizexpenses_expense_element_start'       => ['name' => 'bizexpenses_expense_element_start', 'type' => 'DateTime', 'internal' => 'start'],
        'bizexpenses_expense_element_end'         => ['name' => 'bizexpenses_expense_element_end', 'type' => 'DateTime', 'internal' => 'end'],
        'bizexpenses_expense_element_supplier'    => ['name' => 'bizexpenses_expense_element_supplier', 'type' => 'int', 'internal' => 'supplier'],
        'bizexpenses_expense_element_ref'         => ['name' => 'bizexpenses_expense_element_ref', 'type' => 'int', 'internal' => 'ref'],
        'bizexpenses_expense_element_type'        => ['name' => 'bizexpenses_expense_element_type', 'type' => 'int', 'internal' => 'type'],
        'bizexpenses_expense_element_country'     => ['name' => 'bizexpenses_expense_element_country', 'type' => 'string', 'internal' => 'country'],
        'bizexpenses_expense_element_expense'     => ['name' => 'bizexpenses_expense_element_expense', 'type' => 'int', 'internal' => 'expense'],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'media'        => [
            'mapper'   => MediaMapper::class,
            'table'    => 'bizexpenses_expense_element_media',
            'external' => 'bizexpenses_expense_element_media_dst',
            'self'     => 'bizexpenses_expense_element_media_src',
        ],
    ];

    /**
     * Belongs to.
     *
     * @var array<string, array{mapper:class-string, external:string, column?:string, by?:string}>
     * @since 1.0.0
     */
    public const BELONGS_TO = [
        'ref' => [
            'mapper'     => AccountMapper::class,
            'external'   => 'bizexpenses_expense_element_ref',
        ],
        'supplier' => [
            'mapper'     => SupplierMapper::class,
            'external'   => 'bizexpenses_expense_element_supplier',
        ],
        'approvedBy' => [
            'mapper'     => AccountMapper::class,
            'external'   => 'bizexpenses_expense_element_approvedby',
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
            'mapper'     => ExpenseElementTypeMapper::class,
            'external'   => 'bizexpenses_expense_element_type',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'bizexpenses_expense_element';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'bizexpenses_expense_element_id';

    /**
     * Model to use by the mapper.
     *
     * @var class-string<T>
     * @since 1.0.0
     */
    public const MODEL = BaseStringL11n::class;
}
