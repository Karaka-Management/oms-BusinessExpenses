<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\BusinessExpenses\tests\Models;

use Modules\BusinessExpenses\Models\NullExpense;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\BusinessExpenses\Models\NullExpense::class)]
final class NullExpenseTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\BusinessExpenses\Models\Expense', new NullExpense());
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testId() : void
    {
        $null = new NullExpense(2);
        self::assertEquals(2, $null->id);
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testJsonSerialize() : void
    {
        $null = new NullExpense(2);
        self::assertEquals(['id' => 2], $null->jsonSerialize());
    }
}
