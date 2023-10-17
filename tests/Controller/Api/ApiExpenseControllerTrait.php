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

namespace Modules\BusinessExpenses\tests\Controller\Api;

use phpOMS\Account\AccountStatus;
use phpOMS\Account\AccountType;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\System\File\Local\Directory;
use phpOMS\Uri\HttpUri;
use phpOMS\Utils\RnG\DateTime;
use phpOMS\Utils\TestUtils;

trait ApiExpenseControllerTrait
{
    public function testExpenseCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('type', 1);
        $request->setData('description', 'Some test description');

        $this->module->apiExpenseCreate($request, $response);
        self::assertEquals('ok', $response->getData('')['status']);
        self::assertGreaterThan(0, $response->getDataArray('')['response']->id);
    }

    public function testExpenseElementCreate() : void
    {
        if (!\is_dir(__DIR__ . '/temp')) {
            \mkdir(__DIR__ . '/temp');
        }

        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;

        $request->setData('type', 1);
        $request->setData('description', 'Test description');
        $request->setData('expense', 1);

        $tmpInvoices = \scandir(__DIR__ . '/billing');
        $invoiceDocs = [];
        foreach ($tmpInvoices as $invoice) {
            if ($invoice !== '..' && $invoice !== '.') {
                $invoiceDocs[] = $invoice;
            }
        }

        $file = $invoiceDocs[0];
        \copy(__DIR__ . '/billing/' . $file, __DIR__ . '/temp/' . $file);

        $toUpload['file0'] = [
            'name'     => $file,
            'type'     => \explode('.', $file)[1],
            'tmp_name' => __DIR__ . '/temp/' . $file,
            'error'    => \UPLOAD_ERR_OK,
            'size'     => \filesize(__DIR__ . '/temp/' . $file),
        ];

        TestUtils::setMember($request, 'files', $toUpload);
        $this->module->apiExpenseElementCreate($request, $response);
        self::assertEquals('ok', $response->getData('')['status']);
        self::assertGreaterThan(0, $response->getDataArray('')['response']->id);

        if (\is_dir(__DIR__ . '/temp')) {
            Directory::delete(__DIR__ . '/temp');
        }
    }
}
