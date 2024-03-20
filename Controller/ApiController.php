<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
use Modules\Billing\Models\BillMapper;
use Modules\BusinessExpenses\Models\Expense;
use Modules\BusinessExpenses\Models\ExpenseElement;
use Modules\BusinessExpenses\Models\ExpenseElementMapper;
use Modules\BusinessExpenses\Models\ExpenseElementTypeL11nMapper;
use Modules\BusinessExpenses\Models\ExpenseElementTypeMapper;
use Modules\BusinessExpenses\Models\ExpenseMapper;
use Modules\BusinessExpenses\Models\ExpenseStatus;
use Modules\BusinessExpenses\Models\ExpenseTypeL11nMapper;
use Modules\BusinessExpenses\Models\ExpenseTypeMapper;
use Modules\BusinessExpenses\Models\MediaType;
use Modules\BusinessExpenses\Models\PermissionCategory;
use Modules\Media\Models\CollectionMapper;
use Modules\Media\Models\MediaClass;
use Modules\Media\Models\MediaMapper;
use Modules\Media\Models\PathSettings;
use Modules\SupplierManagement\Models\NullSupplier;
use phpOMS\Account\PermissionType;
use phpOMS\Localization\BaseStringL11n;
use phpOMS\Localization\BaseStringL11nType;
use phpOMS\Localization\ISO639x1Enum;
use phpOMS\Localization\NullBaseStringL11nType;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\NotificationLevel;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
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
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseTypeCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateExpenseTypeCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        /** @var BaseStringL11nType $type */
        $type = $this->createExpenseTypeFromRequest($request);
        $this->createModel($request->header->account, $type, ExpenseTypeMapper::class, 'expense_type', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $type);
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
        $type->setL11n(
            $request->getDataString('title') ?? '',
            ISO639x1Enum::tryFromValue($request->getDataString('language')) ?? ISO639x1Enum::_EN
        );

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
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseTypeL11nCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateExpenseTypeL11nCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $typeL11n = $this->createExpenseTypeL11nFromRequest($request);
        $this->createModel($request->header->account, $typeL11n, ExpenseTypeL11nMapper::class, 'expense_type_l11n', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $typeL11n);
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
        $typeL11n           = new BaseStringL11n();
        $typeL11n->ref      = $request->getDataInt('type') ?? 0;
        $typeL11n->language = ISO639x1Enum::tryFromValue($request->getDataString('language')) ?? $request->header->l11n->language;
        $typeL11n->content  = $request->getDataString('title') ?? '';

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
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseElementTypeCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateExpenseElementTypeCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        /** @var BaseStringL11nType $type */
        $type = $this->createExpenseElementTypeFromRequest($request);
        $this->createModel($request->header->account, $type, ExpenseElementTypeMapper::class, 'expense_element_type', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $type);
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
        $type->setL11n(
            $request->getDataString('title') ?? '',
            ISO639x1Enum::tryFromValue($request->getDataString('language')) ?? ISO639x1Enum::_EN
        );

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
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseElementTypeL11nCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateExpenseElementTypeL11nCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $typeL11n = $this->createExpenseElementTypeL11nFromRequest($request);
        $this->createModel($request->header->account, $typeL11n, ExpenseElementTypeL11nMapper::class, 'expense_element_type_l11n', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $typeL11n);
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
        $typeL11n           = new BaseStringL11n();
        $typeL11n->ref      = $request->getDataInt('type') ?? 0;
        $typeL11n->language = ISO639x1Enum::tryFromValue($request->getDataString('language')) ?? $request->header->l11n->language;
        $typeL11n->content  = $request->getDataString('title') ?? '';

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
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateExpenseCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $expense = $this->createExpenseFromRequest($request);
        $this->createModel($request->header->account, $expense, ExpenseMapper::class, 'expense', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $expense);
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
        $expense->status      = ExpenseStatus::tryFromValue($request->getDataInt('status')) ?? ExpenseStatus::DRAFT;
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
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseElementCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateExpenseElementCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $element = $this->createExpenseElementFromRequest($request);
        $this->createModel($request->header->account, $element, ExpenseElementMapper::class, 'expense_element', $request->getOrigin());

        /* @var \Modules\BusinessExpenses\Models\Expense $expense */
        $old = ExpenseMapper::get()
            ->with('elements')
            ->where('id', (int) $request->getData('expense'))
            ->execute();

        $new = clone $old;

        if (!empty($request->files)) {
            $request->setData('element', $element->id, true);
            $this->apiMediaAddToExpenseElement($request, $response, $data);
        }

        $new->recalculate();
        $this->updateModel($request->header->account, $old, $new, ExpenseMapper::class, 'expense', $request->getOrigin());

        $this->createStandardCreateResponse($request, $response, $element);
    }

    /**
     * Api method to create expense element from an upload
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseElementFromUploadCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateExpenseElementCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $elements = [];

        foreach ($request->files as $file) {
            $internalResponse = new HttpResponse();
            $internalRequest  = new HttpRequest();

            $internalRequest->header->account = $request->header->account;
            $internalRequest->header->l11n    = $request->header->l11n;

            $internalRequest->setData('expense', $request->getDataInt('expense'));
            $internalRequest->setData('file_type', MediaType::BILL);
            $internalRequest->addFile($file);

            $this->apiExpenseElementCreate($internalRequest, $internalResponse, $data);

            $elements[] = $internalResponse->getDataArray($internalRequest->uri->__toString())['response'];
        }

        $this->createStandardCreateResponse($request, $response, $elements);
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

        // @todo fill from media if available

        // @todo handle different value set (net, gross, taxr, ...).
        // Depending on the value set the other values should be calculated
        $element->net   = new FloatInt($request->getDataInt('net') ?? 0);
        $element->taxP  = new FloatInt($request->getDataInt('taxp') ?? 0);
        $element->gross = new FloatInt($request->getDataInt('gross') ?? 0);

        if ($request->hasData('supplier')) {
            $element->supplier = new NullSupplier((int) $request->getData('supplier'));
        }

        // @todo use country of expense if no country is set
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
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiMediaAddToExpense(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateMediaAddToExpense($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidAddResponse($request, $response, $val);

            return;
        }

        /** @var \Modules\BusinessExpenses\Models\Expense $expense */
        $expense = ExpenseMapper::get()->where('id', (int) $request->getData('expense'))->execute();
        $path    = $this->createExpenseDir($expense);

        $uploaded = [];
        if (!empty($uploadedFiles = $request->files)) {
            $uploaded = $this->app->moduleManager->get('Media', 'Api')->uploadFiles(
                names: [],
                fileNames: [],
                files: $uploadedFiles,
                account: $request->header->account,
                basePath: __DIR__ . '/../../../Modules/Media/Files' . $path,
                virtualPath: $path,
                pathSettings: PathSettings::FILE_PATH,
                hasAccountRelation: false,
                readContent: $request->getDataBool('parse_content') ?? false
            );

            $collection = null;
            foreach ($uploaded as $media) {
                $this->createModelRelation(
                    $request->header->account,
                    $expense->id,
                    $media->id,
                    ExpenseMapper::class,
                    'files',
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

        $mediaFiles = $request->getDataJson('media');
        foreach ($mediaFiles as $media) {
            $this->createModelRelation(
                $request->header->account,
                $expense->id,
                (int) $media,
                ExpenseElementMapper::class,
                'files',
                '',
                $request->getOrigin()
            );
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
    private function validateMediaAddToExpense(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['media'] = (!$request->hasData('media') && empty($request->files)))
            || ($val['expense'] = !$request->hasData('expense'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create a bill
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiMediaAddToExpenseElement(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateMediaAddToExpenseElement($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidAddResponse($request, $response, $val);

            return;
        }

        /** @var \Modules\BusinessExpenses\Models\Expense $expense */
        $expense = ExpenseMapper::get()->where('id', (int) $request->getData('expense'))->execute();
        $path    = $this->createExpenseDir($expense);

        $element = (int) $request->getData('element');

        $uploaded = [];
        if (!empty($uploadedFiles = $request->files)) {
            $uploaded = $this->app->moduleManager->get('Media', 'Api')->uploadFiles(
                names: [],
                fileNames: [],
                files: $uploadedFiles,
                account: $request->header->account,
                basePath: __DIR__ . '/../../../Modules/Media/Files' . $path,
                virtualPath: $path,
                pathSettings: PathSettings::FILE_PATH,
                hasAccountRelation: false,
                readContent: $request->getDataBool('parse_content') ?? false
            );

            $collection = null;
            foreach ($uploaded as $media) {
                $this->createModelRelation(
                    $request->header->account,
                    $element,
                    $media->id,
                    ExpenseElementMapper::class,
                    'files',
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

        $mediaFiles = $request->getDataJson('media');
        foreach ($mediaFiles as $media) {
            $this->createModelRelation(
                $request->header->account,
                $element,
                (int) $media,
                ExpenseElementMapper::class,
                'files',
                '',
                $request->getOrigin()
            );
        }

        // Is invoice
        if ($request->getDataInt('file_type') === MediaType::BILL
            && \count($uploaded) + \count($mediaFiles) === 1
            && $this->app->moduleManager->isActive('Billing')
            && $expense->net->value !== 0
        ) {
            $internalResponse = new HttpResponse();
            $internalRequest  = new HttpRequest();

            $internalRequest->header->account = $request->header->account;
            $internalRequest->header->l11n    = $request->header->l11n;

            $internalRequest->setData('media', empty($uploaded) ? \reset($mediaFiles) : \reset($uploaded)->id);
            $internalRequest->setData('async', false);

            $this->app->moduleManager->get('Billing', 'ApiPurchase')->apiSupplierBillUpload($internalRequest, $internalResponse, $data);

            $bills = $internalResponse->getDataArray($internalRequest->uri->__toString())['response'];

            $elementObj = ExpenseElementMapper::get()
                ->where('id', $element)
                ->execute();

            $oldElement       = clone $elementObj;
            $elementObj->bill = \reset($bills);

            $bill = \Modules\Billing\Models\BillMapper::get()
                ->where('id', $elementObj->bill)
                ->execute();

            $elementObj->net      = $bill->netSales;
            $elementObj->taxP     = $bill->taxP;
            $elementObj->gross    = $bill->grossSales;
            $elementObj->supplier = $bill->supplier;
            $elementObj->country  = $bill->billCountry;

            $this->updateModel($request->header->account, $oldElement, $elementObj, ExpenseElementMapper::class, 'expense_element', $request->getOrigin());
        }

        $this->fillJsonResponse(
            $request,
            $response,
            NotificationLevel::OK,
            'Media', 'Media added to bill.',
            [
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
     * Api method to remove Media from ExpenseElement
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiMediaRemoveFromExpenseElement(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateMediaRemoveFromExpenseElement($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidRemoveResponse($request, $response, $val);

            return;
        }

        /** @var \Modules\Media\Models\Media $media */
        $media = MediaMapper::get()->where('id', (int) $request->getData('media'))->execute();

        /** @var \Modules\BusinessExpenses\Models\Expense $expense */
        $expense = ExpenseMapper::get()->where('id', (int) $request->getData('expense'))->execute();

        /** @var \Modules\BusinessExpenses\Models\ExpenseElement $element */
        $element = ExpenseElementMapper::get()->where('id', (int) $request->getData('element'))->execute();

        $path = \dirname($this->createExpenseDir($expense));

        /** @var \Modules\Media\Models\Collection $collection */
        $collection = CollectionMapper::get()
            ->where('name', (string) $element->id)
            ->where('virtual', $path)
            ->where('class', MediaClass::COLLECTION)
            ->limit(1)
            ->execute();

        if ($collection->id !== 0) {
            $this->deleteModelRelation(
                $request->header->account,
                $collection->id,
                $media->id,
                CollectionMapper::class,
                'sources',
                '',
                $request->getOrigin()
            );
        }

        $this->deleteModelRelation(
            $request->header->account,
            $element->id,
            $media->id,
            BillMapper::class,
            'files',
            '',
            $request->getOrigin()
        );

        $referenceCount = MediaMapper::countInternalReferences($media->id);

        if ($referenceCount === 0) {
            $this->deleteModel($request->header->account, $media, MediaMapper::class, 'element_media', $request->getOrigin());

            if (\is_dir($media->getAbsolutePath())) {
                \phpOMS\System\File\Local\Directory::delete($media->getAbsolutePath());
            } else {
                \phpOMS\System\File\Local\File::delete($media->getAbsolutePath());
            }
        }

        $this->createStandardDeleteResponse($request, $response, $media);
    }

    /**
     * Validate Media remove from ExpenseElement request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateMediaRemoveFromExpenseElement(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['media'] = !$request->hasData('media'))
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
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseFromUpload(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
    }

    /**
     * Api method to create item files
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiNoteCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateNoteCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $request->setData('virtualpath', '/Modules/BusinessExpenses/Items/' . $request->getData('id'), true);
        $this->app->moduleManager->get('Editor', 'Api')->apiEditorCreate($request, $response, $data);

        if ($response->header->status !== RequestStatusCode::R_200) {
            return;
        }

        $responseData = $response->getDataArray($request->uri->__toString());
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

    /**
     * Api method to update Note
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiNoteUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        $accountId = $request->header->account;
        if (!$this->app->accountManager->get($accountId)->hasPermission(
            PermissionType::MODIFY, $this->app->unitId, $this->app->appId, self::NAME, PermissionCategory::EXPENSE_NOTE, $request->getDataInt('id'))
        ) {
            $this->fillJsonResponse($request, $response, NotificationLevel::HIDDEN, '', '', []);
            $response->header->status = RequestStatusCode::R_403;

            return;
        }

        $this->app->moduleManager->get('Editor', 'Api')->apiEditorUpdate($request, $response, $data);
    }

    /**
     * Api method to delete Note
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiNoteDelete(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        $accountId = $request->header->account;
        if (!$this->app->accountManager->get($accountId)->hasPermission(
            PermissionType::DELETE, $this->app->unitId, $this->app->appId, self::NAME, PermissionCategory::EXPENSE_NOTE, $request->getDataInt('id'))
        ) {
            $this->fillJsonResponse($request, $response, NotificationLevel::HIDDEN, '', '', []);
            $response->header->status = RequestStatusCode::R_403;

            return;
        }

        $this->app->moduleManager->get('Editor', 'Api')->apiEditorDelete($request, $response, $data);
    }

    /**
     * Api method to update ExpenseType
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseTypeUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateExpenseTypeUpdate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $val);

            return;
        }

        /** @var BaseStringL11nType $old */
        $old = ExpenseTypeMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $new = $this->updateExpenseTypeFromRequest($request, clone $old);

        $this->updateModel($request->header->account, $old, $new, ExpenseTypeMapper::class, 'expense_type', $request->getOrigin());
        $this->createStandardUpdateResponse($request, $response, $new);
    }

    /**
     * Method to update ExpenseType from request.
     *
     * @param RequestAbstract    $request Request
     * @param BaseStringL11nType $new     Model to modify
     *
     * @return BaseStringL11nType
     *
     * @todo Implement API update function
     *
     * @since 1.0.0
     */
    public function updateExpenseTypeFromRequest(RequestAbstract $request, BaseStringL11nType $new) : BaseStringL11nType
    {
        $new->title = $request->getDataString('name') ?? $new->title;

        return $new;
    }

    /**
     * Validate ExpenseType update request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @todo Implement API validation function
     *
     * @since 1.0.0
     */
    private function validateExpenseTypeUpdate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = !$request->hasData('id'))) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to delete ExpenseType
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseTypeDelete(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateExpenseTypeDelete($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidDeleteResponse($request, $response, $val);

            return;
        }

        /** @var BaseStringL11nType $expenseType */
        $expenseType = ExpenseTypeMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $this->deleteModel($request->header->account, $expenseType, ExpenseTypeMapper::class, 'expense_type', $request->getOrigin());
        $this->createStandardDeleteResponse($request, $response, $expenseType);
    }

    /**
     * Validate ExpenseType delete request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateExpenseTypeDelete(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = !$request->hasData('id'))) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to update ExpenseTypeL11n
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseTypeL11nUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateExpenseTypeL11nUpdate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $val);

            return;
        }

        /** @var BaseStringL11n $old */
        $old = ExpenseTypeL11nMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $new = $this->updateExpenseTypeL11nFromRequest($request, clone $old);

        $this->updateModel($request->header->account, $old, $new, ExpenseTypeL11nMapper::class, 'expense_type_l11n', $request->getOrigin());
        $this->createStandardUpdateResponse($request, $response, $new);
    }

    /**
     * Method to update ExpenseTypeL11n from request.
     *
     * @param RequestAbstract $request Request
     * @param BaseStringL11n  $new     Model to modify
     *
     * @return BaseStringL11n
     *
     * @todo Implement API update function
     *
     * @since 1.0.0
     */
    public function updateExpenseTypeL11nFromRequest(RequestAbstract $request, BaseStringL11n $new) : BaseStringL11n
    {
        $new->language = ISO639x1Enum::tryFromValue($request->getDataString('language')) ?? $new->language;
        $new->content  = $request->getDataString('title') ?? $new->content;

        return $new;
    }

    /**
     * Validate ExpenseTypeL11n update request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateExpenseTypeL11nUpdate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = !$request->hasData('id'))
            || ($val['title'] = !$request->hasData('title'))
            || ($val['language'] = $request->hasData('language') && !ISO639x1Enum::isValidValue($request->getDataString('language')))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to delete ExpenseTypeL11n
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseTypeL11nDelete(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateExpenseTypeL11nDelete($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidDeleteResponse($request, $response, $val);

            return;
        }

        $expenseTypeL11n = ExpenseTypeL11nMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $this->deleteModel($request->header->account, $expenseTypeL11n, ExpenseTypeL11nMapper::class, 'expense_type_l11n', $request->getOrigin());
        $this->createStandardDeleteResponse($request, $response, $expenseTypeL11n);
    }

    /**
     * Validate ExpenseTypeL11n delete request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateExpenseTypeL11nDelete(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = !$request->hasData('id'))) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to update ExpenseElementType
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseElementTypeUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateExpenseElementTypeUpdate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $val);

            return;
        }

        /** @var BaseStringL11nType $old */
        $old = ExpenseElementTypeMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $new = $this->updateExpenseElementTypeFromRequest($request, clone $old);

        $this->updateModel($request->header->account, $old, $new, ExpenseElementTypeMapper::class, 'expense_element_type', $request->getOrigin());
        $this->createStandardUpdateResponse($request, $response, $new);
    }

    /**
     * Method to update ExpenseElementType from request.
     *
     * @param RequestAbstract    $request Request
     * @param BaseStringL11nType $new     Model to modify
     *
     * @return BaseStringL11nType
     *
     * @todo Implement API update function
     *
     * @since 1.0.0
     */
    public function updateExpenseElementTypeFromRequest(RequestAbstract $request, BaseStringL11nType $new) : BaseStringL11nType
    {
        $new->title = $request->getDataString('name') ?? $new->title;

        return $new;
    }

    /**
     * Validate ExpenseElementType update request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @todo Implement API validation function
     *
     * @since 1.0.0
     */
    private function validateExpenseElementTypeUpdate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = !$request->hasData('id'))) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to delete ExpenseElementType
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseElementTypeDelete(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        // @todo make sure can be deleted
        if (!empty($val = $this->validateExpenseElementTypeDelete($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidDeleteResponse($request, $response, $val);

            return;
        }

        /** @var BaseStringL11nType $expenseElementType */
        $expenseElementType = ExpenseElementTypeMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $this->deleteModel($request->header->account, $expenseElementType, ExpenseElementTypeMapper::class, 'expense_element_type', $request->getOrigin());
        $this->createStandardDeleteResponse($request, $response, $expenseElementType);
    }

    /**
     * Validate ExpenseElementType delete request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateExpenseElementTypeDelete(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = !$request->hasData('id'))) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to update ExpenseElementTypeL11n
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseElementTypeL11nUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateExpenseElementTypeL11nUpdate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $val);

            return;
        }

        /** @var BaseStringL11n $old */
        $old = ExpenseElementTypeL11nMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $new = $this->updateExpenseElementTypeL11nFromRequest($request, clone $old);

        $this->updateModel($request->header->account, $old, $new, ExpenseElementTypeL11nMapper::class, 'expense_element_type_l11n', $request->getOrigin());
        $this->createStandardUpdateResponse($request, $response, $new);
    }

    /**
     * Method to update ExpenseElementTypeL11n from request.
     *
     * @param RequestAbstract $request Request
     * @param BaseStringL11n  $new     Model to modify
     *
     * @return BaseStringL11n
     *
     * @todo Implement API update function
     *
     * @since 1.0.0
     */
    public function updateExpenseElementTypeL11nFromRequest(RequestAbstract $request, BaseStringL11n $new) : BaseStringL11n
    {
        $new->language = ISO639x1Enum::tryFromValue($request->getDataString('language')) ?? $new->language;
        $new->content  = $request->getDataString('title') ?? $new->content;

        return $new;
    }

    /**
     * Validate ExpenseElementTypeL11n update request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @todo Implement API validation function
     *
     * @since 1.0.0
     */
    private function validateExpenseElementTypeL11nUpdate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = !$request->hasData('id'))
            || ($val['title'] = !$request->hasData('title'))
            || ($val['language'] = $request->hasData('language') && !ISO639x1Enum::isValidValue($request->getDataString('language')))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to delete ExpenseElementTypeL11n
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseElementTypeL11nDelete(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateExpenseElementTypeL11nDelete($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidDeleteResponse($request, $response, $val);

            return;
        }

        $expenseElementTypeL11n = ExpenseElementTypeL11nMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $this->deleteModel($request->header->account, $expenseElementTypeL11n, ExpenseElementTypeL11nMapper::class, 'expense_element_type_l11n', $request->getOrigin());
        $this->createStandardDeleteResponse($request, $response, $expenseElementTypeL11n);
    }

    /**
     * Validate ExpenseElementTypeL11n delete request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateExpenseElementTypeL11nDelete(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = !$request->hasData('id'))) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to update Expense
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateExpenseUpdate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $val);

            return;
        }

        /** @var \Modules\BusinessExpenses\Models\Expense $old */
        $old = ExpenseMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $new = $this->updateExpenseFromRequest($request, clone $old);

        $this->updateModel($request->header->account, $old, $new, ExpenseMapper::class, 'expense', $request->getOrigin());
        $this->createStandardUpdateResponse($request, $response, $new);
    }

    /**
     * Method to update Expense from request.
     *
     * @param RequestAbstract $request Request
     * @param Expense         $new     Model to modify
     *
     * @return Expense
     *
     * @todo Implement API update function
     *
     * @since 1.0.0
     */
    public function updateExpenseFromRequest(RequestAbstract $request, Expense $new) : Expense
    {
        $new->type        = $request->hasData('type') ? new NullBaseStringL11nType((int) $request->getDataInt('type')) : $new->type;
        $new->status      = ExpenseStatus::tryFromValue($request->getDataInt('status')) ?? $new->status;
        $new->description = $request->getDataString('description') ?? $new->description;
        $new->country     = $request->getDataString('country') ?? $new->country;

        return $new;
    }

    /**
     * Validate Expense update request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @todo Implement API validation function
     *
     * @since 1.0.0
     */
    private function validateExpenseUpdate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = !$request->hasData('id'))) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to delete Expense
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseDelete(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateExpenseDelete($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidDeleteResponse($request, $response, $val);

            return;
        }

        /** @var \Modules\BusinessExpenses\Models\Expense $expense */
        $expense = ExpenseMapper::get()->where('id', (int) $request->getData('id'))->execute();

        // @todo delete elements
        // @todo delete media
        // @todo check external accounting references?

        $this->deleteModel($request->header->account, $expense, ExpenseMapper::class, 'expense', $request->getOrigin());
        $this->createStandardDeleteResponse($request, $response, $expense);
    }

    /**
     * Validate Expense delete request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateExpenseDelete(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = !$request->hasData('id'))) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to update ExpenseElement
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseElementUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateExpenseElementUpdate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $val);

            return;
        }

        /** @var \Modules\BusinessExpenses\Models\ExpenseElement $old */
        $old = ExpenseElementMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $new = $this->updateExpenseElementFromRequest($request, clone $old);
        $this->updateModel($request->header->account, $old, $new, ExpenseElementMapper::class, 'expense_element', $request->getOrigin());

        /* @var \Modules\BusinessExpenses\Models\Expense $expense */
        $old = ExpenseMapper::get()
            ->with('elements')
            ->where('id', (int) $request->getData('expense'))
            ->execute();

        $new = clone $old;
        $new->recalculate();
        $this->updateModel($request->header->account, $old, $new, ExpenseMapper::class, 'expense', $request->getOrigin());
        $this->createStandardUpdateResponse($request, $response, $new);
    }

    /**
     * Method to update ExpenseElement from request.
     *
     * @param RequestAbstract $request Request
     * @param ExpenseElement  $new     Model to modify
     *
     * @return ExpenseElement
     *
     * @todo Implement API update function
     *
     * @since 1.0.0
     */
    public function updateExpenseElementFromRequest(RequestAbstract $request, ExpenseElement $new) : ExpenseElement
    {
        $new->description = $request->getDataString('description') ?? $new->description;
        $new->type        = $request->hasData('type') ? new NullBaseStringL11nType((int) $request->getData('type')) : $new->type;

        // Depending on the value set the other values should be calculated
        $new->net      = $request->hasData('net') ? new FloatInt($request->getDataInt('net') ?? 0) : $new->net;
        $new->taxP     = $request->hasData('taxp') ? new FloatInt($request->getDataInt('taxp') ?? 0) : $new->taxP;
        $new->gross    = $request->hasData('gross') ? new FloatInt($request->getDataInt('gross') ?? 0) : $new->gross;
        $new->supplier = $request->hasData('supplier') ? new NullSupplier((int) $request->getData('supplier')) : $new->supplier;
        $new->country  = $request->getDataString('country') ?? $new->country;

        return $new;
    }

    /**
     * Validate ExpenseElement update request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @todo Implement API validation function
     *
     * @since 1.0.0
     */
    private function validateExpenseElementUpdate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = !$request->hasData('id'))) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to delete ExpenseElement
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiExpenseElementDelete(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateExpenseElementDelete($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidDeleteResponse($request, $response, $val);

            return;
        }

        // @todo delete media

        /** @var \Modules\BusinessExpenses\Models\ExpenseElement $expenseElement */
        $expenseElement = ExpenseElementMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $this->deleteModel($request->header->account, $expenseElement, ExpenseElementMapper::class, 'expense_element', $request->getOrigin());

        /* @var \Modules\BusinessExpenses\Models\Expense $expense */
        $old = ExpenseMapper::get()
            ->with('elements')
            ->where('id', (int) $request->getData('expense'))
            ->execute();

        $new = clone $old;
        $new->recalculate();
        $this->updateModel($request->header->account, $old, $new, ExpenseMapper::class, 'expense', $request->getOrigin());

        $this->createStandardDeleteResponse($request, $response, $expenseElement);
    }

    /**
     * Validate ExpenseElement delete request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateExpenseElementDelete(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = !$request->hasData('id'))) {
            return $val;
        }

        return [];
    }
}
