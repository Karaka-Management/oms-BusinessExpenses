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

use Modules\Admin\Models\Account;
use Modules\SupplierManagement\Models\Supplier;
use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Stdlib\Base\FloatInt;

/**
 *  Expense class.
 *
 * @package Modules\BusinessExpenses\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class ExpenseElement
{
    public int $id = 0;

    public int $expense = 0;

    public bool $approved = false;

    public string $description = '';

    public FloatInt $net;

    public FloatInt $taxR;

    public FloatInt $taxP;

    public FloatInt $gross;

    public FloatInt $quantity;

    public ExpenseElementType $type;

    public ?Account $ref = null;

    public array $media = [];

    public string $taxId = '';

    public ?Supplier $supplier = null;

    public string $country = ISO3166TwoEnum::_USA;

    public \DateTime $start;

    public \DateTime $end;

    public function __construct()
    {
        $this->type     = new ExpenseElementType();
        $this->net      = new FloatInt();
        $this->taxR     = new FloatInt();
        $this->taxP     = new FloatInt();
        $this->gross    = new FloatInt();
        $this->quantity = new FloatInt();
        $this->start    = new \DateTime('now');
        $this->end      = new \DateTime('now');
    }
}
