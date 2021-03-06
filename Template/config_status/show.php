<?php

use Kanboard\Plugin\CRProject\Helper\Factory;

// We are cheating here and use "subtask" functionality (subtasks-table) to drag/drop.
$positionUrl = $this->url->href('ConfigStatusController', 'position', array('plugin' => 'CRProject'));
$widgetHelper = Factory::widgetHelper();
?>
    <div class="page-header">
        <h2><?= t('Project status') ?></h2>
    </div>
    <table id="crtable" class="table-list subtasks-table table-striped table-scrolling"
           data-save-position-url="<?= $positionUrl ?>">
        <thead>
        <tr>
            <th class="column-20"><?= t('Title') ?></th>
            <th class="column-70"><?= t('Description') ?></th>
            <th class="column-10" style="text-align: center;"><?= t('Visible') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($statuses as $status): ?>
            <tr class="table-list-row <?= 'color-' . $status['color_id'] ?>" data-subtask-id="<?= $status['id'] ?>">
                <td style="vertical-align: middle;">
                    <i class="fa fa-arrows-alt draggable-row-handle" title="<?= t('Change position') ?>"></i>&nbsp;
                    <?= $this->render('CRProject:config_status/menu', array('status' => $status)) ?>
                    <?= $widgetHelper->label($status['color_id'], $status['title']) ?>
                </td>
                <td class="column-60" style="text-align: left; vertical-align: middle;">
                    <?= $status['description'] ?>
                </td>
                <td class="column-10" style="text-align: center; vertical-align: middle;">
                    <?= $status['is_visible'] == 1 ? t('Yes') : t('No') ?>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
<?= $this->modal->medium('plus', t('Add new status'), 'ConfigStatusController', 'edit', array(
    'plugin' => 'CRProject',
    'id' => 0
)) ?>