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
use Modules\BusinessExpenses\Models\NullExpenseElement;
use phpOMS\Stdlib\Base\FloatInt;
use phpOMS\Stdlib\Base\SmartDateTime;
use phpOMS\Uri\UriFactory;

/** @var \phpOMS\Views\View $this */
$expense  = $this->data['expense'] ?? new NullExpense();
$sessions = $this->data['sessions'] ?? [];

$isNew = $expense->id === 0;

echo $this->data['nav']->render(); ?>
<div id="iExpenseView" class="tabview tab-2 url-rewrite">
    <?php if (!$isNew) : ?>
    <div class="box">
        <ul class="tab-links">
            <li><label for="c-tab-1"><?= $this->getHtml('Overview'); ?></label>
            <li><label for="c-tab-2"><?= $this->getHtml('Notes'); ?></label>
            <li><label for="c-tab-3"><?= $this->getHtml('Expenses'); ?></label>
            <li><label for="c-tab-4"><?= $this->getHtml('Clocking'); ?></label>
        </ul>
    </div>
    <?php endif; ?>
    <div class="tab-content">
        <input type="radio" id="c-tab-1" name="tabular-2"<?= $isNew || $this->request->uri->fragment === 'c-tab-1' ? ' checked' : ''; ?>>
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

                <!--
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
                -->
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <section class="portlet">
                    <form method="<?= $isNew ? 'PUT' : 'POST'; ?>" action="<?= UriFactory::build('{/api}businessexpenses/expense?csrf={$CSRF}'); ?>">
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
                                <textarea id="iDescription" name="description"><?= $this->printTextarea($expense->description); ?></textarea>
                            </div>
                        </div>
                        <div class="portlet-foot">
                            <?php if ($isNew) : ?>
                                <input id="iCreateSubmit" type="Submit" value="<?= $this->getHtml('Create', '0', '0'); ?>">
                            <?php else : ?>
                                <input id="iSaveSubmit" type="Submit" value="<?= $this->getHtml('Save', '0', '0'); ?>">
                            <?php endif; ?>
                        </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>

        <?php if (!$isNew) : ?>
        <input type="radio" id="c-tab-2" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-2' ? ' checked' : ''; ?>>
        <div class="tab">
            <?= $this->data['expense-notes']->render('expense-notes', '', $expense->notes); ?>
        </div>

        <input type="radio" id="c-tab-3" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-3' ? ' checked' : ''; ?>>
        <div class="tab">
            <?php
                $costs = [
                    'total' => new FloatInt(),
                    'week'  => new FloatInt(),
                    'day'   => new FloatInt(),
                ];

                $elements = $expense->elements;

                $current = SmartDateTime::createFromDateTime($expense->start);
                $current->smartModify(0, 0, -1);

                $end     = clone $expense->end;
                $element = empty($elements)
                    ? new NullExpenseElement()
                    : \reset($elements);
            ?>
            <div class="row">
                <div class="col-xs-12">
                    <section class="portlet">
                        <div class="portlet-head">
                            <?= $this->getHtml('Expenses'); ?>
                            <i class="g-icon download btn end-xs">download</i>
                            <div class="end-xs">
                                <a class="button save" href="<?= UriFactory::build('{/base}/businessexpenses/expense/element/create?id=' . $expense->id); ?>"><?= $this->getHtml('New', '0', '0'); ?></a>
                                <a class="button" href="<?= UriFactory::build('{/base}/businessexpenses/expense/element/upload?id=' . $expense->id); ?>"><?= $this->getHtml('Upload'); ?></a>
                            </div>
                        </div>
                        <div class="slider">
                        <table id="iExpenseList" class="default sticky">
                            <thead>
                            <tr>
                                <td><?= $this->getHtml('Start'); ?>
                                <td><?= $this->getHtml('End'); ?>
                                <td class="wf-100"><?= $this->getHtml('Type'); ?>
                                <td><?= $this->getHtml('Costs'); ?>
                            <tbody>
                                <?php
                                while ($current->format('Y-m-d') !== $end->format('Y-m-d')) :
                                    $current->smartModify(0, 0, 1);

                                if ($element->id !== 0 && $element->start->format('Y-m-d') === $current->format('Y-m-d')) :
                                    $url = UriFactory::build('{/base}/businessexpenses/expense/element/view?{?}&id=' . $element->id);
                                ?>
                                <tr data-href="<?= $url; ?>">
                                    <td><a href="<?= $url; ?>"><?= $element->start->format('Y-m-d H:i'); ?></a>
                                    <td><a href="<?= $url; ?>"><?= $element->end?->format('Y-m-d H:i'); ?></a>
                                    <td><a href="<?= $url; ?>"><?= $this->printHtml($element->type->l11n); ?></a>
                                    <td><a href="<?= $url; ?>"><?= $this->getCurrency($element->gross, symbol: ''); ?></a>
                                <?php
                                    $costs['total']->add($element->gross->value);
                                    $costs['week']->add($element->gross->value);
                                    $costs['day']->add($element->gross->value);

                                    $element = \next($elements);
                                    if ($element === false) {
                                        $element = new NullExpenseElement();
                                    }

                                    // Required to handle multiple elements in one day
                                    if ($element->id !== 0 && $element->start->format('Y-m-d') === $current->format('Y-m-d')) {
                                        $current->smartModify(0, 0, -1);
                                    }
                                ?>
                                <?php else : ?>
                                <tr>
                                    <td class="disabled"><?= $current->format('Y-m-d'); ?>
                                    <td colspan="3" class="empty">
                            <?php endif; ?>
                            <?php endwhile; ?>
                            <?php while (($element = \next($elements)) !== false) :
                                $url = UriFactory::build('{/base}/businessexpenses/expense/element/view?{?}&id=' . $element->id);
                            ?>
                                <tr data-href="<?= $url; ?>" class="hl-1">
                                    <td><a href="<?= $url; ?>"><?= $element->start->format('Y-m-d H:i'); ?></a>
                                    <td><a href="<?= $url; ?>"><?= $element->end?->format('Y-m-d H:i'); ?></a>
                                    <td><a href="<?= $url; ?>"><?= $this->printHtml($element->type->l11n); ?></a>
                                    <td><a href="<?= $url; ?>"><?= $this->getCurrency($element->gross, symbol: ''); ?></a>
                            <?php endwhile; ?>
                            <tr class="hl-3">
                                <td colspan="3"><?= $this->getHtml('Total'); ?>
                                <td><?= $costs['total']->getAmount(); ?>
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
                                        <?= $session->start->format('Y-m-d'); ?> - <?= $this->getHtml(':D' . $session->start->format('w')); ?>
                                    <?php endif; ?>
                                <td><span class="tag"><?= $this->getHtml(':CT' . $session->type); ?></span>
                                <td><span class="tag"><?= $this->getHtml(':CS' . $session->status); ?></span>
                                <td><?= $session->start->format('H:i'); ?>
                                <td><?= (int) ($session->getBreak() / 3600); ?>h <?= ((int) ($session->getBreak() / 60) % 60); ?>m
                                <td><?= $session->getEnd() !== null ? $session->getEnd()->format('H:i') : ''; ?>
                                <td><?= (int) ($session->busy / 3600); ?>h <?= ((int) ($session->busy / 60) % 60); ?>m
                            <?php
                                $busy['week'] += $session->busy;
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
                                $busy['month'] += $session->busy;
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
        <?php endif; ?>
    </div>
</div>