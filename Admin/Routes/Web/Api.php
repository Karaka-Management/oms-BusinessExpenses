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

use Modules\BusinessExpenses\Controller\ApiController;
use Modules\BusinessExpenses\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/businessexpenses/expense/find(\?.*$|$)' => [
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\ApiController:apiExpenseFind',
            'verb'       => RouteVerb::GET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
    ],
    '^.*/businessexpenses/expense(\?.*|$)$' => [
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\ApiController:apiExpenseCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\ApiController:apiExpenseUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
    ],

    '^.*/businessexpenses/expense/type(\?.*|$)$' => [
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\ApiController:apiExpenseTypeCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\ApiController:apiExpenseTypeUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
    ],

    '^.*/businessexpenses/expense/type/l11n(\?.*|$)$' => [
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\ApiContractTypeController:apiExpenseTypeL11nCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\ApiContractTypeController:apiExpenseTypeL11nUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
    ],

    '^.*/businessexpenses/expense/file(\?.*|$)$' => [
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\ApiController:apiMediaAddToExpense',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
    ],

    '^.*/businessexpenses/expense/note(\?.*|$)$' => [
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\ApiController:apiNoteCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\ApiController:apiNoteUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\ApiController:apiNoteDelete',
            'verb'       => RouteVerb::DELETE,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::DELETE,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
    ],

    '^.*/businessexpenses/expense/element(\?.*|$)$' => [
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\ApiController:apiExpenseElementCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\ApiController:apiExpenseElementUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
    ],

    '^.*/businessexpenses/expense/element/type(\?.*|$)$' => [
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\ApiController:apiExpenseElementTypeCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\ApiController:apiExpenseElementTypeUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
    ],

    '^.*/businessexpenses/expense/element/type/l11n(\?.*|$)$' => [
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\ApiContractTypeController:apiExpenseElementTypeL11nCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\ApiContractTypeController:apiExpenseTypeL11nUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
    ],

    '^.*/businessexpenses/expense/element/file(\?.*|$)$' => [
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\ApiController:apiMediaAddToExpenseElement',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
    ],

    '^.*/businessexpenses/expense/element/note(\?.*|$)$' => [
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\ApiController:apiNoteCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\ApiController:apiNoteUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
        [
            'dest'       => '\Modules\BusinessExpenses\Controller\ApiController:apiNoteDelete',
            'verb'       => RouteVerb::DELETE,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::DELETE,
                'state'  => PermissionCategory::EXPENSE,
            ],
        ],
    ],
];