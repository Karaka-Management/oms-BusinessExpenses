<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\BusinessExpenses
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\BusinessExpenses\Controller;

use Modules\BusinessExpenses\Models\ExpenseMapper;
use phpOMS\Contract\RenderableInterface;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;

/**
 * BusinessExpenses class.
 *
 * @package Modules\BusinessExpenses
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 * @codeCoverageIgnore
 */
final class BackendController extends Controller
{
    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewBusinessExpensesList(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/BusinessExpenses/Theme/Backend/expense-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1001001001, $request, $response);

        $list = ExpenseMapper::getAll()
            ->with('from')
            ->execute();

        $view->data['expenses'] = $list;

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewBusinessExpensesExpense(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/BusinessExpenses/Theme/Backend/expense-view');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1001001001, $request, $response);

        $expense = ExpenseMapper::get()
            ->with('from')
            ->with('notes')
            ->with('elements')
            ->with('elements/type')
            ->with('elements/type/l11n')
            ->where('id', (int) $request->getData('id'))
            ->execute();

        $view->data['expense'] = $expense;

        $view->data['expense-notes'] = new \Modules\Editor\Theme\Backend\Components\Compound\BaseView($this->app->l11nManager, $request, $response);

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewBusinessExpensesExpenseCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/BusinessExpenses/Theme/Backend/expense-view');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1001001001, $request, $response);

        $expense = ExpenseMapper::get()
            ->with('from')
            ->where('id', (int) $request->getData('id'))
            ->execute();

        $view->data['expense'] = $expense;

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewBusinessExpensesTypeList(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/BusinessExpenses/Theme/Backend/expense-view');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1001001001, $request, $response);

        $expense = ExpenseMapper::get()
            ->with('from')
            ->where('id', (int) $request->getData('id'))
            ->execute();

        $view->data['expense'] = $expense;

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewBusinessExpensesType(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/BusinessExpenses/Theme/Backend/expense-view');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1001001001, $request, $response);

        $expense = ExpenseMapper::get()
            ->with('from')
            ->where('id', (int) $request->getData('id'))
            ->execute();

        $view->data['expense'] = $expense;

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewBusinessExpensesTypeCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/BusinessExpenses/Theme/Backend/expense-view');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1001001001, $request, $response);

        $expense = ExpenseMapper::get()
            ->with('from')
            ->where('id', (int) $request->getData('id'))
            ->execute();

        $view->data['expense'] = $expense;

        return $view;
    }
}
