<?php

use humhub\commands\CronController;
use u4impact\humhub\modules\proposal\Events;
use humhub\modules\admin\widgets\AdminMenu;
use humhub\widgets\TopMenu;

return [
	'id' => 'proposal',
	'class' => 'u4impact\humhub\modules\proposal\Module',
	'namespace' => 'u4impact\humhub\modules\proposal',
	'events' => [
		[
			'class' => TopMenu::class,
			'event' => TopMenu::EVENT_INIT,
			'callback' => [Events::class, 'onTopMenuInit'],
		],
        [
            'class' => AdminMenu::class,
            'event' => AdminMenu::EVENT_INIT,
            'callback' => [Events::class, 'onAdminMenuInit']
        ],
		[
			'class' => AdminMenu::class,
			'event' => AdminMenu::EVENT_INIT,
			'callback' => [Events::class, 'onAdminMenuInit2']
		],
        [
            'class' => CronController::class,
            'event' => CronController::EVENT_ON_HOURLY_RUN,
            'callback' => [Events::class, 'onHourlyCron']
        ],
	],
];

