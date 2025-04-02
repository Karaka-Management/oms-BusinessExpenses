<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\BusinessExpenses\Controller\BackendController;
use Modules\BusinessExpenses\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^/businessexpenses/expense/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\BackendController:viewBusinessExpensesList',
            'verb'       => RouteVerb::GET,
            'active'     => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
    ],
    '^/businessexpenses/expense/create(\?.*$|$)' => [
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\BackendController:viewBusinessExpensesExpenseCreate',
            'verb'       => RouteVerb::GET,
            'active'     => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
    ],
    '^/businessexpenses/expense/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\BackendController:viewBusinessExpensesExpense',
            'verb'       => RouteVerb::GET,
            'active'     => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
    ],
    '^/businessexpenses/expense/element/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\BackendController:viewBusinessExpensesElement',
            'verb'       => RouteVerb::GET,
            'active'     => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
    ],
    '^/businessexpenses/expense/element/create(\?.*$|$)' => [
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\BackendController:viewBusinessExpensesElementCreate',
            'verb'       => RouteVerb::GET,
            'active'     => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
    ],
    '^/businessexpenses/type/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\BackendController:viewBusinessExpensesTypeList',
            'verb'       => RouteVerb::GET,
            'active'     => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
    ],
    '^/businessexpenses/type/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\BackendController:viewBusinessExpensesType',
            'verb'       => RouteVerb::GET,
            'active'     => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
    ],
    '^/businessexpenses/type/create(\?.*$|$)' => [
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\BackendController:viewBusinessExpensesTypeCreate',
            'verb'       => RouteVerb::GET,
            'active'     => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
    ],
];
