<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\BusinessExpenses\Admin
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\BusinessExpenses\Admin;

use phpOMS\Application\ApplicationAbstract;
use phpOMS\Config\SettingsInterface;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Module\InstallerAbstract;
use phpOMS\Module\ModuleInfo;

/**
 * Installer class.
 *
 * @package Modules\BusinessExpenses\Admin
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Installer extends InstallerAbstract
{
    /**
     * Path of the file
     *
     * @var string
     * @since 1.0.0
     */
    public const PATH = __DIR__;

    /**
     * {@inheritdoc}
     */
    public static function install(ApplicationAbstract $app, ModuleInfo $info, SettingsInterface $cfgHandler) : void
    {
        parent::install($app, $info, $cfgHandler);

        /* Expense types */
        $fileContent = \file_get_contents(__DIR__ . '/Install/expensetypes.json');
        if ($fileContent === false) {
            return;
        }

        /** @var array $types */
        $types        = \json_decode($fileContent, true);
        $expenseTypes = self::createExpenseTypes($app, $types);

        /* Element types */
        $fileContent = \file_get_contents(__DIR__ . '/Install/elementtypes.json');
        if ($fileContent === false) {
            return;
        }

        /** @var array $types */
        $types        = \json_decode($fileContent, true);
        $elementTypes = self::createExpenseElementTypes($app, $types);
    }

    /**
     * Install fuel type
     *
     * @param ApplicationAbstract $app   Application
     * @param array               $types Type definition
     *
     * @return array
     *
     * @since 1.0.0
     */
    private static function createExpenseTypes(ApplicationAbstract $app, array $types) : array
    {
        /** @var array<string, array> $expenseTypes */
        $expenseTypes = [];

        /** @var \Modules\BusinessExpenses\Controller\ApiController $module */
        $module = $app->moduleManager->get('BusinessExpenses');

        /** @var array $type */
        foreach ($types as $type) {
            $response = new HttpResponse();
            $request  = new HttpRequest();

            $request->header->account = 1;
            $request->setData('name', $type['name'] ?? '');
            $request->setData('title', \reset($type['l11n']));
            $request->setData('language', \array_keys($type['l11n'])[0] ?? 'en');

            $module->apiExpenseTypeCreate($request, $response);

            $responseData = $response->getData('');
            if (!\is_array($responseData)) {
                continue;
            }

            $expenseTypes[$type['name']] = \is_array($responseData['response'])
                ? $responseData['response']
                : $responseData['response']->toArray();

            $isFirst = true;
            foreach ($type['l11n'] as $language => $l11n) {
                if ($isFirst) {
                    $isFirst = false;
                    continue;
                }

                $response = new HttpResponse();
                $request  = new HttpRequest();

                $request->header->account = 1;
                $request->setData('title', $l11n);
                $request->setData('language', $language);
                $request->setData('type', $expenseTypes[$type['name']]['id']);

                $module->apiExpenseTypeL11nCreate($request, $response);
            }
        }

        return $expenseTypes;
    }

    /**
     * Install fuel type
     *
     * @param ApplicationAbstract $app   Application
     * @param array               $types Type definition
     *
     * @return array
     *
     * @since 1.0.0
     */
    private static function createExpenseElementTypes(ApplicationAbstract $app, array $types) : array
    {
        /** @var array<string, array> $elementTypes */
        $elementTypes = [];

        /** @var \Modules\BusinessExpenses\Controller\ApiController $module */
        $module = $app->moduleManager->get('BusinessExpenses');

        /** @var array $type */
        foreach ($types as $type) {
            $response = new HttpResponse();
            $request  = new HttpRequest();

            $request->header->account = 1;
            $request->setData('name', $type['name'] ?? '');
            $request->setData('title', \reset($type['l11n']));
            $request->setData('language', \array_keys($type['l11n'])[0] ?? 'en');

            $module->apiExpenseElementTypeCreate($request, $response);

            $responseData = $response->getData('');
            if (!\is_array($responseData)) {
                continue;
            }

            $elementTypes[$type['name']] = \is_array($responseData['response'])
                ? $responseData['response']
                : $responseData['response']->toArray();

            $isFirst = true;
            foreach ($type['l11n'] as $language => $l11n) {
                if ($isFirst) {
                    $isFirst = false;
                    continue;
                }

                $response = new HttpResponse();
                $request  = new HttpRequest();

                $request->header->account = 1;
                $request->setData('title', $l11n);
                $request->setData('language', $language);
                $request->setData('type', $elementTypes[$type['name']]['id']);

                $module->apiExpenseElementTypeL11nCreate($request, $response);
            }
        }

        return $elementTypes;
    }
}
