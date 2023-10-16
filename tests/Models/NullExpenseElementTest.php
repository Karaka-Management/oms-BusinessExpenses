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

use Modules\BusinessExpenses\Models\NullExpenseElement;

/**
 * @internal
 */
final class NullExpenseElementTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\BusinessExpenses\Models\NullExpenseElement
     * @group module
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\BusinessExpenses\Models\ExpenseElement', new NullExpenseElement());
    }

    /**
     * @covers Modules\BusinessExpenses\Models\NullExpenseElement
     * @group module
     */
    public function testId() : void
    {
        $null = new NullExpenseElement(2);
        self::assertEquals(2, $null->id);
    }

    /**
     * @covers Modules\BusinessExpenses\Models\NullExpenseElement
     * @group module
     */
    public function testJsonSerialize() : void
    {
        $null = new NullExpenseElement(2);
        self::assertEquals(['id' => 2], $null);
    }
}
