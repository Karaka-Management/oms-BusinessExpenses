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
class Expense
{
    public int $id = 0;

    public Account $from;

    public int $status = ExpenseStatus::DRAFT;

    public ExpenseType $type;

    public string $description = '';

    public bool $approved = false;

    public bool $paid = false;

    /**
     * Elements/costs
     *
     * @var ExpenseElement[]
     * @since 1.0.0
     */
    public array $elements = [];

    public array $media = [];

    public FloatInt $net;

    public FloatInt $gross;

    public FloatInt $taxP;

    public \DateTime $start;

    public \DateTime $end;

    public \DateTimeImmutable $createdAt;

    public string $country = ISO3166TwoEnum::_USA;

    public function __construct()
    {
        $this->type      = new ExpenseType();
        $this->start     = new \DateTime('now');
        $this->end       = new \DateTime('now');
        $this->createdAt = new \DateTimeImmutable('now');
        $this->from      = new Account();

        $this->net   = new FloatInt();
        $this->gross = new FloatInt();
        $this->taxP  = new FloatInt();
    }
}
