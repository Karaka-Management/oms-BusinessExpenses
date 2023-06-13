<?php
/**
 * Karaka
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

use Modules\Admin\Models\AccountMapper;
use Modules\Admin\Models\NullAccount;
use Modules\BusinessExpenses\Models\Expense;
use Modules\BusinessExpenses\Models\ExpenseElement;
use Modules\BusinessExpenses\Models\ExpenseElementMapper;
use Modules\BusinessExpenses\Models\ExpenseElementTypeL11nMapper;
use Modules\BusinessExpenses\Models\ExpenseElementTypeMapper;
use Modules\BusinessExpenses\Models\ExpenseMapper;
use Modules\BusinessExpenses\Models\ExpenseStatus;
use Modules\BusinessExpenses\Models\ExpenseTypeL11nMapper;
use Modules\BusinessExpenses\Models\ExpenseTypeMapper;
use Modules\Media\Models\CollectionMapper;
use Modules\Media\Models\MediaMapper;
use Modules\Media\Models\PathSettings;
use Modules\SupplierManagement\Models\NullSupplier;
use phpOMS\Localization\BaseStringL11n;
use phpOMS\Localization\BaseStringL11nType;
use phpOMS\Localization\ISO639x1Enum;
use phpOMS\Localization\NullBaseStringL11nType;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\NotificationLevel;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Model\Message\FormValidation;
use phpOMS\Stdlib\Base\FloatInt;

/**
 * BusinessExpenses class.
 *
 * @package Modules\BusinessExpenses
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ApiController extends Controller
{
    /**
     * Api method to create a type
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseTypeCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateExpenseTypeCreate($request))) {
            $response->data[$request->uri->__toString()] = new FormValidation($val);
            $response->header->status                    = RequestStatusCode::R_400;

            return;
        }

        /** @var BaseStringL11nType $type */
        $type = $this->createExpenseTypeFromRequest($request);
        $this->createModel($request->header->account, $type, ExpenseTypeMapper::class, 'expense_type', $request->getOrigin());

        $this->fillJsonResponse(
            $request,
            $response,
            NotificationLevel::OK,
            '',
            $this->app->l11nManager->getText($response->header->l11n->language, '0', '0', 'SucessfulCreate'),
            $type
        );
    }

    /**
     * Method to create type from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return BaseStringL11nType Returns the created type from the request
     *
     * @since 1.0.0
     */
    public function createExpenseTypeFromRequest(RequestAbstract $request) : BaseStringL11nType
    {
        $type        = new BaseStringL11nType();
        $type->title = $request->getDataString('name') ?? '';
        $type->setL11n($request->getDataString('title') ?? '', $request->getDataString('language') ?? ISO639x1Enum::_EN);

        return $type;
    }

    /**
     * Validate type create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateExpenseTypeCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['name'] = !$request->hasData('name'))
            || ($val['title'] = !$request->hasData('title'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create expense attribute l11n
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseTypeL11nCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateExpenseTypeL11nCreate($request))) {
            $response->data['expense_type_l11n_create'] = new FormValidation($val);
            $response->header->status                   = RequestStatusCode::R_400;

            return;
        }

        $typeL11n = $this->createExpenseTypeL11nFromRequest($request);
        $this->createModel($request->header->account, $typeL11n, ExpenseTypeL11nMapper::class, 'expense_type_l11n', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Localization', 'Localization successfully created', $typeL11n);
    }

    /**
     * Method to create expense attribute l11n from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return BaseStringL11n
     *
     * @since 1.0.0
     */
    private function createExpenseTypeL11nFromRequest(RequestAbstract $request) : BaseStringL11n
    {
        $typeL11n      = new BaseStringL11n();
        $typeL11n->ref = $request->getDataInt('type') ?? 0;
        $typeL11n->setLanguage(
            $request->getDataString('language') ?? $request->header->l11n->language
        );
        $typeL11n->content = $request->getDataString('title') ?? '';

        return $typeL11n;
    }

    /**
     * Validate expense attribute l11n create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateExpenseTypeL11nCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['title'] = !$request->hasData('title'))
            || ($val['type'] = !$request->hasData('type'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create a type
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseElementTypeCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateExpenseElementTypeCreate($request))) {
            $response->data[$request->uri->__toString()] = new FormValidation($val);
            $response->header->status                    = RequestStatusCode::R_400;

            return;
        }

        /** @var BaseStringL11nType $type */
        $type = $this->createExpenseElementTypeFromRequest($request);
        $this->createModel($request->header->account, $type, ExpenseElementTypeMapper::class, 'expense_element_type', $request->getOrigin());

        $this->fillJsonResponse(
            $request,
            $response,
            NotificationLevel::OK,
            '',
            $this->app->l11nManager->getText($response->header->l11n->language, '0', '0', 'SucessfulCreate'),
            $type
        );
    }

    /**
     * Method to create type from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return BaseStringL11nType Returns the created type from the request
     *
     * @since 1.0.0
     */
    public function createExpenseElementTypeFromRequest(RequestAbstract $request) : BaseStringL11nType
    {
        $type        = new BaseStringL11nType();
        $type->title = $request->getDataString('name') ?? '';
        $type->setL11n($request->getDataString('title') ?? '', $request->getDataString('language') ?? ISO639x1Enum::_EN);

        return $type;
    }

    /**
     * Validate type create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateExpenseElementTypeCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['name'] = !$request->hasData('name'))
            || ($val['title'] = !$request->hasData('title'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create expense attribute l11n
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseElementTypeL11nCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateExpenseElementTypeL11nCreate($request))) {
            $response->data['expense_element_type_l11n_create'] = new FormValidation($val);
            $response->header->status                           = RequestStatusCode::R_400;

            return;
        }

        $typeL11n = $this->createExpenseElementTypeL11nFromRequest($request);
        $this->createModel($request->header->account, $typeL11n, ExpenseElementTypeL11nMapper::class, 'expense_element_type_l11n', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Localization', 'Localization successfully created', $typeL11n);
    }

    /**
     * Method to create expense attribute l11n from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return BaseStringL11n
     *
     * @since 1.0.0
     */
    private function createExpenseElementTypeL11nFromRequest(RequestAbstract $request) : BaseStringL11n
    {
        $typeL11n      = new BaseStringL11n();
        $typeL11n->ref = $request->getDataInt('type') ?? 0;
        $typeL11n->setLanguage(
            $request->getDataString('language') ?? $request->header->l11n->language
        );
        $typeL11n->content = $request->getDataString('title') ?? '';

        return $typeL11n;
    }

    /**
     * Validate expense attribute l11n create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateExpenseElementTypeL11nCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['title'] = !$request->hasData('title'))
            || ($val['type'] = !$request->hasData('type'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create expense attribute l11n
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateExpenseCreate($request))) {
            $response->data['expense_create'] = new FormValidation($val);
            $response->header->status         = RequestStatusCode::R_400;

            return;
        }

        $expense = $this->createExpenseFromRequest($request);
        $this->createModel($request->header->account, $expense, ExpenseMapper::class, 'expense', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Expense', 'Successfully created', $expense);
    }

    /**
     * Method to create expense attribute l11n from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return Expense
     *
     * @since 1.0.0
     */
    private function createExpenseFromRequest(RequestAbstract $request) : Expense
    {
        $expense              = new Expense();
        $expense->from        = new NullAccount((int) $request->header->account);
        $expense->type        = new NullBaseStringL11nType((int) $request->getDataInt('type'));
        $expense->status      = (int) ($request->getDataInt('status') ?? ExpenseStatus::DRAFT);
        $expense->description = $request->getDataString('description') ?? '';

        $country = $request->getDataString('country') ?? '';
        if (empty($country)) {
            $account = $this->app->accountManager->get($request->header->account);
            if ($account->id === 0) {
                $account = AccountMapper::get()->with('l11n')->where('id', $request->header->account)->execute();
            }

            $country = $account->l11n->country;
        }

        $expense->country = $country;

        return $expense;
    }

    /**
     * Validate expense attribute l11n create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateExpenseCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['type'] = !$request->hasData('type'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create expense attribute l11n
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseElementCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateExpenseElementCreate($request))) {
            $response->data['expense_element_create'] = new FormValidation($val);
            $response->header->status                 = RequestStatusCode::R_400;

            return;
        }

        $element = $this->createExpenseElementFromRequest($request);
        $this->createModel($request->header->account, $element, ExpenseElementMapper::class, 'expense_element', $request->getOrigin());

        if (!empty($request->files)) {
            $request->setData('element', $element->id, true);
            $this->apiMediaAddToExpenseElement($request, $response, $data);

            // @todo: refill element with parsed data from media (ocr)
        }

        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Element', 'Successfully created', $element);
    }

    /**
     * Method to create expense attribute l11n from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return ExpenseElement
     *
     * @since 1.0.0
     */
    private function createExpenseElementFromRequest(RequestAbstract $request) : ExpenseElement
    {
        $element              = new ExpenseElement();
        $element->expense     = (int) $request->getData('expense');
        $element->description = $request->getDataString('description') ?? '';
        $element->type        = new NullBaseStringL11nType((int) $request->getData('type'));

        // @todo: fill from media if available

        // @todo: handle different value set (net, gross, taxr, ...).
        // Depending on the value set the other values should be calculated
        $element->net      = new FloatInt($request->getDataInt('net') ?? 0);
        $element->taxR     = new FloatInt($request->getDataInt('taxr') ?? 0);
        $element->taxP     = new FloatInt($request->getDataInt('taxp') ?? 0);
        $element->gross    = new FloatInt($request->getDataInt('gross') ?? 0);
        $element->quantity = new FloatInt($request->getDataInt('quantity') ?? 0);

        if ($request->hasData('supplier')) {
            $element->supplier = new NullSupplier((int) $request->getData('supplier'));
        }

        // @todo: use country of expense if no country is set
        $country = $request->getDataString('country') ?? '';
        if (empty($country)) {
            $account = $this->app->accountManager->get($request->header->account);
            if ($account->id === 0) {
                $account = AccountMapper::get()->with('l11n')->where('id', $request->header->account)->execute();
            }

            $country = $account->l11n->country;
        }

        $element->country = $country;

        return $element;
    }

    /**
     * Api method to create a bill
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiMediaAddToExpenseElement(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateMediaAddToExpenseElement($request))) {
            $response->data[$request->uri->__toString()] = new FormValidation($val);
            $response->header->status                    = RequestStatusCode::R_400;

            return;
        }

        /** @var \Modules\BusinessExpenses\Models\Expense $expense */
        $expense = ExpenseMapper::get()->where('id', (int) $request->getData('expense'))->execute();
        $path    = $this->createExpenseDir($expense);

        $element = (int) $request->getData('element');

        $uploaded = [];
        if (!empty($uploadedFiles = $request->files)) {
            $uploaded = $this->app->moduleManager->get('Media')->uploadFiles(
                names: [],
                fileNames: [],
                files: $uploadedFiles,
                account: $request->header->account,
                basePath: __DIR__ . '/../../../Modules/Media/Files' . $path,
                virtualPath: $path,
                pathSettings: PathSettings::FILE_PATH,
                hasAccountRelation: false,
                readContent: (bool) ($request->getData('parse_content') ?? false)
            );

            $collection = null;
            foreach ($uploaded as $media) {
                $this->createModelRelation(
                    $request->header->account,
                    $element,
                    $media->id,
                    ExpenseElementMapper::class,
                    'media',
                    '',
                    $request->getOrigin()
                );

                if ($request->hasData('type')) {
                    $this->createModelRelation(
                        $request->header->account,
                        $media->id,
                        $request->getDataInt('type'),
                        MediaMapper::class,
                        'types',
                        '',
                        $request->getOrigin()
                    );
                }

                if ($collection === null) {
                    /** @var \Modules\Media\Models\Collection $collection */
                    $collection = MediaMapper::getParentCollection($path)->limit(1)->execute();

                    if ($collection->id === 0) {
                        $collection = $this->app->moduleManager->get('Media')->createRecursiveMediaCollection(
                            $path,
                            $request->header->account,
                            __DIR__ . '/../../../Modules/Media/Files' . $path,
                        );
                    }
                }

                $this->createModelRelation(
                    $request->header->account,
                    $collection->id,
                    $media->id,
                    CollectionMapper::class,
                    'sources',
                    '',
                    $request->getOrigin()
                );
            }
        }

        if (!empty($mediaFiles = $request->getDataJson('media'))) {
            foreach ($mediaFiles as $media) {
                $this->createModelRelation(
                    $request->header->account,
                    $element,
                    (int) $media,
                    ExpenseElementMapper::class,
                    'media',
                    '',
                    $request->getOrigin()
                );
            }
        }

        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Media', 'Media added to bill.', [
            'upload' => $uploaded,
            'media'  => $mediaFiles,
        ]);
    }

    /**
     * Method to validate bill creation from request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateMediaAddToExpenseElement(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['media'] = (!$request->hasData('media') && empty($request->files)))
            || ($val['expense'] = !$request->hasData('expense'))
            || ($val['element'] = !$request->hasData('element'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Validate expense attribute l11n create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateExpenseElementCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['expense'] = !$request->hasData('expense'))
            || ($val['type'] = !$request->hasData('type'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Create media directory path
     *
     * @param Expense $expense Expense
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function createExpenseDir(Expense $expense) : string
    {
        return '/Modules/BusinessExpenses/Expense/'
            . $this->app->unitId . '/'
            . $expense->createdAt->format('Y/m/d') . '/'
            . $expense->id;
    }

    /**
     * Api method to create bill files
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseFromUpload(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
    }

    /**
     * Api method to create item files
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiNoteCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : void
    {
        if (!empty($val = $this->validateNoteCreate($request))) {
            $response->data['expense_note_create'] = new FormValidation($val);
            $response->header->status              = RequestStatusCode::R_400;

            return;
        }

        $request->setData('virtualpath', '/Modules/BusinessExpenses/Items/' . $request->getData('id'), true);
        $this->app->moduleManager->get('Editor', 'Api')->apiEditorCreate($request, $response, $data);

        if ($response->header->status !== RequestStatusCode::R_200) {
            return;
        }

        $responseData = $response->get($request->uri->__toString());
        if (!\is_array($responseData)) {
            return;
        }

        $model = $responseData['response'];
        $this->createModelRelation($request->header->account, (int) $request->getData('id'), $model->id, ExpenseMapper::class, 'notes', '', $request->getOrigin());
    }

    /**
     * Validate item note create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateNoteCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = !$request->hasData('id'))
        ) {
            return $val;
        }

        return [];
    }
}
