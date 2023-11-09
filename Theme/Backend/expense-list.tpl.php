<?php

/**
 * Jingga
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

use Modules\BusinessExpenses\Models\ExpenseStatus;
use phpOMS\Uri\UriFactory;

/** @var \phpOMS\Views\View $this */
$expenses      = $this->data['expenses'] ?? [];
$expenseStatus = ExpenseStatus::getConstants();

echo $this->data['nav']->render(); ?>
<div class="row">
    <div class="col-xs-12">
        <section class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Expenses'); ?><i class="g-icon download btn end-xs">download</i></div>
            <div class="slider">
            <table id="iSalesClientList" class="default sticky">
                <thead>
                <tr>
                    <td>
                    <td><?= $this->getHtml('ID', '0', '0'); ?>
                        <label for="iSalesClientList-sort-1">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-1">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="iSalesClientList-sort-2">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-2">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                    <td><?= $this->getHtml('Status'); ?>
                        <label for="iSalesClientList-sort-3">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-3">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="iSalesClientList-sort-4">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-4">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                    <td><?= $this->getHtml('Paid'); ?>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                    <td><?= $this->getHtml('Approved'); ?>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                    <td class="wf-100"><?= $this->getHtml('From'); ?>
                        <label for="iSalesClientList-sort-5">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-5">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="iSalesClientList-sort-6">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-6">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                    <td><?= $this->getHtml('Amount'); ?>
                        <label for="iSalesClientList-sort-7">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-7">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="iSalesClientList-sort-8">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-8">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                    <td><?= $this->getHtml('Start'); ?>
                        <label for="iSalesClientList-sort-7">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-7">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="iSalesClientList-sort-8">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-8">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                    <td><?= $this->getHtml('End'); ?>
                        <label for="iSalesClientList-sort-7">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-7">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="iSalesClientList-sort-8">
                            <input type="radio" name="iSalesClientList-sort" id="iSalesClientList-sort-8">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                <tbody>
                <?php $count = 0; foreach ($expenses as $key => $value) : ++$count;
                 $url        = UriFactory::build('{/base}/businessexpenses/expense?{?}&id=' . $value->id);
                 ?>
                <tr data-href="<?= $url; ?>">
                    <td>
                    <td data-label="<?= $this->getHtml('ID', '0', '0'); ?>"><a href="<?= $url; ?>"><?= $value->id; ?></a>
                    <td data-label="<?= $this->getHtml('Status'); ?>"><a href="<?= $url; ?>"><?= $this->getHtml(':status' . $value->status); ?></a>
                    <td class="centerText" data-label="<?= $this->getHtml('Paid'); ?>"><a href="<?= $url; ?>"><i class="g-icon"><?= $value->paid ? 'check' : 'close'; ?></i></a>
                    <td class="centerText" data-label="<?= $this->getHtml('Approved'); ?>"><a href="<?= $url; ?>"><i class="g-icon"><?= $value->approved ? 'check' : 'close'; ?></i></a>
                    <td data-label="<?= $this->getHtml('From'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($this->renderUserName('%3$s %2$s %1$s', [$value->from->name1, $value->from->name2, $value->from->name3])); ?></a>
                    <td data-label="<?= $this->getHtml('Amount'); ?>"><a href="<?= $url; ?>"><?= $this->getCurrency($value->gross); ?></a>
                    <td data-label="<?= $this->getHtml('Start'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->start->format('Y-m-d')); ?></a>
                    <td data-label="<?= $this->getHtml('End'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->end->format('Y-m-d')); ?></a>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                    <tr><td colspan="9" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
            </div>
        </section>
    </div>
</div>
