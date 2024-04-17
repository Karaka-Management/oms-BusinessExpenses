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
use phpOMS\Uri\UriFactory;

/** @var \phpOMS\Views\View $this */
$element  = $this->data['element'] ?? new NullExpense();
$isNew = $element->id === 0;

echo $this->data['nav']->render(); ?>
<div class="tabview tab-2">
    <?php if (!$isNew) : ?>
    <div class="box">
        <ul class="tab-links">
            <li><label for="c-tab-1"><?= $this->getHtml('Element'); ?></label>
            <li><label for="c-tab-2"><?= $this->getHtml('Files'); ?></label>
            <!--
            <li><label for="c-tab-3"><?= $this->getHtml('Entries'); ?></label>
            -->
        </ul>
    </div>
    <?php endif; ?>
    <div class="tab-content">
        <input type="radio" id="c-tab-1" name="tabular-2"<?= $isNew || $this->request->uri->fragment === 'c-tab-1' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-lg-6">
                    <?php if (!$isNew) : ?>
                        <div class="box">
                            <a tabindex="0" class="button" href="<?= UriFactory::build('{/base}/businessexpenses/expense/view?id=' . $element->expense); ?>"><?= $this->getHtml('Back'); ?></a>
                        </div>
                    <?php endif; ?>
                    <section class="portlet">
                    <form method="<?= $isNew ? 'PUT' : 'POST'; ?>" action="<?= UriFactory::build('{/api}businessexpenses/expense/element?csrf={$CSRF}'); ?>">
                        <div class="portlet-head"><?= $this->getHtml('Element'); ?></div>
                        <div class="portlet-body">
                            <div class="form-group">
                                <label for="iId"><?= $this->getHtml('ID', '0', '0'); ?></label>
                                <input type="text" id="iId" name="id" value="<?= $element->id; ?>" disabled>
                            </div>

                            <div class="form-group">
                                <label for="iType"><?= $this->getHtml('Type'); ?></label>
                                <select id="iType" name="type">
                                    <option value=""<?= $isNew ? ' selected' : ''; ?>>
                                    <?php
                                    foreach ($this->data['types'] as $type) : ?>
                                        <option value="<?= $type->id; ?>"<?= $element->type->id === $type->id ? ' selected' : ''; ?>><?= $this->printHtml($type->getL11n()); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iDescription"><?= $this->getHtml('Description'); ?></label>
                                <pre id="iDescription" class="textarea contenteditable" name="description" contenteditable="true"><?= $this->printHtml($element->description); ?></pre>
                            </div>

                            <div class="form-group">
                                <label for="iStart"><?= $this->getHtml('Start'); ?></label>
                                <input type="datetime-local" id="iStart" name="start" value="<?= $this->printHtml($element->start->format('Y-m-d\TH:i:s')); ?>">
                            </div>

                            <div class="form-group">
                                <label for="iEnd"><?= $this->getHtml('End'); ?></label>
                                <input type="datetime-local" id="iEnd" name="end" value="<?= $this->printHtml($element->end->format('Y-m-d\TH:i:s')); ?>">
                            </div>

                            <div class="form-group">
                                <label for="iCosts"><?= $this->getHtml('Costs'); ?></label>
                                <input type="number" id="iCosts" name="gross" step="any" value="<?= $element->gross->getNormalizedValue(); ?>">
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
        <input type="radio" id="c-tab-2" name="tabular-2" checked>
        <div class="tab col-simple">
            <div class="col-xs-12">
                <?= $this->data['media-upload']->render('item-file', 'files', '', $element->files); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
