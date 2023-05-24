<?php

/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\ClientManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\BusinessExpenses\Models\NullExpense;
use phpOMS\Uri\UriFactory;

/** @var \phpOMS\Views\View $this */
$expense = $this->getData('expense') ?? new NullExpense();

echo $this->getData('nav')->render(); ?>

<div class="row">
    <div class="col-xs-12 col-lg-6">
        <section class="portlet highlight-2">
            <div class="portlet-body">
                <table class="wf-100">
                    <tr><td><?= $this->getHtml('Net'); ?>:
                        <td>
                    <tr><td><?= $this->getHtml('Gross'); ?>:
                        <td>
                </table>
            </div>
        </section>
    </div>

    <div class="col-xs-12 col-lg-6">
        <section class="portlet highlight-3">
            <div class="portlet-body">
                <table class="wf-100">
                    <tr><td><?= $this->getHtml('Approved'); ?>:
                        <td>
                    <tr><td><?= $this->getHtml('Paid'); ?>:
                        <td>
                </table>
            </div>
        </section>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <section class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Report'); ?></div>
            <div class="portlet-body">
                <div class="form-group">
                    <label for="iReportFrom"><?= $this->getHtml('From'); ?></label>
                    <input type="text" id="iReportFrom" name="name" value="<?= $this->printHtml($this->renderUserName('%3$s %2$s %1$s', [$expense->from->name1, $expense->from->name2, $expense->from->name3])); ?>" disabled>
                </div>

                <div class="form-group">
                    <label for="iCreatedAt"><?= $this->getHtml('CreatedAt'); ?></label>
                    <input type="datetime-local" id="iCreatedAt" name="created" value="<?= $expense->createdAt->format('Y-m-d\TH:i'); ?>" disabled>
                </div>

                <div class="form-group">
                    <label for="iExpenseStart"><?= $this->getHtml('Start'); ?></label>
                    <input type="datetime-local" id="iExpenseStart" name="start" value="<?= $expense->start->format('Y-m-d\TH:i'); ?>">
                </div>

                <div class="form-group">
                    <label for="iExpenseEnd"><?= $this->getHtml('End'); ?></label>
                    <input type="datetime-local" id="iExpenseEnd" name="end" value="<?= $expense->end->format('Y-m-d\TH:i'); ?>">
                </div>

                <div class="form-group">
                    <label for="iDescription"><?= $this->getHtml('Description'); ?></label>
                    <textarea id="iDescription" name="description"><?= $this->printHtml($expense->description); ?></textarea>
                </div>
            </div>
            <div class="portlet-foot"></div>
        </section>
    </div>

    <div class="col-xs-12 col-md-6">
        <section class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Notes'); ?><i class="fa fa-download floatRight download btn"></i></div>
            <div class="slider">
            <table id="iSalesClientList" class="default sticky">
                <thead>
                <tr>
                    <td>
                    <td class="wf-100"><?= $this->getHtml('Title'); ?>
                        <label for="iSalesClientList-sort-1">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-1">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="iSalesClientList-sort-2">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-2">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                <tbody>
            </table>
        </section>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <section class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Expenses'); ?><i class="fa fa-download floatRight download btn"></i></div>
            <div class="slider">
            <table id="iSalesClientList" class="default sticky">
                <thead>
                <tr>
                    <td>
                    <td><?= $this->getHtml('ID', '0', '0'); ?>
                        <label for="iSalesClientList-sort-1">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-1">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="iSalesClientList-sort-2">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-2">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                <tbody>
            </table>
        </section>
    </div>
</div>