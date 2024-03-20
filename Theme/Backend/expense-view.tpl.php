<?php

/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\ClientManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\BusinessExpenses\Models\NullExpense;

/** @var \phpOMS\Views\View $this */
$expense  = $this->getData('expense') ?? new NullExpense();
$sessions = $this->data['sessions'] ?? [];

echo $this->data['nav']->render(); ?>
<div class="tabview tab-2">
    <div class="box">
        <ul class="tab-links">
            <li><label for="c-tab-1"><?= $this->getHtml('Overview'); ?></label>
            <li><label for="c-tab-2"><?= $this->getHtml('Notes'); ?></label>
            <li><label for="c-tab-3"><?= $this->getHtml('Expenses'); ?></label>
            <li><label for="c-tab-4"><?= $this->getHtml('Clocking'); ?></label>
        </ul>
    </div>
    <div class="tab-content">
        <input type="radio" id="c-tab-1" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-1' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-lg-6">
                    <section class="portlet hl-2">
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
                    <section class="portlet hl-3">
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
            </div>
        </div>

        <input type="radio" id="c-tab-2" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-2' ? ' checked' : ''; ?>>
        <div class="tab">
            <?= $this->data['expense-notes']->render('expense-notes', '', $expense->notes); ?>
        </div>

        <input type="radio" id="c-tab-3" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-3' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12">
                    <section class="portlet">
                        <div class="portlet-head"><?= $this->getHtml('Expenses'); ?><i class="g-icon download btn end-xs">download</i></div>
                        <div class="slider">
                        <table id="iExpenseList" class="default sticky">
                            <thead>
                            <tr>
                                <td>
                                <td><?= $this->getHtml('ID', '0', '0'); ?>
                                    <label for="iExpenseList-sort-1">
                                        <input type="radio" name="iExpenseList-sort" id="iExpenseList-sort-1">
                                        <i class="sort-asc g-icon">expand_less</i>
                                    </label>
                                    <label for="iExpenseList-sort-2">
                                        <input type="radio" name="iExpenseList-sort" id="iExpenseList-sort-2">
                                        <i class="sort-desc g-icon">expand_more</i>
                                    </label>
                                    <label>
                                        <i class="filter g-icon">filter_alt</i>
                                    </label>
                                <td><?= $this->getHtml('Start'); ?>
                                <td><?= $this->getHtml('End'); ?>
                                <td class="wf-100"><?= $this->getHtml('Type'); ?>
                            <tbody>
                                <?php foreach ($expense->elements as $element) : ?>
                                    <tr>
                                        <td>
                                            <td><?= $element->id; ?>
                                        <td><?= $element->start->format('Y-m-d H:i'); ?>
                                        <td><?= $element->end?->format('Y-m-d H:i'); ?>
                                        <td><?= $this->printHtml($element->type->l11n); ?>
                                <?php endforeach; ?>
                        </table>
                    </section>
                </div>
            </div>
        </div>

        <input type="radio" id="c-tab-4" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-4' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12">
                    <section class="portlet">
                        <div class="portlet-head"><?= $this->getHtml('Recordings', 'HumanResourceTimeRecording', 'Backend'); ?><i class="g-icon download btn end-xs">download</i></div>
                        <table id="recordingList" class="default sticky">
                            <thead>
                            <tr>
                                <td><?= $this->getHtml('Date', 'HumanResourceTimeRecording', 'Backend'); ?>
                                    <label for="recordingList-sort-1">
                                        <input type="radio" name="recordingList-sort" id="recordingList-sort-1">
                                        <i class="sort-asc g-icon">expand_less</i>
                                    </label>
                                    <label for="recordingList-sort-2">
                                        <input type="radio" name="recordingList-sort" id="recordingList-sort-2">
                                        <i class="sort-desc g-icon">expand_more</i>
                                    </label>
                                    <label>
                                        <i class="filter g-icon">filter_alt</i>
                                    </label>
                                <td><?= $this->getHtml('Type', 'HumanResourceTimeRecording', 'Backend'); ?>
                                    <label for="recordingList-sort-3">
                                        <input type="radio" name="recordingList-sort" id="recordingList-sort-3">
                                        <i class="sort-asc g-icon">expand_less</i>
                                    </label>
                                    <label for="recordingList-sort-4">
                                        <input type="radio" name="recordingList-sort" id="recordingList-sort-4">
                                        <i class="sort-desc g-icon">expand_more</i>
                                    </label>
                                    <label>
                                        <i class="filter g-icon">filter_alt</i>
                                    </label>
                                <td><?= $this->getHtml('Status', 'HumanResourceTimeRecording', 'Backend'); ?>
                                    <label for="recordingList-sort-5">
                                        <input type="radio" name="recordingList-sort" id="recordingList-sort-5">
                                        <i class="sort-asc g-icon">expand_less</i>
                                    </label>
                                    <label for="recordingList-sort-6">
                                        <input type="radio" name="recordingList-sort" id="recordingList-sort-6">
                                        <i class="sort-desc g-icon">expand_more</i>
                                    </label>
                                    <label>
                                        <i class="filter g-icon">filter_alt</i>
                                    </label>
                                <td><?= $this->getHtml('Start', 'HumanResourceTimeRecording', 'Backend'); ?>
                                    <label for="recordingList-sort-7">
                                        <input type="radio" name="recordingList-sort" id="recordingList-sort-7">
                                        <i class="sort-asc g-icon">expand_less</i>
                                    </label>
                                    <label for="recordingList-sort-8">
                                        <input type="radio" name="recordingList-sort" id="recordingList-sort-8">
                                        <i class="sort-desc g-icon">expand_more</i>
                                    </label>
                                    <label>
                                        <i class="filter g-icon">filter_alt</i>
                                    </label>
                                <td><?= $this->getHtml('Break', 'HumanResourceTimeRecording', 'Backend'); ?>
                                    <label for="recordingList-sort-9">
                                        <input type="radio" name="recordingList-sort" id="recordingList-sort-9">
                                        <i class="sort-asc g-icon">expand_less</i>
                                    </label>
                                    <label for="recordingList-sort-10">
                                        <input type="radio" name="recordingList-sort" id="recordingList-sort-10">
                                        <i class="sort-desc g-icon">expand_more</i>
                                    </label>
                                    <label>
                                        <i class="filter g-icon">filter_alt</i>
                                    </label>
                                <td><?= $this->getHtml('End', 'HumanResourceTimeRecording', 'Backend'); ?>
                                    <label for="recordingList-sort-11">
                                        <input type="radio" name="recordingList-sort" id="recordingList-sort-11">
                                        <i class="sort-asc g-icon">expand_less</i>
                                    </label>
                                    <label for="recordingList-sort-12">
                                        <input type="radio" name="recordingList-sort" id="recordingList-sort-12">
                                        <i class="sort-desc g-icon">expand_more</i>
                                    </label>
                                    <label>
                                        <i class="filter g-icon">filter_alt</i>
                                    </label>
                                <td><?= $this->getHtml('Total', 'HumanResourceTimeRecording', 'Backend'); ?>
                                    <label for="recordingList-sort-13">
                                        <input type="radio" name="recordingList-sort" id="recordingList-sort-13">
                                        <i class="sort-asc g-icon">expand_less</i>
                                    </label>
                                    <label for="recordingList-sort-14">
                                        <input type="radio" name="recordingList-sort" id="recordingList-sort-14">
                                        <i class="sort-desc g-icon">expand_more</i>
                                    </label>
                                    <label>
                                        <i class="filter g-icon">filter_alt</i>
                                    </label>
                            <tbody>
                            <?php
                                $count = 0;
                                foreach ($sessions as $session) : ++$count;
                            ?>
                            <tr>
                                <td>
                                    <?php
                                        if ($lastOpenSession !== null
                                            && $session->start->format('Y-m-d') === $lastOpenSession->start->format('Y-m-d')
                                        ) : ?>
                                        <span class="tag">Today</span>
                                    <?php else : ?>
                                        <?= $session->start->format('Y-m-d'); ?> - <?= $this->getHtml('D' . $session->start->format('w')); ?>
                                    <?php endif; ?>
                                <td><span class="tag"><?= $this->getHtml('CT' . $session->type); ?></span>
                                <td><span class="tag"><?= $this->getHtml('CS' . $session->status); ?></span>
                                <td><?= $session->start->format('H:i'); ?>
                                <td><?= (int) ($session->getBreak() / 3600); ?>h <?= ((int) ($session->getBreak() / 60) % 60); ?>m
                                <td><?= $session->getEnd() !== null ? $session->getEnd()->format('H:i') : ''; ?>
                                <td><?= (int) ($session->getBusy() / 3600); ?>h <?= ((int) ($session->getBusy() / 60) % 60); ?>m
                            <?php
                                $busy['week'] += $session->getBusy();
                                if ($session->start->getTimestamp() < $startWeek->getTimestamp()
                                    || $count === $sessionCount
                            ) : ?>
                            <tr>
                                <th colspan="6"> <?= $startWeek->format('Y/m/d'); ?> - <?= $endWeek->format('Y/m/d'); ?>
                                <th><?= (int) ($busy['week'] / 3600); ?>h <?= ((int) ($busy['week'] / 60) % 60); ?>m
                            <?php
                                    $endWeek      = $startWeek;
                                    $startWeek    = $startWeek->createModify(0, 0, -7);
                                    $busy['week'] = 0;
                                endif;
                            ?>
                            <?php
                                $busy['month'] += $session->getBusy();
                                if ($session->start->getTimestamp() < $startMonth->getTimestamp()
                                    || $count === $sessionCount
                            ) : ?>
                            <tr>
                                <th colspan="6"> <?= $startMonth->format('Y/m/d'); ?> - <?= $endMonth->format('Y/m/d'); ?>
                                <th><?= (int) ($busy['month'] / 3600); ?>h <?= ((int) ($busy['month'] / 60) % 60); ?>m
                            <?php
                                    $endMonth      = $startMonth;
                                    $startMonth    = $startMonth->createModify(0, -1, 0);
                                    $busy['month'] = 0;
                                endif;
                            ?>
                            <?php endforeach; ?>
                            <?php if ($count === 0) : ?>
                            <tr>
                                <td colspan="7" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                            <?php endif; ?>
                        </table>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>