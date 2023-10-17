<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\BusinessExpenses\tests\Models;

use Modules\BusinessExpenses\Models\NullExpense;

/**
 * @internal
 */
final class NullExpenseTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\BusinessExpenses\Models\NullExpense
     * @group module
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\BusinessExpenses\Models\Expense', new NullExpense());
    }

    /**
     * @covers Modules\BusinessExpenses\Models\NullExpense
     * @group module
     */
    public function testId() : void
    {
        $null = new NullExpense(2);
        self::assertEquals(2, $null->id);
    }

    /**
     * @covers Modules\BusinessExpenses\Models\NullExpense
     * @group module
     */
    public function testJsonSerialize() : void
    {
        $null = new NullExpense(2);
        self::assertEquals(['id' => 2], $null->jsonSerialize());
    }
}
