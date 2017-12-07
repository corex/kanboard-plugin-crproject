<div class="dropdown">
    <a href="#" class="dropdown-menu dropdown-menu-link-icon">
        <nobr><i class="fa fa-cog"></i><i class="fa fa-caret-down"></i></nobr>
    </a>
    <ul>
        <li>
            <?php
            $isHidden = $projectStatus !== null ? intval($projectStatus['is_hidden']) == 1 : false;
            $title = $isHidden ? t('Unhide') : t('Hide');
            $parameters = array('plugin' => 'CRProject', 'id' => $id, 'isHidden' => intval(!$isHidden));
            ?>
            <?= $this->url->icon('eye', t($title), 'DashboardController', 'visibility', $parameters) ?>
        </li>
        <li>
            <?php
            $parameters = array('plugin' => 'CRProject', 'id' => $id, 'statusId' => 0);
            ?>
            <?= $this->url->icon('folder', t('None'), 'DashboardController', 'status', $parameters) ?>
        </li>
        <?php foreach ($statuses as $status): ?>
            <?php
            $title = $status['title'];
            $isVisible = $status['is_visible'] == 1;
            $title .= ' (' . ($isVisible ? t('Visible') : t('Hidden')) . ')';
            ?>
            <li>
                <?php
                $parameters = array('plugin' => 'CRProject', 'id' => $id, 'statusId' => $status['id']);
                ?>
                <?= $this->url->icon('folder', t($title), 'DashboardController', 'status', $parameters) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
