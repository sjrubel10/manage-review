<?php return array(
    'root' => array(
        'name' => 'review/master',
        'pretty_version' => 'dev-master',
        'version' => 'dev-master',
        'reference' => 'be867d93f00862d86bcb1640459c3198ad63c66b',
        'type' => 'wordpress-plugin',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'phpmailer/phpmailer' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => '06e6e8071324c856d4d63e7528443ff83f71e44a',
            'type' => 'library',
            'install_path' => __DIR__ . '/../phpmailer/phpmailer',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'dev_requirement' => false,
        ),
        'review/master' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => 'be867d93f00862d86bcb1640459c3198ad63c66b',
            'type' => 'wordpress-plugin',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
    ),
);
