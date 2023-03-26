<?php

namespace common\components;

class FConstant extends \common\config\FSettings
{
    const DB_MYSQL = 'mysql';
    const DB_ARRAY_FILE = 'array_file';
    const DB_ELASTIC = 'elastic';

    const NUMERIC_MAX = PHP_INT_MAX;

    const USER_STATUS_DELETED = 0;
    const USER_STATUS_ACTIVE = 10;
    const ROLE_USER = 10;
    const ROLE_MODERATOR = 20;
    const ROLE_ADMIN = 30;
    const ROLE_ALL = '@';
    const ROLE_NONE = '?';
    const ROLE_CODE_GROUPS = ['admin' => FHtml::ROLE_ADMIN, 'user' => FHtml::ROLE_USER, 'moderator' => FHtml::ROLE_MODERATOR, 'everybody' => FHtml::ROLE_ALL, 'nobody' => FHtml::ROLE_NONE ];

    const APPLICATION_NONE = '';
    const APPLICATION_MANY = true;
    const FORMAT_JSON = 'json';

    const LANGUAGES_NONE = '';
    const LANGUAGES_LABELS_ONLY = true;
    const LANGUAGES_PARAM = 'language';

    const LANGUAGES_LABELS_AND_DB = 'all';

    const EXCLUDED_TABLES_AS_APPLICATIONS = ['user'];
    const EXCLUDED_TABLES_AS_MULTILANGS = ['user*', 'object*', 'app_*', 'auth*', 'setting*', 'object_relation'];
    const INCLUDED_TABLES_AS_MULTILANGS = ['object_setting', 'object_category'];
    const EXCLUDED_TABLES_AS_OBJECT_CHANGES = ['object*', 'app_*', 'auth*', 'setting*'];
    const EXCLUDED_ACTIONS_AS_PAGEVIEW_SETTINGS = ['create', 'update', 'view', 'index', 'Form', 'Detail'];
    const EXCLUDED_UNIQUE_CODES = ['_*', FHtml::NULL_VALUE, '?*'];

    const ICON_RIGHT_ARROW = '<i class="glyphicon glyphicon-chevron-right"></i>';
    const ICON_OK = '<i class="glyphicon glyphicon-ok"></i>';
    const ICON_REMOVE = '<i class="glyphicon glyphicon-remove"></i>';
    const ICON_TRASH = '<i class="glyphicon glyphicon-trash"></i>';

    const ICON_SHUT_OFF = '<i class="fa fa-power-off"></i>';
    const ICON_CREATE = '<i class="glyphicon glyphicon-plus"></i>';
    const ICON_FLASH = '<i class="glyphicon glyphicon-flash"></i>';
    const ICON_CALENDAR = '<i class="glyphicon glyphicon-calendar"></i>';
    const ICON_SUCCESS = '<i class="glyphicon glyphicon-success"></i>';
    const ICON_MINUS_SIGN = '<i class="glyphicon glyphicon-minus-sign"></i>';
    const ICON_CHART = '<i class="fa fa-pie-chart"></i>';
    const ICON_CHART_LINE = '<i class="fa fa-line-chart" aria-hidden="true"></i>';

    const ICON_ACTIVE = '<span class="glyphicon glyphicon-ok text-success"></span>';
    const ICON_INACTIVE = '<span class="glyphicon glyphicon-remove text-danger"></span>';
    const ICON_EXPAND = '<span class="glyphicon glyphicon-expand"></span>';
    const ICON_COLLAPSE = '<span class="glyphicon glyphicon-collapse-down"></span>';
    const ICON_UNCHECKED = '<span class="glyphicon glyphicon-unchecked"></span>';

    const ICON_ARRAYS = 	array(
        'fa-glass'                               => 'f000',
        'fa-music'                               => 'f001',
        'fa-search'                              => 'f002',
        'fa-envelope-o'                          => 'f003',
        'fa-heart'                               => 'f004',
        'fa-star'                                => 'f005',
        'fa-star-o'                              => 'f006',
        'fa-user'                                => 'f007',
        'fa-film'                                => 'f008',
        'fa-th-large'                            => 'f009',
        'fa-th'                                  => 'f00a',
        'fa-th-list'                             => 'f00b',
        'fa-check'                               => 'f00c',
        'fa-times'                               => 'f00d',
        'fa-search-plus'                         => 'f00e',
        'fa-search-minus'                        => 'f010',
        'fa-power-off'                           => 'f011',
        'fa-signal'                              => 'f012',
        'fa-cog'                                 => 'f013',
        'fa-trash-o'                             => 'f014',
        'fa-home'                                => 'f015',
        'fa-file-o'                              => 'f016',
        'fa-clock-o'                             => 'f017',
        'fa-road'                                => 'f018',
        'fa-download'                            => 'f019',
        'fa-arrow-circle-o-down'                 => 'f01a',
        'fa-arrow-circle-o-up'                   => 'f01b',
        'fa-inbox'                               => 'f01c',
        'fa-play-circle-o'                       => 'f01d',
        'fa-repeat'                              => 'f01e',
        'fa-refresh'                             => 'f021',
        'fa-list-alt'                            => 'f022',
        'fa-lock'                                => 'f023',
        'fa-flag'                                => 'f024',
        'fa-headphones'                          => 'f025',
        'fa-volume-off'                          => 'f026',
        'fa-volume-down'                         => 'f027',
        'fa-volume-up'                           => 'f028',
        'fa-qrcode'                              => 'f029',
        'fa-barcode'                             => 'f02a',
        'fa-tag'                                 => 'f02b',
        'fa-tags'                                => 'f02c',
        'fa-book'                                => 'f02d',
        'fa-bookmark'                            => 'f02e',
        'fa-print'                               => 'f02f',
        'fa-camera'                              => 'f030',
        'fa-font'                                => 'f031',
        'fa-bold'                                => 'f032',
        'fa-italic'                              => 'f033',
        'fa-text-height'                         => 'f034',
        'fa-text-width'                          => 'f035',
        'fa-align-left'                          => 'f036',
        'fa-align-center'                        => 'f037',
        'fa-align-right'                         => 'f038',
        'fa-align-justify'                       => 'f039',
        'fa-list'                                => 'f03a',
        'fa-outdent'                             => 'f03b',
        'fa-indent'                              => 'f03c',
        'fa-video-camera'                        => 'f03d',
        'fa-picture-o'                           => 'f03e',
        'fa-pencil'                              => 'f040',
        'fa-map-marker'                          => 'f041',
        'fa-adjust'                              => 'f042',
        'fa-tint'                                => 'f043',
        'fa-pencil-square-o'                     => 'f044',
        'fa-share-square-o'                      => 'f045',
        'fa-check-square-o'                      => 'f046',
        'fa-arrows'                              => 'f047',
        'fa-step-backward'                       => 'f048',
        'fa-fast-backward'                       => 'f049',
        'fa-backward'                            => 'f04a',
        'fa-play'                                => 'f04b',
        'fa-pause'                               => 'f04c',
        'fa-stop'                                => 'f04d',
        'fa-forward'                             => 'f04e',
        'fa-fast-forward'                        => 'f050',
        'fa-step-forward'                        => 'f051',
        'fa-eject'                               => 'f052',
        'fa-chevron-left'                        => 'f053',
        'fa-chevron-right'                       => 'f054',
        'fa-plus-circle'                         => 'f055',
        'fa-minus-circle'                        => 'f056',
        'fa-times-circle'                        => 'f057',
        'fa-check-circle'                        => 'f058',
        'fa-question-circle'                     => 'f059',
        'fa-info-circle'                         => 'f05a',
        'fa-crosshairs'                          => 'f05b',
        'fa-times-circle-o'                      => 'f05c',
        'fa-check-circle-o'                      => 'f05d',
        'fa-ban'                                 => 'f05e',
        'fa-arrow-left'                          => 'f060',
        'fa-arrow-right'                         => 'f061',
        'fa-arrow-up'                            => 'f062',
        'fa-arrow-down'                          => 'f063',
        'fa-share'                               => 'f064',
        'fa-expand'                              => 'f065',
        'fa-compress'                            => 'f066',
        'fa-plus'                                => 'f067',
        'fa-minus'                               => 'f068',
        'fa-asterisk'                            => 'f069',
        'fa-exclamation-circle'                  => 'f06a',
        'fa-gift'                                => 'f06b',
        'fa-leaf'                                => 'f06c',
        'fa-fire'                                => 'f06d',
        'fa-eye'                                 => 'f06e',
        'fa-eye-slash'                           => 'f070',
        'fa-exclamation-triangle'                => 'f071',
        'fa-plane'                               => 'f072',
        'fa-calendar'                            => 'f073',
        'fa-random'                              => 'f074',
        'fa-comment'                             => 'f075',
        'fa-magnet'                              => 'f076',
        'fa-chevron-up'                          => 'f077',
        'fa-chevron-down'                        => 'f078',
        'fa-retweet'                             => 'f079',
        'fa-shopping-cart'                       => 'f07a',
        'fa-folder'                              => 'f07b',
        'fa-folder-open'                         => 'f07c',
        'fa-arrows-v'                            => 'f07d',
        'fa-arrows-h'                            => 'f07e',
        'fa-bar-chart'                           => 'f080',
        'fa-twitter-square'                      => 'f081',
        'fa-facebook-square'                     => 'f082',
        'fa-camera-retro'                        => 'f083',
        'fa-key'                                 => 'f084',
        'fa-cogs'                                => 'f085',
        'fa-comments'                            => 'f086',
        'fa-thumbs-o-up'                         => 'f087',
        'fa-thumbs-o-down'                       => 'f088',
        'fa-star-half'                           => 'f089',
        'fa-heart-o'                             => 'f08a',
        'fa-sign-out'                            => 'f08b',
        'fa-linkedin-square'                     => 'f08c',
        'fa-thumb-tack'                          => 'f08d',
        'fa-external-link'                       => 'f08e',
        'fa-sign-in'                             => 'f090',
        'fa-trophy'                              => 'f091',
        'fa-github-square'                       => 'f092',
        'fa-upload'                              => 'f093',
        'fa-lemon-o'                             => 'f094',
        'fa-phone'                               => 'f095',
        'fa-square-o'                            => 'f096',
        'fa-bookmark-o'                          => 'f097',
        'fa-phone-square'                        => 'f098',
        'fa-twitter'                             => 'f099',
        'fa-facebook'                            => 'f09a',
        'fa-github'                              => 'f09b',
        'fa-unlock'                              => 'f09c',
        'fa-credit-card'                         => 'f09d',
        'fa-rss'                                 => 'f09e',
        'fa-hdd-o'                               => 'f0a0',
        'fa-bullhorn'                            => 'f0a1',
        'fa-bell'                                => 'f0f3',
        'fa-certificate'                         => 'f0a3',
        'fa-hand-o-right'                        => 'f0a4',
        'fa-hand-o-left'                         => 'f0a5',
        'fa-hand-o-up'                           => 'f0a6',
        'fa-hand-o-down'                         => 'f0a7',
        'fa-arrow-circle-left'                   => 'f0a8',
        'fa-arrow-circle-right'                  => 'f0a9',
        'fa-arrow-circle-up'                     => 'f0aa',
        'fa-arrow-circle-down'                   => 'f0ab',
        'fa-globe'                               => 'f0ac',
        'fa-wrench'                              => 'f0ad',
        'fa-tasks'                               => 'f0ae',
        'fa-filter'                              => 'f0b0',
        'fa-briefcase'                           => 'f0b1',
        'fa-arrows-alt'                          => 'f0b2',
        'fa-users'                               => 'f0c0',
        'fa-link'                                => 'f0c1',
        'fa-cloud'                               => 'f0c2',
        'fa-flask'                               => 'f0c3',
        'fa-scissors'                            => 'f0c4',
        'fa-files-o'                             => 'f0c5',
        'fa-paperclip'                           => 'f0c6',
        'fa-floppy-o'                            => 'f0c7',
        'fa-square'                              => 'f0c8',
        'fa-bars'                                => 'f0c9',
        'fa-list-ul'                             => 'f0ca',
        'fa-list-ol'                             => 'f0cb',
        'fa-strikethrough'                       => 'f0cc',
        'fa-underline'                           => 'f0cd',
        'fa-table'                               => 'f0ce',
        'fa-magic'                               => 'f0d0',
        'fa-truck'                               => 'f0d1',
        'fa-pinterest'                           => 'f0d2',
        'fa-pinterest-square'                    => 'f0d3',
        'fa-google-plus-square'                  => 'f0d4',
        'fa-google-plus'                         => 'f0d5',
        'fa-money'                               => 'f0d6',
        'fa-caret-down'                          => 'f0d7',
        'fa-caret-up'                            => 'f0d8',
        'fa-caret-left'                          => 'f0d9',
        'fa-caret-right'                         => 'f0da',
        'fa-columns'                             => 'f0db',
        'fa-sort'                                => 'f0dc',
        'fa-sort-desc'                           => 'f0dd',
        'fa-sort-asc'                            => 'f0de',
        'fa-envelope'                            => 'f0e0',
        'fa-linkedin'                            => 'f0e1',
        'fa-undo'                                => 'f0e2',
        'fa-gavel'                               => 'f0e3',
        'fa-tachometer'                          => 'f0e4',
        'fa-comment-o'                           => 'f0e5',
        'fa-comments-o'                          => 'f0e6',
        'fa-bolt'                                => 'f0e7',
        'fa-sitemap'                             => 'f0e8',
        'fa-umbrella'                            => 'f0e9',
        'fa-clipboard'                           => 'f0ea',
        'fa-lightbulb-o'                         => 'f0eb',
        'fa-exchange'                            => 'f0ec',
        'fa-cloud-download'                      => 'f0ed',
        'fa-cloud-upload'                        => 'f0ee',
        'fa-user-md'                             => 'f0f0',
        'fa-stethoscope'                         => 'f0f1',
        'fa-suitcase'                            => 'f0f2',
        'fa-bell-o'                              => 'f0a2',
        'fa-coffee'                              => 'f0f4',
        'fa-cutlery'                             => 'f0f5',
        'fa-file-text-o'                         => 'f0f6',
        'fa-building-o'                          => 'f0f7',
        'fa-hospital-o'                          => 'f0f8',
        'fa-ambulance'                           => 'f0f9',
        'fa-medkit'                              => 'f0fa',
        'fa-fighter-jet'                         => 'f0fb',
        'fa-beer'                                => 'f0fc',
        'fa-h-square'                            => 'f0fd',
        'fa-plus-square'                         => 'f0fe',
        'fa-angle-double-left'                   => 'f100',
        'fa-angle-double-right'                  => 'f101',
        'fa-angle-double-up'                     => 'f102',
        'fa-angle-double-down'                   => 'f103',
        'fa-angle-left'                          => 'f104',
        'fa-angle-right'                         => 'f105',
        'fa-angle-up'                            => 'f106',
        'fa-angle-down'                          => 'f107',
        'fa-desktop'                             => 'f108',
        'fa-laptop'                              => 'f109',
        'fa-tablet'                              => 'f10a',
        'fa-mobile'                              => 'f10b',
        'fa-circle-o'                            => 'f10c',
        'fa-quote-left'                          => 'f10d',
        'fa-quote-right'                         => 'f10e',
        'fa-spinner'                             => 'f110',
        'fa-circle'                              => 'f111',
        'fa-reply'                               => 'f112',
        'fa-github-alt'                          => 'f113',
        'fa-folder-o'                            => 'f114',
        'fa-folder-open-o'                       => 'f115',
        'fa-smile-o'                             => 'f118',
        'fa-frown-o'                             => 'f119',
        'fa-meh-o'                               => 'f11a',
        'fa-gamepad'                             => 'f11b',
        'fa-keyboard-o'                          => 'f11c',
        'fa-flag-o'                              => 'f11d',
        'fa-flag-checkered'                      => 'f11e',
        'fa-terminal'                            => 'f120',
        'fa-code'                                => 'f121',
        'fa-reply-all'                           => 'f122',
        'fa-star-half-o'                         => 'f123',
        'fa-location-arrow'                      => 'f124',
        'fa-crop'                                => 'f125',
        'fa-code-fork'                           => 'f126',
        'fa-chain-broken'                        => 'f127',
        'fa-question'                            => 'f128',
        'fa-info'                                => 'f129',
        'fa-exclamation'                         => 'f12a',
        'fa-superscript'                         => 'f12b',
        'fa-subscript'                           => 'f12c',
        'fa-eraser'                              => 'f12d',
        'fa-puzzle-piece'                        => 'f12e',
        'fa-microphone'                          => 'f130',
        'fa-microphone-slash'                    => 'f131',
        'fa-shield'                              => 'f132',
        'fa-calendar-o'                          => 'f133',
        'fa-fire-extinguisher'                   => 'f134',
        'fa-rocket'                              => 'f135',
        'fa-maxcdn'                              => 'f136',
        'fa-chevron-circle-left'                 => 'f137',
        'fa-chevron-circle-right'                => 'f138',
        'fa-chevron-circle-up'                   => 'f139',
        'fa-chevron-circle-down'                 => 'f13a',
        'fa-html5'                               => 'f13b',
        'fa-css3'                                => 'f13c',
        'fa-anchor'                              => 'f13d',
        'fa-unlock-alt'                          => 'f13e',
        'fa-bullseye'                            => 'f140',
        'fa-ellipsis-h'                          => 'f141',
        'fa-ellipsis-v'                          => 'f142',
        'fa-rss-square'                          => 'f143',
        'fa-play-circle'                         => 'f144',
        'fa-ticket'                              => 'f145',
        'fa-minus-square'                        => 'f146',
        'fa-minus-square-o'                      => 'f147',
        'fa-level-up'                            => 'f148',
        'fa-level-down'                          => 'f149',
        'fa-check-square'                        => 'f14a',
        'fa-pencil-square'                       => 'f14b',
        'fa-external-link-square'                => 'f14c',
        'fa-share-square'                        => 'f14d',
        'fa-compass'                             => 'f14e',
        'fa-caret-square-o-down'                 => 'f150',
        'fa-caret-square-o-up'                   => 'f151',
        'fa-caret-square-o-right'                => 'f152',
        'fa-eur'                                 => 'f153',
        'fa-gbp'                                 => 'f154',
        'fa-usd'                                 => 'f155',
        'fa-inr'                                 => 'f156',
        'fa-jpy'                                 => 'f157',
        'fa-rub'                                 => 'f158',
        'fa-krw'                                 => 'f159',
        'fa-btc'                                 => 'f15a',
        'fa-file'                                => 'f15b',
        'fa-file-text'                           => 'f15c',
        'fa-sort-alpha-asc'                      => 'f15d',
        'fa-sort-alpha-desc'                     => 'f15e',
        'fa-sort-amount-asc'                     => 'f160',
        'fa-sort-amount-desc'                    => 'f161',
        'fa-sort-numeric-asc'                    => 'f162',
        'fa-sort-numeric-desc'                   => 'f163',
        'fa-thumbs-up'                           => 'f164',
        'fa-thumbs-down'                         => 'f165',
        'fa-youtube-square'                      => 'f166',
        'fa-youtube'                             => 'f167',
        'fa-xing'                                => 'f168',
        'fa-xing-square'                         => 'f169',
        'fa-youtube-play'                        => 'f16a',
        'fa-dropbox'                             => 'f16b',
        'fa-stack-overflow'                      => 'f16c',
        'fa-instagram'                           => 'f16d',
        'fa-flickr'                              => 'f16e',
        'fa-adn'                                 => 'f170',
        'fa-bitbucket'                           => 'f171',
        'fa-bitbucket-square'                    => 'f172',
        'fa-tumblr'                              => 'f173',
        'fa-tumblr-square'                       => 'f174',
        'fa-long-arrow-down'                     => 'f175',
        'fa-long-arrow-up'                       => 'f176',
        'fa-long-arrow-left'                     => 'f177',
        'fa-long-arrow-right'                    => 'f178',
        'fa-apple'                               => 'f179',
        'fa-windows'                             => 'f17a',
        'fa-android'                             => 'f17b',
        'fa-linux'                               => 'f17c',
        'fa-dribbble'                            => 'f17d',
        'fa-skype'                               => 'f17e',
        'fa-foursquare'                          => 'f180',
        'fa-trello'                              => 'f181',
        'fa-female'                              => 'f182',
        'fa-male'                                => 'f183',
        'fa-gratipay'                            => 'f184',
        'fa-sun-o'                               => 'f185',
        'fa-moon-o'                              => 'f186',
        'fa-archive'                             => 'f187',
        'fa-bug'                                 => 'f188',
        'fa-vk'                                  => 'f189',
        'fa-weibo'                               => 'f18a',
        'fa-renren'                              => 'f18b',
        'fa-pagelines'                           => 'f18c',
        'fa-stack-exchange'                      => 'f18d',
        'fa-arrow-circle-o-right'                => 'f18e',
        'fa-arrow-circle-o-left'                 => 'f190',
        'fa-caret-square-o-left'                 => 'f191',
        'fa-dot-circle-o'                        => 'f192',
        'fa-wheelchair'                          => 'f193',
        'fa-vimeo-square'                        => 'f194',
        'fa-try'                                 => 'f195',
        'fa-plus-square-o'                       => 'f196',
        'fa-space-shuttle'                       => 'f197',
        'fa-slack'                               => 'f198',
        'fa-envelope-square'                     => 'f199',
        'fa-wordpress'                           => 'f19a',
        'fa-openid'                              => 'f19b',
        'fa-university'                          => 'f19c',
        'fa-graduation-cap'                      => 'f19d',
        'fa-yahoo'                               => 'f19e',
        'fa-google'                              => 'f1a0',
        'fa-reddit'                              => 'f1a1',
        'fa-reddit-square'                       => 'f1a2',
        'fa-stumbleupon-circle'                  => 'f1a3',
        'fa-stumbleupon'                         => 'f1a4',
        'fa-delicious'                           => 'f1a5',
        'fa-digg'                                => 'f1a6',
        'fa-pied-piper-pp'                       => 'f1a7',
        'fa-pied-piper-alt'                      => 'f1a8',
        'fa-drupal'                              => 'f1a9',
        'fa-joomla'                              => 'f1aa',
        'fa-language'                            => 'f1ab',
        'fa-fax'                                 => 'f1ac',
        'fa-building'                            => 'f1ad',
        'fa-child'                               => 'f1ae',
        'fa-paw'                                 => 'f1b0',
        'fa-spoon'                               => 'f1b1',
        'fa-cube'                                => 'f1b2',
        'fa-cubes'                               => 'f1b3',
        'fa-behance'                             => 'f1b4',
        'fa-behance-square'                      => 'f1b5',
        'fa-steam'                               => 'f1b6',
        'fa-steam-square'                        => 'f1b7',
        'fa-recycle'                             => 'f1b8',
        'fa-car'                                 => 'f1b9',
        'fa-taxi'                                => 'f1ba',
        'fa-tree'                                => 'f1bb',
        'fa-spotify'                             => 'f1bc',
        'fa-deviantart'                          => 'f1bd',
        'fa-soundcloud'                          => 'f1be',
        'fa-database'                            => 'f1c0',
        'fa-file-pdf-o'                          => 'f1c1',
        'fa-file-word-o'                         => 'f1c2',
        'fa-file-excel-o'                        => 'f1c3',
        'fa-file-powerpoint-o'                   => 'f1c4',
        'fa-file-image-o'                        => 'f1c5',
        'fa-file-archive-o'                      => 'f1c6',
        'fa-file-audio-o'                        => 'f1c7',
        'fa-file-video-o'                        => 'f1c8',
        'fa-file-code-o'                         => 'f1c9',
        'fa-vine'                                => 'f1ca',
        'fa-codepen'                             => 'f1cb',
        'fa-jsfiddle'                            => 'f1cc',
        'fa-life-ring'                           => 'f1cd',
        'fa-circle-o-notch'                      => 'f1ce',
        'fa-rebel'                               => 'f1d0',
        'fa-empire'                              => 'f1d1',
        'fa-git-square'                          => 'f1d2',
        'fa-git'                                 => 'f1d3',
        'fa-hacker-news'                         => 'f1d4',
        'fa-tencent-weibo'                       => 'f1d5',
        'fa-qq'                                  => 'f1d6',
        'fa-weixin'                              => 'f1d7',
        'fa-paper-plane'                         => 'f1d8',
        'fa-paper-plane-o'                       => 'f1d9',
        'fa-history'                             => 'f1da',
        'fa-circle-thin'                         => 'f1db',
        'fa-header'                              => 'f1dc',
        'fa-paragraph'                           => 'f1dd',
        'fa-sliders'                             => 'f1de',
        'fa-share-alt'                           => 'f1e0',
        'fa-share-alt-square'                    => 'f1e1',
        'fa-bomb'                                => 'f1e2',
        'fa-futbol-o'                            => 'f1e3',
        'fa-tty'                                 => 'f1e4',
        'fa-binoculars'                          => 'f1e5',
        'fa-plug'                                => 'f1e6',
        'fa-slideshare'                          => 'f1e7',
        'fa-twitch'                              => 'f1e8',
        'fa-yelp'                                => 'f1e9',
        'fa-newspaper-o'                         => 'f1ea',
        'fa-wifi'                                => 'f1eb',
        'fa-calculator'                          => 'f1ec',
        'fa-paypal'                              => 'f1ed',
        'fa-google-wallet'                       => 'f1ee',
        'fa-cc-visa'                             => 'f1f0',
        'fa-cc-mastercard'                       => 'f1f1',
        'fa-cc-discover'                         => 'f1f2',
        'fa-cc-amex'                             => 'f1f3',
        'fa-cc-paypal'                           => 'f1f4',
        'fa-cc-stripe'                           => 'f1f5',
        'fa-bell-slash'                          => 'f1f6',
        'fa-bell-slash-o'                        => 'f1f7',
        'fa-trash'                               => 'f1f8',
        'fa-copyright'                           => 'f1f9',
        'fa-at'                                  => 'f1fa',
        'fa-eyedropper'                          => 'f1fb',
        'fa-paint-brush'                         => 'f1fc',
        'fa-birthday-cake'                       => 'f1fd',
        'fa-area-chart'                          => 'f1fe',
        'fa-pie-chart'                           => 'f200',
        'fa-line-chart'                          => 'f201',
        'fa-lastfm'                              => 'f202',
        'fa-lastfm-square'                       => 'f203',
        'fa-toggle-off'                          => 'f204',
        'fa-toggle-on'                           => 'f205',
        'fa-bicycle'                             => 'f206',
        'fa-bus'                                 => 'f207',
        'fa-ioxhost'                             => 'f208',
        'fa-angellist'                           => 'f209',
        'fa-cc'                                  => 'f20a',
        'fa-ils'                                 => 'f20b',
        'fa-meanpath'                            => 'f20c',
        'fa-buysellads'                          => 'f20d',
        'fa-connectdevelop'                      => 'f20e',
        'fa-dashcube'                            => 'f210',
        'fa-forumbee'                            => 'f211',
        'fa-leanpub'                             => 'f212',
        'fa-sellsy'                              => 'f213',
        'fa-shirtsinbulk'                        => 'f214',
        'fa-simplybuilt'                         => 'f215',
        'fa-skyatlas'                            => 'f216',
        'fa-cart-plus'                           => 'f217',
        'fa-cart-arrow-down'                     => 'f218',
        'fa-diamond'                             => 'f219',
        'fa-ship'                                => 'f21a',
        'fa-user-secret'                         => 'f21b',
        'fa-motorcycle'                          => 'f21c',
        'fa-street-view'                         => 'f21d',
        'fa-heartbeat'                           => 'f21e',
        'fa-venus'                               => 'f221',
        'fa-mars'                                => 'f222',
        'fa-mercury'                             => 'f223',
        'fa-transgender'                         => 'f224',
        'fa-transgender-alt'                     => 'f225',
        'fa-venus-double'                        => 'f226',
        'fa-mars-double'                         => 'f227',
        'fa-venus-mars'                          => 'f228',
        'fa-mars-stroke'                         => 'f229',
        'fa-mars-stroke-v'                       => 'f22a',
        'fa-mars-stroke-h'                       => 'f22b',
        'fa-neuter'                              => 'f22c',
        'fa-genderless'                          => 'f22d',
        'fa-facebook-official'                   => 'f230',
        'fa-pinterest-p'                         => 'f231',
        'fa-whatsapp'                            => 'f232',
        'fa-server'                              => 'f233',
        'fa-user-plus'                           => 'f234',
        'fa-user-times'                          => 'f235',
        'fa-bed'                                 => 'f236',
        'fa-viacoin'                             => 'f237',
        'fa-train'                               => 'f238',
        'fa-subway'                              => 'f239',
        'fa-medium'                              => 'f23a',
        'fa-y-combinator'                        => 'f23b',
        'fa-optin-monster'                       => 'f23c',
        'fa-opencart'                            => 'f23d',
        'fa-expeditedssl'                        => 'f23e',
        'fa-battery-full'                        => 'f240',
        'fa-battery-three-quarters'              => 'f241',
        'fa-battery-half'                        => 'f242',
        'fa-battery-quarter'                     => 'f243',
        'fa-battery-empty'                       => 'f244',
        'fa-mouse-pointer'                       => 'f245',
        'fa-i-cursor'                            => 'f246',
        'fa-object-group'                        => 'f247',
        'fa-object-ungroup'                      => 'f248',
        'fa-sticky-note'                         => 'f249',
        'fa-sticky-note-o'                       => 'f24a',
        'fa-cc-jcb'                              => 'f24b',
        'fa-cc-diners-club'                      => 'f24c',
        'fa-clone'                               => 'f24d',
        'fa-balance-scale'                       => 'f24e',
        'fa-hourglass-o'                         => 'f250',
        'fa-hourglass-start'                     => 'f251',
        'fa-hourglass-half'                      => 'f252',
        'fa-hourglass-end'                       => 'f253',
        'fa-hourglass'                           => 'f254',
        'fa-hand-rock-o'                         => 'f255',
        'fa-hand-paper-o'                        => 'f256',
        'fa-hand-scissors-o'                     => 'f257',
        'fa-hand-lizard-o'                       => 'f258',
        'fa-hand-spock-o'                        => 'f259',
        'fa-hand-pointer-o'                      => 'f25a',
        'fa-hand-peace-o'                        => 'f25b',
        'fa-trademark'                           => 'f25c',
        'fa-registered'                          => 'f25d',
        'fa-creative-commons'                    => 'f25e',
        'fa-gg'                                  => 'f260',
        'fa-gg-circle'                           => 'f261',
        'fa-tripadvisor'                         => 'f262',
        'fa-odnoklassniki'                       => 'f263',
        'fa-odnoklassniki-square'                => 'f264',
        'fa-get-pocket'                          => 'f265',
        'fa-wikipedia-w'                         => 'f266',
        'fa-safari'                              => 'f267',
        'fa-chrome'                              => 'f268',
        'fa-firefox'                             => 'f269',
        'fa-opera'                               => 'f26a',
        'fa-internet-explorer'                   => 'f26b',
        'fa-television'                          => 'f26c',
        'fa-contao'                              => 'f26d',
        'fa-500px'                               => 'f26e',
        'fa-amazon'                              => 'f270',
        'fa-calendar-plus-o'                     => 'f271',
        'fa-calendar-minus-o'                    => 'f272',
        'fa-calendar-times-o'                    => 'f273',
        'fa-calendar-check-o'                    => 'f274',
        'fa-industry'                            => 'f275',
        'fa-map-pin'                             => 'f276',
        'fa-map-signs'                           => 'f277',
        'fa-map-o'                               => 'f278',
        'fa-map'                                 => 'f279',
        'fa-commenting'                          => 'f27a',
        'fa-commenting-o'                        => 'f27b',
        'fa-houzz'                               => 'f27c',
        'fa-vimeo'                               => 'f27d',
        'fa-black-tie'                           => 'f27e',
        'fa-fonticons'                           => 'f280',
        'fa-reddit-alien'                        => 'f281',
        'fa-edge'                                => 'f282',
        'fa-credit-card-alt'                     => 'f283',
        'fa-codiepie'                            => 'f284',
        'fa-modx'                                => 'f285',
        'fa-fort-awesome'                        => 'f286',
        'fa-usb'                                 => 'f287',
        'fa-product-hunt'                        => 'f288',
        'fa-mixcloud'                            => 'f289',
        'fa-scribd'                              => 'f28a',
        'fa-pause-circle'                        => 'f28b',
        'fa-pause-circle-o'                      => 'f28c',
        'fa-stop-circle'                         => 'f28d',
        'fa-stop-circle-o'                       => 'f28e',
        'fa-shopping-bag'                        => 'f290',
        'fa-shopping-basket'                     => 'f291',
        'fa-hashtag'                             => 'f292',
        'fa-bluetooth'                           => 'f293',
        'fa-bluetooth-b'                         => 'f294',
        'fa-percent'                             => 'f295',
        'fa-gitlab'                              => 'f296',
        'fa-wpbeginner'                          => 'f297',
        'fa-wpforms'                             => 'f298',
        'fa-envira'                              => 'f299',
        'fa-universal-access'                    => 'f29a',
        'fa-wheelchair-alt'                      => 'f29b',
        'fa-question-circle-o'                   => 'f29c',
        'fa-blind'                               => 'f29d',
        'fa-audio-description'                   => 'f29e',
        'fa-volume-control-phone'                => 'f2a0',
        'fa-braille'                             => 'f2a1',
        'fa-assistive-listening-systems'         => 'f2a2',
        'fa-american-sign-language-interpreting' => 'f2a3',
        'fa-deaf'                                => 'f2a4',
        'fa-glide'                               => 'f2a5',
        'fa-glide-g'                             => 'f2a6',
        'fa-sign-language'                       => 'f2a7',
        'fa-low-vision'                          => 'f2a8',
        'fa-viadeo'                              => 'f2a9',
        'fa-viadeo-square'                       => 'f2aa',
        'fa-snapchat'                            => 'f2ab',
        'fa-snapchat-ghost'                      => 'f2ac',
        'fa-snapchat-square'                     => 'f2ad',
        'fa-pied-piper'                          => 'f2ae',
        'fa-first-order'                         => 'f2b0',
        'fa-yoast'                               => 'f2b1',
        'fa-themeisle'                           => 'f2b2',
        'fa-google-plus-official'                => 'f2b3',
        'fa-font-awesome'                        => 'f2b4'
    );

    const ERROR_OK = 200;
    const ERROR_FAIL = 500;

    const ERROR_VALIDATION = 201;
    const ERROR_NOT_FOUND = 208;
    const ERROR_MISSING_PARAMS = 202;
    const ERROR_MISMATCH_PARAMS  = 203;
    const ERROR_TOKEN_MISSING = 204;
    const ERROR_TOKEN_MISMATCH  = 205;
    const ERROR_INVALID_PARAMS  = 206;

    const ERROR_USER_NOT_FOUND  = 221;


    const ERRORS_ARRAY = array(
        self::ERROR_OK => 'Success',
        self::ERROR_FAIL => 'Fail',
        self::ERROR_MISSING_PARAMS =>  FError::MISSING_PARAMS,
        self::ERROR_MISMATCH_PARAMS =>  FError::MISMATCH_PARAMS,
        self::ERROR_TOKEN_MISSING => 'Token missing',
        self::ERROR_TOKEN_MISMATCH =>  FError::TOKEN_MISMATCH,
        '206' =>  FError::CAN_NOT_PERFORM,
        '207' => 'Paid fail',
        self::ERROR_NOT_FOUND => 'Data not found',
        '221' => 'User not found',
        '222' => 'Wrong password',
        '223' => 'Email does not exist',
        '224' => 'Email or username does not exist',
        '225' => 'Email existed',
        '226' => 'Email or username existed',
        '227' => 'Current password mismatch',
        '228' => 'Your account is not activated',
        '229' => 'Fail to send email, please check your email address',
        '230' => 'Your account balance is not enough to do this action',
        '233' => 'Please login by your social network account and change password',
        '234' => 'Current password and new password are the same',
        '235' => 'You are not moderator',
        '240' => 'Your request is in processing, please wait',
        '250' => 'Account existed',
         self::ERROR_INVALID_PARAMS => 'Invalid Parameters'
    );

    const
        MISSING_PARAMS = 'Missing parameters',
        MISMATCH_PARAMS = 'Params mismatch';

    const
        EMAIL_OR_USERNAME_EXIST = 'Email or username existed',
        PAGE_INDEX_INVALID = 'Invalid Page Index',
        EMAIL_EXIST = 'Email exist',
        EMAIL_DOES_NOT_EXIST = 'Email does not exist',
        EMAIL_OR_USERNAME_DOES_NOT_EXIST = 'Email or username does not exist',
        WRONG_PASSWORD = 'Wrong password',
        USER_NOT_FOUND = 'User not found',
        DEAL_NOT_FOUND = 'Deal not found',
        DATA_NOT_FOUND = 'Data not found',

        TOKEN_MISMATCH = 'Token mismatch',
        INVALID_FOOTPRINT =  'Invalid Footprint',
        EXPIRED_FOOTPRINT = 'Expired Footprint',
        FAIL = 'Fail',
        NOT_FOUND = 'Not found',
        DENIED = 'Denied',
        ACCESS_DENIED = 'Access Denied',

    CAN_NOT_PERFORM = 'You can not perform this action';

    const DB_TYPE_SQL = 'sql',
        DB_TYPE_PHP = 'php',
        DB_TYPE_CSV = 'csv',
        DB_TYPE_FILE_PHP = 'file_php',
        DB_TYPE_JSON = 'json',
        DB_TYPE_WORDPRESS = 'wordpress',
        DB_TYPE_API = 'api',
        DB_TYPE_ELASTIC = 'elastic';

//    Auth
    const STATE_ACTIVE = 1,
        STATE_INACTIVE = 0;

    const
        CHANGE_TYPE = 'change',
        CLEAR_TYPE = 'clear',
        FILL_TYPE = 'fill';

    //HungHX: 20160801
    const
        NULL_VALUE = '...',
        NULL_LABEL = '';

    //2017/3/21
    const
        STATUS_ACTIVE = '1',
        STATUS_INACTIVE = '0',
        STATUS_NEW = 'new',
        STATUS_PLANNING = 'planning',
        STATUS_PROCESSING = 'processing',
        STATUS_SHIPPING = 'shipping',
        STATUS_DONE = 'done',
        STATUS_APPROVED = 'approved',
        STATUS_FINISH = self::STATUS_DONE,
        STATUS_FAIL = 'fail',
        STATUS_HALF = 'half',
        STATUS_PENDING = 'pending',
        STATUS_REJECTED = 'rejected',
        STATUS_ON_HOLD = 'on-hold',
         STATUS_ARRAY = [FHtml::STATUS_NEW, FHtml::STATUS_PROCESSING, FHtml::STATUS_DONE, FHtml::STATUS_PENDING, FHtml::STATUS_FAIL];

    const
        CODE_NEWLINE = "
        ",
        CODE_TAB = '    ',
        CODE_TAB2 = '       ',
        CODE_TAB3 = '           ';

    const
        TYPE_ANDROID = 1,
        TYPE_IOS = 2,
        NO_IMAGE = 'no_image.jpg';
    const
        DEFAULT_ITEMS_PER_PAGE = 50;
    const
        WIDGET_COLOR_DEFAULT = "light",
        WIDGET_TYPE_DEFAULT = "light bordered",
        WIDGET_TYPE_BOX = "box",
        WIDGET_TYPE_NONE = "light", // light, light bordered, box
        WIDGET_TYPE_LIGHT = "light bordered";

    const RENDER_TYPE_CODE = 'code';
    const RENDER_TYPE_AUTO = 'auto';
    const RENDER_TYPE_DB_SETTING = 'database';

    const VIEWS_PRINT_HEADER = '../../../../views/www/_print_header';
    const VIEWS_PRINT_HEADER1 = '../../../../views/www/_print_header';

    const MULTILANG_TEXT_REMOVALS = [' id', ' ID'];

    const
        ADMIN_EMAIL = 'ADMIN_EMAIL',
        GOOGLE_API_KEY = 'GOOGLE_API_KEY',
        PEM_FILE = 'PEM_FILE',
        PRIVACY = 'PRIVACY',
        COMPANY_NAME = 'COMPANY_NAME',
        COMPANY_DESCRIPTION = 'COMPANY_DESCRIPTION',
        COMPANY_HOMEPAGE = 'COMPANY_HOMEPAGE',
        SUCCESS = 'SUCCESS',
        ERROR = 'ERROR';

    const
        LABEL_ACTIVE = 'active',
        LABEL_INACTIVE = 'inactive',
        LABEL_NEW = 'new',
        LABEL_NORMAL = 'normal',
        LABEL_PREMIUM = 'premium',
        LABEL_OLD = 'old';

    const
        ACTION_DELETE = 'delete',
        ACTION_EDIT = 'update',
        ACTION_ADD = 'add',
        ACTION_CREATE = 'create',
        ACTION_REJECT = 'reject',
        ACTION_APPROVED = 'approve',
        ACTION_VIEW = 'view',
        ACTION_SAVE = 'save',
        ACTION_LOAD = 'load',
        ACTION_SEND = 'send';

    const
        TABLE_USER = 'user',
        TABLE_SETTINGS = 'settings',
        TABLE_CATEGORIES = 'object_category',
        TABLE_PRODUCT = 'ecommerce_product',
        TABLE_ARTICLE = 'cms_article',
        TABLE_PROMOTION = 'cms_promotion',
        TABLE_PORTFOLIO = 'cms_portfolio',
        TABLE_BLOGS = 'cms_blogs',
        TABLE_NEWS = 'cms_blogs',
        TABLE_ABOUT = 'cms_about',
        TABLE_TESTIMONIAL = 'cms_testimonial',
        TABLE_EMPLOYEE = 'cms_employee',
        TABLE_OBJECT_TYPE = 'object_type',
        TABLE_SERVICE = 'cms_service',
        TABLE_FAQ = 'cms_faq',
        TABLE_CONTACT = 'cms_contact',
        TABLE_OBJECT_FILES = 'object_file',
        TABLE_OBJECT_ATTRIBUTES = 'object_attributes',
        TABLE_OBJECT_META = 'object_attributes',
        OBJECT_TYPE_DEFAULT = 'common',
        TABLE_OBJECT_SETTING = 'object_setting';

    const EDIT_TYPE_INLINE = 'inline',
        EDIT_TYPE_POPUP = 'popup',
        EDIT_TYPE_VIEW = 'view',
        EDIT_TYPE_DEFAULT = 'default',
        EDIT_TYPE_INPUT = 'input';

    const DISPLAY_TYPE_DEFAULT = 'list';
    const DISPLAY_TYPE_TABLE = 'table';
    const DISPLAY_TYPE_LIST = 'list';
    const DISPLAY_TYPE_GRID_SMALL = 'grid2';
    const DISPLAY_TYPE_GRID_BIG = 'grid3';
    const DISPLAY_TYPE_GRID = 'grid';
    const DISPLAY_TYPE_PRINT = 'print';
    const DISPLAY_TYPE_IMAGE = 'image';
    const DISPLAY_TYPE_WIDGET = 'widget';

    const COLUMNS_HIDDEN = array(
        'password*',
        'auth_*',
        'application_id',
        'sort_order',
        'thumbnail',
        '*content',
        '*description',
        '*tags',
        '*overview',
        '*created',
        '*modified',
        '*user',
        'create_date', 'created_*', 'modified_*', 'modified_date'
    );

    const COLUMNS_FORM_HIDDEN = array(
        'password*',
        'auth_*',
        'application_id',
        'sort_order'
    );

    const COLUMNS_FORM_READONLY = array(
        'count*',
        'rate*',
        'created_date',
        'created_user',
        'modified_date',
        'modified_user',

        );

    const COLUMNS_VISIBLE = array(
        'code',
        'name',
        'title',
        'image',
        'icon',
        'color',
        'lang',
        'phone',
        'address',
        'type',
        'status',
        'price',
        'is_active', 'is_*',
        'is_top',
        'category_id'
    );

    const COLUMNS_SYSTEMS = array(
        'application_id'
    );

    const
        ARRAY_CONTROLS_ALIGNMENT = [
        ['id' => 'horizontal', 'name' => 'Horizontal'],
        ['id' => 'vertical', 'name' => 'Vertical'],
        ['id' => 'inline', 'name' => 'Inline'],

    ],
        ARRAY_BUTTONS_STYLE = [
        ['id' => 'icons', 'name' => 'Icons'],
        ['id' => 'dropdown', 'name' => 'Dropdown'],
    ],
        ARRAY_OPEN_STYLE = [
        ['id' => 'open', 'name' => 'Open on default'],
        ['id' => 'closed', 'name' => 'Closed on default'],
        ['id' => 'none', 'name' => 'Hidden'],

    ],
        ARRAY_MENU_STYLE = [
        ['id' => 'open', 'name' => 'Open on default'],
        ['id' => 'closed', 'name' => 'Closed on default'],
        ['id' => 'none', 'name' => 'Hidden'],

    ],
        ARRAY_FOOTER_STYLE = [
        ['id' => 'normal', 'name' => 'Display Normal'],
        ['id' => 'fixed', 'name' => 'Hidden'],
    ],

        ARRAY_FONT_SIZE = [
        ['id' => '12px', 'name' => 'Tiny (12px)'],
        ['id' => '13px', 'name' => 'Small (13px)'],
        ['id' => '14px', 'name' => 'Normal (14px)'],
        ['id' => '16px', 'name' => 'Big (16px)'],
        ['id' => '20px', 'name' => 'Huge (20px)'],
        ['id' => '24px', 'name' => 'Giant (24px)'],

    ],
        ARRAY_ALIGNMENT = [
        ['id' => 'left', 'name' => 'Left'],
        ['id' => 'right', 'name' => 'Right'],
        ['id' => 'center', 'name' => 'Center']],

        ARRAY_TRANSITION_SPEED = [
        ['id' => '500', 'name' => 'Super fast'],
        ['id' => '1000', 'name' => 'Fast'],
        ['id' => '1500', 'name' => 'Normal'],
        ['id' => '2000', 'name' => 'Slow'],
        ['id' => '2500', 'name' => 'Very slow']],
        ARRAY_ADMIN_THEME = [
        ['id' => 'default', 'name' => 'Default'],
        ['id' => 'darkblue', 'name' => 'Dark Blue'],
        ['id' => 'light', 'name' => 'Light'],
        ['id' => 'light2', 'name' => 'Light 2'],
        ['id' => 'blue', 'name' => 'Blue'],
        ['id' => 'gray', 'name' => 'Gray']],
        ARRAY_BORDER_WIDTH = [
        ['id' => '', 'name' => 'None'],
        ['id' => '1px', 'name' => 'Thin (1px)'],
        ['id' => '2px', 'name' => 'Normal (2px)'],
        ['id' => '5px', 'name' => 'Big (5px)'],
        ],
        ARRAY_BORDER_TYPE = [
        ['id' => '', 'name' => 'None'],
        ['id' => 'all', 'name' => 'All'],
        ['id' => 'left', 'name' => 'Left'],
        ['id' => 'right', 'name' => 'Right'],
        ['id' => 'top', 'name' => 'Top'],
        ['id' => 'bottom', 'name' => 'Bottom'],

    ],
        ARRAY_BUTTONS_POSITION_TYPE = [
        ['id' => 'relative', 'name' => 'Normal'],
        ['id' => 'fixed', 'name' => 'Fixed ON BOTTOM']

    ],

        ARRAY_FORM_CONTROL_STYLE = [
        ['id' => 'box', 'name' => 'Box'],
        ['id' => 'round', 'name' => 'Round'],
        ['id' => 'round2', 'name' => 'Round Oval'],
        ['id' => 'line', 'name' => 'Line'],
        ['id' => 'none', 'name' => 'None']

    ],
        ARRAY_FORM_WIDTH_TYPE = [
        ['id' => 'full', 'name' => 'Main (Full)'],
        ['id' => 'layout', 'name' => 'Main | Preview (8:4)'],
    ],
        ARRAY_PORTLET_STYLE = [
        ['id' => 'none', 'name' => 'Fullscreen'],
        ['id' => 'box', 'name' => 'Box']
        ],
        ARRAY_THEME_STYLE = [
        ['id' => 'Material Design', 'name' => 'Material Design'],
        ['id' => 'Bootstrap', 'name' => 'Bootstrap'],
    ],
        ARRAY_BORDER_STYLE = [
        ['id' => 'none', 'name' => 'No Border'],
        ['id' => 'box', 'name' => 'Full'],
        ['id' => 'line', 'name' => 'Line (Only Bottom)']
    ],
        ARRAY_GRID_BUTTONS = [
        ['id' => 'icons', 'name' => 'icons'],
        ['id' => 'dropdown', 'name' => 'dropdown']],

        ARRAY_TRANSITION_TYPE = [
        ['id' => 'fade', 'name' => 'fade'],
        ['id' => 'zoomout', 'name' => 'zoomout'],
        ['id' => '3dcurtain-vertical', 'name' => '3dcurtain-vertical'],
        ['id' => 'random', 'name' => 'random']],

        ARRAY_FIELD_LAYOUT = [
        ['id' => self::LAYOUT_ONELINE, 'name' => self::LAYOUT_ONELINE],
        ['id' => self::LAYOUT_NEWLINE, 'name' => self::LAYOUT_NEWLINE],
        ['id' => self::LAYOUT_ONELINE_RIGHT, 'name' => self::LAYOUT_ONELINE_RIGHT],
        ['id' => self::LAYOUT_TEXT, 'name' => self::LAYOUT_TEXT],
        ['id' => self::LAYOUT_NO_LABEL, 'name' => self::LAYOUT_NO_LABEL],
        ['id' => self::LAYOUT_TABLE, 'name' => self::LAYOUT_TABLE],
    ],
        ARRAY_EDITOR = [
        ['id' => 'text', 'name' => 'Textarea'],
        ['id' => 'date', 'name' => 'Date'],
        ['id' => 'datetime', 'name' => 'DateTime'],
        ['id' => 'time', 'name' => 'Time'],
        ['id' => 'html', 'name' => 'Html'],
        ['id' => 'numeric', 'name' => 'Numeric'],
        ['id' => 'checkbox', 'name' => 'Boolean'],
        ['id' => 'file', 'name' => 'File'],
        ['id' => 'image', 'name' => 'Image'],
        ['id' => 'select', 'name' => 'Select'],
        ['id' => 'selectmany', 'name' => 'Select Array'],
        ['id' => 'rate', 'name' => 'Rate'],
        ['id' => 'slide', 'name' => 'Slide'],
    ],
        ARRAY_COLOR = [
        ['id' => 'default', 'name' => 'Grey'],
        ['id' => 'success', 'name' => 'Green'],
        ['id' => 'primary', 'name' => 'Blue'],
        ['id' => 'warning', 'name' => 'Yellow'],
        ['id' => 'danger', 'name' => 'Red']],
        ARRAY_GENDER = [
        ['id' => 'Male', 'name' => 'Male'],
        ['id' => 'Female', 'name' => 'Female'],
        ['id' => 'Kid', 'name' => 'Kid'],
        ['id' => 'N/A', 'name' => 'Dont know'],
        ['id' => 'Others', 'name' => 'Others']],
        ARRAY_DBTYPE = [
        ['id' => 'pk', 'name' => 'pk'],
        ['id' => 'upk', 'name' => 'upk'],
        ['id' => 'bigpk', 'name' => 'bigpk'],
        ['id' => 'ubigpk', 'name' => 'ubigpk'],
        ['id' => 'char', 'name' => 'char'],
        ['id' => 'string', 'name' => 'string'],
        ['id' => 'text', 'name' => 'text'],
        ['id' => 'smallint', 'name' => 'smallint'],
        ['id' => 'integer', 'name' => 'integer'],
        ['id' => 'bigint', 'name' => 'bigint'],
        ['id' => 'float', 'name' => 'float'],
        ['id' => 'double', 'name' => 'double'],
        ['id' => 'decimal', 'name' => 'decimal'],
        ['id' => 'datetime', 'name' => 'datetime'],
        ['id' => 'timestamp', 'name' => 'timestamp'],
        ['id' => 'time', 'name' => 'time'],
        ['id' => 'date', 'name' => 'date'],
        ['id' => 'binary', 'name' => 'binary'],
        ['id' => 'boolean', 'name' => 'boolean'],
        ['id' => 'money', 'name' => 'money'],
        ],

        ARRAY_DAYS_IN_WEEK = array('Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday', 'Saturday');

        const ARRAY_FILE_TYPES = array(
            "exe" => "application/octet-stream",
            "zip" => "application/zip",
            "mp3" => "audio/mpeg",
            "mp4" => "audio/mpeg",
            "mpg" => "video/mpeg",
            "avi" => "video/x-msvideo",
            'pdf' => 'application/pdf',
            'txt' => 'text/plain',
            'html' => 'text/html',
            'doc' => 'application/msword',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            'gif' => 'image/gif',
            'png' => 'image/png',
            'jpeg' => 'image/jpg',
            'jpg' => 'image/jpg',
            'php' => 'text/plain',
            'swf' => 'application/x-shockwave-flash',
            'rar' => 'application/rar',
            'ra' => 'audio/x-pn-realaudio',
            'ram' => 'audio/x-pn-realaudio',
            'ogg' => 'audio/x-pn-realaudio',
            'wav' => 'video/x-msvideo',
            'wmv' => 'video/x-msvideo',
            'asf' => 'video/x-msvideo',
            'divx' => 'video/x-msvideo',
            'mpeg' => 'video/mpeg',
            'mpe' => 'video/mpeg',
            'mov' => 'video/quicktime',
            '3gp' => 'video/quicktime',
            'm4a' => 'video/quicktime',
            'aac' => 'video/quicktime',
            'm3u' => 'video/quicktime',
        );

        const ARRAY_LANGUAGUES = [
            'ps' => 'Pashto (پښتو)',
            'sv' => 'Swedish (svenska)',
            'sq' => 'Albanian (Shqip)',
            'ar' => 'Arabic (العربية)',
            'en' => 'English (English)',
            'ca' => 'Catalan (català)',
            'pt' => 'Portuguese (Português)',
            'es' => 'Spanish (Español)',
            'hy' => 'Armenian (Հայերեն)',
            'nl' => 'Dutch (Nederlands)',
            'de' => 'German (Deutsch)',
            'az' => 'Azerbaijani (azərbaycan dili)',
            'bn' => 'Bengali (বাংলা)',
            'be' => 'Belarusian (беларуская мова)',
            'fr' => 'French (français)',
            'dz' => 'Dzongkha (རྫོང་ཁ)',
            'bs' => 'Bosnian (bosanski jezik)',
            'no' => 'Norwegian (Norsk)',
            'ms' => 'Malay (bahasa Melayu)',
            'bg' => 'Bulgarian (български език)',
            'km' => 'Khmer (ខ្មែរ)',
            'zh' => 'Chinese (中文 (Zhōngwén])',
            'hr' => 'Croatian (hrvatski jezik)',
            'el' => 'Greek (modern] (ελληνικά)',
            'cs' => 'Czech (čeština)',
            'da' => 'Danish (dansk)',
            'ti' => 'Tigrinya (ትግርኛ)',
            'et' => 'Estonian (eesti)',
            'am' => 'Amharic (አማርኛ)',
            'fo' => 'Faroese (føroyskt)',
            'fi' => 'Finnish (suomi)',
            'ka' => 'Georgian (ქართული)',
            'kl' => 'Kalaallisut (kalaallisut)',
            'la' => 'Latin (latine)',
            'hu' => 'Hungarian (magyar)',
            'is' => 'Icelandic (Íslenska)',
            'hi' => 'Hindi (हिन्दी)',
            'id' => 'Indonesian (Bahasa Indonesia)',
            'fa' => 'Persian (Farsi] (فارسی)',
            'ga' => 'Irish (Gaeilge)',
            'he' => 'Hebrew (modern] (עברית)',
            'it' => 'Italian (Italiano)',
            'ja' => 'Japanese (日本語 (にほんご])',
            'kk' => 'Kazakh (қазақ тілі)',
            'ky' => 'Kyrgyz (Кыргызча)',
            'lo' => 'Lao (ພາສາລາວ)',
            'lv' => 'Latvian (latviešu valoda)',
            'lt' => 'Lithuanian (lietuvių kalba)',
            'mk' => 'Macedonian (македонски јазик)',
            'my' => 'Malaysian (بهاس مليسيا)',
            'dv' => 'Divehi (ދިވެހި)',
            'mt' => 'Maltese (Malti)',
            'ro' => 'Romanian (Română)',
            'mn' => 'Mongolian (Монгол хэл)',
            'sr' => 'Serbian (српски језик)',
            'ne' => 'Nepali (नेपाली)',
            'ko' => 'Korean (한국어)',
            'pl' => 'Polish (język polski)',
            'ru' => 'Russian (Русский)',
            'rw' => 'Kinyarwanda (Ikinyarwanda)',
            'sm' => 'Samoan (gagana fa\'a Samoa)',
            'sk' => 'Slovak (slovenčina)',
            'sl' => 'Slovene (slovenski jezik)',
            'so' => 'Somali (Soomaaliga)',
            'af' => 'Afrikaans (Afrikaans)',
            'si' => 'Sinhalese (සිංහල)',
            'tg' => 'Tajik (тоҷикӣ)',
            'sw' => 'Swahili (Kiswahili)',
            'th' => 'Thai (ไทย)',
            'tr' => 'Turkish (Türkçe)',
            'tk' => 'Turkmen (Türkmen)',
            'uk' => 'Ukrainian (Українська)',
            'uz' => 'Uzbek (Oʻzbek)',
            'bi' => 'Bislama (Bislama)',
            'vi' => 'Vietnamese (Tiếng Việt)',
        ];


    const ARRAY_CURRENCY = array (
        'ALL' => 'Albania Lek',
        'AFN' => 'Afghanistan Afghani',
        'ARS' => 'Argentina Peso',
        'AWG' => 'Aruba Guilder',
        'AUD' => 'Australia Dollar',
        'AZN' => 'Azerbaijan New Manat',
        'BSD' => 'Bahamas Dollar',
        'BBD' => 'Barbados Dollar',
        'BDT' => 'Bangladeshi taka',
        'BYR' => 'Belarus Ruble',
        'BZD' => 'Belize Dollar',
        'BMD' => 'Bermuda Dollar',
        'BOB' => 'Bolivia Boliviano',
        'BAM' => 'Bosnia and Herzegovina Convertible Marka',
        'BWP' => 'Botswana Pula',
        'BGN' => 'Bulgaria Lev',
        'BRL' => 'Brazil Real',
        'BND' => 'Brunei Darussalam Dollar',
        'KHR' => 'Cambodia Riel',
        'CAD' => 'Canada Dollar',
        'KYD' => 'Cayman Islands Dollar',
        'CLP' => 'Chile Peso',
        'CNY' => 'China Yuan Renminbi',
        'COP' => 'Colombia Peso',
        'CRC' => 'Costa Rica Colon',
        'HRK' => 'Croatia Kuna',
        'CUP' => 'Cuba Peso',
        'CZK' => 'Czech Republic Koruna',
        'DKK' => 'Denmark Krone',
        'DOP' => 'Dominican Republic Peso',
        'XCD' => 'East Caribbean Dollar',
        'EGP' => 'Egypt Pound',
        'SVC' => 'El Salvador Colon',
        'EEK' => 'Estonia Kroon',
        'EUR' => 'Euro Member Countries',
        'FKP' => 'Falkland Islands (Malvinas) Pound',
        'FJD' => 'Fiji Dollar',
        'GHC' => 'Ghana Cedis',
        'GIP' => 'Gibraltar Pound',
        'GTQ' => 'Guatemala Quetzal',
        'GGP' => 'Guernsey Pound',
        'GYD' => 'Guyana Dollar',
        'HNL' => 'Honduras Lempira',
        'HKD' => 'Hong Kong Dollar',
        'HUF' => 'Hungary Forint',
        'ISK' => 'Iceland Krona',
        'INR' => 'India Rupee',
        'IDR' => 'Indonesia Rupiah',
        'IRR' => 'Iran Rial',
        'IMP' => 'Isle of Man Pound',
        'ILS' => 'Israel Shekel',
        'JMD' => 'Jamaica Dollar',
        'JPY' => 'Japan Yen',
        'JEP' => 'Jersey Pound',
        'KZT' => 'Kazakhstan Tenge',
        'KPW' => 'Korea (North) Won',
        'KRW' => 'Korea (South) Won',
        'KGS' => 'Kyrgyzstan Som',
        'LAK' => 'Laos Kip',
        'LVL' => 'Latvia Lat',
        'LBP' => 'Lebanon Pound',
        'LRD' => 'Liberia Dollar',
        'LTL' => 'Lithuania Litas',
        'MKD' => 'Macedonia Denar',
        'MYR' => 'Malaysia Ringgit',
        'MUR' => 'Mauritius Rupee',
        'MXN' => 'Mexico Peso',
        'MNT' => 'Mongolia Tughrik',
        'MZN' => 'Mozambique Metical',
        'NAD' => 'Namibia Dollar',
        'NPR' => 'Nepal Rupee',
        'ANG' => 'Netherlands Antilles Guilder',
        'NZD' => 'New Zealand Dollar',
        'NIO' => 'Nicaragua Cordoba',
        'NGN' => 'Nigeria Naira',
        'NOK' => 'Norway Krone',
        'OMR' => 'Oman Rial',
        'PKR' => 'Pakistan Rupee',
        'PAB' => 'Panama Balboa',
        'PYG' => 'Paraguay Guarani',
        'PEN' => 'Peru Nuevo Sol',
        'PHP' => 'Philippines Peso',
        'PLN' => 'Poland Zloty',
        'QAR' => 'Qatar Riyal',
        'RON' => 'Romania New Leu',
        'RUB' => 'Russia Ruble',
        'SHP' => 'Saint Helena Pound',
        'SAR' => 'Saudi Arabia Riyal',
        'RSD' => 'Serbia Dinar',
        'SCR' => 'Seychelles Rupee',
        'SGD' => 'Singapore Dollar',
        'SBD' => 'Solomon Islands Dollar',
        'SOS' => 'Somalia Shilling',
        'ZAR' => 'South Africa Rand',
        'LKR' => 'Sri Lanka Rupee',
        'SEK' => 'Sweden Krona',
        'CHF' => 'Switzerland Franc',
        'SRD' => 'Suriname Dollar',
        'SYP' => 'Syria Pound',
        'TWD' => 'Taiwan New Dollar',
        'THB' => 'Thailand Baht',
        'TTD' => 'Trinidad and Tobago Dollar',
        'TRY' => 'Turkey Lira',
        'TRL' => 'Turkey Lira',
        'TVD' => 'Tuvalu Dollar',
        'UAH' => 'Ukraine Hryvna',
        'GBP' => 'United Kingdom Pound',
        'USD' => 'United States Dollar',
        'UYU' => 'Uruguay Peso',
        'UZS' => 'Uzbekistan Som',
        'VEF' => 'Venezuela Bolivar',
        'VND' => 'Viet Nam Dong',
        'YER' => 'Yemen Rial',
        'ZWD' => 'Zimbabwe Dollar'
    );

    const
        RELATION_FOREIGN_KEY = '_',
        RELATION_ONE_MANY = 'has',
        RELATION_MANY_MANY = '';

    /**
     * Bootstrap Contextual Color Types
     */
    const TYPE_DEFAULT = 'default'; // only applicable for panel contextual style
    const TYPE_PRIMARY = 'primary';
    const TYPE_INFO = 'info';
    const TYPE_DANGER = 'danger';
    const TYPE_WARNING = 'warning';
    const TYPE_SUCCESS = 'success';
    const TYPE_ACTIVE = 'active'; // only applicable for table row contextual style

    /**
     * Expand Row States
     */
    const ROW_NONE = -1;
    const ROW_EXPANDED = 0;
    const ROW_COLLAPSED = 1;

    /**
     * Alignment
     */
    // Horizontal Alignment
    const ALIGN_RIGHT = 'right';
    const ALIGN_CENTER = 'center';
    const ALIGN_LEFT = 'left';
    // Vertical Alignment
    const ALIGN_TOP = 'top';
    const ALIGN_MIDDLE = 'middle';
    const ALIGN_BOTTOM = 'bottom';
    // CSS for preventing cell wrapping
    const NOWRAP = 'kv-nowrap';

    // form inputs
    const INPUT_HIDDEN = 'hiddenInput';
    const INPUT_TEXT = 'textInput';
    const INPUT_TEXTAREA = 'textarea';
    const INPUT_PASSWORD = 'passwordInput';
    const INPUT_DROPDOWN_LIST = 'dropdownList';
    const INPUT_LIST_BOX = 'listBox';
    const INPUT_CHECKBOX = 'checkbox';
    const INPUT_RADIO = 'radio';
    const INPUT_CHECKBOX_LIST = 'checkboxList';
    const INPUT_RADIO_LIST = 'radioList';
    const INPUT_MULTISELECT = 'multiselect';
    const INPUT_STATIC = 'staticInput';
    const INPUT_FILE = 'fileInput';
    const INPUT_HTML5 = 'input';
    const INPUT_WIDGET = 'widget';
    const INPUT_RAW = 'raw'; // any free text or html markup
    const INPUT_HTML = 'html';
    const INPUT_NUMERIC = 'numeric';
    const INPUT_READONLY = 'readonly';
    const INPUT_INLINE = 'inline';

    const
        COLUMN_VIEW = 'kartik\grid\DataColumn',
        COLUMN_EDIT = 'kartik\grid\EditableColumn',
        EDITOR_BOOLEAN = '\kartik\checkbox\CheckboxX',
        EDITOR_BOOLEAN_SETTINGS = "'pluginOptions' => ['theme' => 'krajee-flatblue', 'size'=>'md', 'threeState'=>false]",

        EDITOR_DATE = '\kartik\widgets\DatePicker',
        EDITOR_DATE_SETTINGS = "'pluginOptions' => ['format' => FHtml::config(FHtml::SETTINGS_DATE_FORMAT, 'dd M yyyy'), 'class' => 'form-control', 'autoclose' => true, 'todayHighlight' => true, 'todayBtn' => true ]",

        EDITOR_DATETIME = '\kartik\widgets\DateTimePicker',
        EDITOR_DATETIME_SETTINGS = "'convertFormat' => true, 'pluginOptions' => ['format' => FHtml::config(FHtml::SETTINGS_TIME_FORMAT, 'dd M yyyy hh:ii'), 'autoclose' => true, 'todayHighlight' => true, 'todayBtn' => true, 'daysOfWeekDisabled' => FHtml::config(FHtml::SETTINGS_DAYS_OF_WEEK_DISABLED, '0,6'), 'hoursDisabled' => FHtml::config(FHtml::SETTINGS_HOURS_DISABLED, '0,1,2,3,4,5,6,7,8,19,20,21,22')]",

        EDITOR_TIME = '\kartik\widgets\TimePicker',
        EDITOR_TIME_SETTINGS = " 'pluginOptions'=> ['showMeridian'=>false, 'showSeconds' => false, 'minuteStep' => 15]",

        EDITOR_TEXT = 'FCKEditor',
        EDITOR_TEXT_SETTINGS = "'options' => ['rows' => 5, 'disabled' => false], 'preset' => 'normal'",

        EDITOR_SELECT = '\kartik\widgets\Select2',
        EDITOR_SELECT_SETTINGS = "'pluginOptions' => ['allowClear' => true, 'tags' => true]",

        EDITOR_RATE = '\kartik\widgets\StarRating',
        EDITOR_RATE_SETTINGS = "'pluginOptions' => [ 'stars' => 5, 'min' => 0, 'max' => 5, 'step' => 1, 'showClear' => true, 'showCaption' => true, 'defaultCaption' => '{rating}', 'starCaptions' => [0 => '', 1 => 'Poor', 2 => 'OK', 3 => 'Good', 4 => 'Super', 5 => 'Extreme']]",

//        EDITOR_NUMERIC = '\kartik\widgets\TouchSpin',
//        EDITOR_NUMERIC_SETTINGS = "'pluginOptions' => [ 'initval' => 1, 'min' => 0, 'max' => 10000000000000000, 'step' => 1, 'decimals' => 0,  'verticalbuttons' => true, 'verticalupclass' => 'glyphicon glyphicon-plus', 'verticaldownclass' => 'glyphicon glyphicon-minus','prefix' => '', 'postfix' => '']",
//
//        EDITOR_CURRENCY = '\kartik\money\MaskMoney',
//        EDITOR_CURRENCY_SETTINGS = "'pluginOptions' => [ 'initval' => 1, 'min' => 0, 'max' => 999999999999999999, 'thousands' => ',',  'decimal' => '.', 'precision' => 2, 'allowZero' => true, 'allowNegative' => false, 'suffix' => '', 'prefix' => '', 'affixesStay' => false,]",

        EDITOR_NUMERIC = '\yii\widgets\MaskedInput',
        EDITOR_NUMERIC_SETTINGS = "'clientOptions' => ['alias' =>  'numeric', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]",

        EDITOR_SWITCH = 'kartik\switchinput\SwitchInput',
        EDITOR_SWITCH_SETTINGS = "",

        EDITOR_HTML = 'common\widgets\FCKEditor',
        EDITOR_HTML_SETTINGS = "",

        EDITOR_CURRENCY = '\yii\widgets\MaskedInput',
        EDITOR_CURRENCY_SETTINGS = "'clientOptions' => ['alias' =>  'decimal', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]",

        EDITOR_COLOR = '\kartik\widgets\ColorInput',
        EDITOR_COLOR_SETTINGS = "",

        EDITOR_MASK = '\yii\widgets\MaskedInput',
        EDITOR_MASK_SETTINGS = "",

        EDITOR_SLIDE = 'kartik\slider\Slider',
        EDITOR_SLIDE_SETTINGS = "'sliderColor'=>Slider::TYPE_GREY, 'handleColor'=>Slider::TYPE_DANGER, 'pluginOptions'=>['min'=>0,'max'=>100,'step'=>1]",
        EDITOR_SLIDE_RANGE_SETTINGS = "'sliderColor'=>Slider::TYPE_GREY, 'handleColor'=>Slider::TYPE_DANGER, 'pluginOptions'=>['min'=>0,'max'=>100,'step'=>1,'range'=>true]",

        EDITOR_FILE = '\common\widgets\FFileInput',
        EDITOR_FILE_SETTINGS = "'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'previewFileType' => 'any', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])]",

        EDITOR_RANGE = '\kartik\widgets\RangeInput',
        EDITOR_RANGE_SETTINGS = "'pluginOptions' => ['placeholder' => 'Rate (0 - 10)...', 'html5Options' => ['min' => 0, 'max' => 100], 'addon' => ['append' => ['content' => 'star']]]";

    const
        SUBMIT_TYPE = 'submit';
    const
        BUTTON_CREATE = 'create',
        BUTTON_UPDATE = 'update',
        BUTTON_DELETE = 'delete',
        BUTTON_PROCESS = 'processing',
        BUTTON_PENDING = 'pending',
        BUTTON_RESET = 'reset',
        BUTTON_SEARCH = 'search',
        BUTTON_EDIT = 'update',
        BUTTON_CANCEL = 'cancel',
        BUTTON_ADD = 'add',
        BUTTON_REMOVE = 'remove',
        BUTTON_SELECT = 'select',
        BUTTON_MOVE = 'move',
        BUTTON_RELOAD = 'reload',
        BUTTON_OK = 'ok',
        BUTTON_COPY = 'copy',
        BUTTON_ACCEPT = 'accept',
        BUTTON_REJECT = 'reject',
        BUTTON_APPROVED = 'approve',
        BUTTON_BACK = 'back',
        BUTTON_READ = 'read',
        BUTTON_UNREAD = 'unread',
        BUTTON_CONFIRM = 'confirm',
        BUTTON_COMPLETE = 'complete',
        BUTTON_REVERT = 'revert',
        BUTTON_SEND = 'send';


    const
        SHOW_LINK = 'link',
        SHOW_DATE = 'date',
        SHOW_LABEL = 'label',
        SHOW_BOOLEAN = 'boolean',
        SHOW_USER = 'user',
        SHOW_ROLE = 'role',
        SHOW_TIME = 'time',
        SHOW_TIMESTAMP = 'timestamp',
        SHOW_DATETIME = 'datetime',
        SHOW_PARENT = 'parentid',
        SHOW_NUMBER = 'number',
        SHOW_CURRENCY = 'currency',
        SHOW_DECIMAL = 'decimal',
        SHOW_LOOKUP = 'lookup',
        SHOW_RATE = 'rate',
        SHOW_EMAIL = 'email',
        SHOW_RANGE = 'range',
        SHOW_FILE = 'file',
        SHOW_TEXT = 'text',
        SHOW_MASK = 'mask',
        SHOW_DATE_FRIENDLY = 'date_friendly',
        SHOW_ACTIVE = 'active',
        SHOW_COLOR = 'color',
        SHOW_IMAGE = 'image',
        SHOW_HIDDEN = 'hidden',
        SHOW_HTML = 'html',
        SHOW_IFRAME = 'iframe',
        SHOW_VIDEO = 'video',
        SHOW_STYLESHEET = 'style',
        SHOW_SCRIPT = 'script',
        SHOW_WIDGET = 'widget',
        SHOW_VIEW = 'view',
        SHOW_GOOGLE_MAP = 'google_map';

    const COLORS = [0 => 'default', 1 => 'primary', 2 => 'success', 3 => 'warning', 4 => 'danger'];
    const COLORS_BACKGROUND_ARRAYS = ['u', 'red', 'blue', 'sea', 'yellow', 'dark', 'grey'];

    const
        LAYOUT_ONELINE = 'one_line',
        LAYOUT_NEWLINE = 'new_line',
        LAYOUT_ONELINE_RIGHT = 'one_line_right',
        LAYOUT_TEXT = 'text',
        LAYOUT_NO_LABEL = 'nolabel',
        LAYOUT_TABLE = 'table';

    const
        CSS_IMAGE_DEFAULT = 'img-responsive',
        CSS_IMAGE_ROUND = 'img-responsive rounded-x',
        CSS_IMAGE_ANIMATED_SWING = 'wow swing img-responsive',
        CSS_IMAGE_ANIMATED_FADE = 'wow fadeIn img-responsive',
        CSS_IMAGE_ANIMATED_BOUNCE = 'wow bounceIn img-responsive',
        CSS_IMAGE_ANIMATED_PULSE = 'wow pulse img-responsive',
        CSS_IMAGE_ANIMATED_ROLL = 'wow rollIn img-responsive',
        CSS_IMAGE_ANIMATED_ZOOM = 'wow zoomIn img-responsive',
        CSS_IMAGE_ROUND_BORDER = 'img-responsive img-bordered rounded-x',
        CSS_IMAGE_SQUARE_BORDER = 'img-responsive img-bordered rounded-2x',
        CSS_IMAGE_SQUARE = 'img-responsive rounded-2x',
        CSS_DIV_SHADOW_BORDER = 'box-shadow shadow-effect-1 rounded-2x',
        CSS_DIV_SHADOW = 'box-shadow shadow-effect-1';

    const CSS_ANIMATED_ARRAYS = ['swing', 'fadeIn', 'bounceIn', 'pulse', 'rollIn', 'zoomIn'];

    const WIDGET_WIDTH_FULL = 'full',
        WIDGET_WIDTH_CONTAINER = 'container',
        WIDGET_BACKGROUND_PARRALAX = 'parallax-team parallaxBg',
        WIDGET_BACKGROUND_PARRALAX1 = 'parallax-counter-v1 parallaxBg',
        WIDGET_BACKGROUND_PARRALAX2 = 'parallax-counter-v2 parallaxBg',
        WIDGET_BACKGROUND_PARRALAX3 = 'parallax-counter-v3 parallaxBg',
        WIDGET_BACKGROUND_PARRALAX4 = 'parallax-counter-v4 parallaxBg',
        WIDGET_BACKGROUND_DARK = 'twitter-block parallaxBg';

    const WIDGET_BACKGROUND_ARRAYS = [
        '',
        'parallax-team parallaxBg', 'parallax-counter-v1 parallaxBg', 'parallax-counter-v2 parallaxBg',
        'parallax-counter-v3 parallaxBg', 'parallax-counter-v4 parallaxBg', 'twitter-block parallaxBg'
    ];

    const
        HEADLINE_TYPE_LEFT = 'headline',
        HEADLINE_TYPE_CENTER = 'heading',
        HEADLINE_TYPE_CENTER_V1 = 'heading heading-v1',
        HEADLINE_TYPE_CENTER_V2 = 'heading heading-v2',
        HEADLINE_TYPE_TITLE2 = 'title-box-v2',
        HEADLINE_TYPE_TITLE1 = 'title-box-v1';

       // Basic Configs
    const APP_NAME = 'name';
    const APP_LICENSE = 'purchase_license';
    const APP_SITE = 'purchase_site';
    const APP_SECRET = 'app.secret';
    const APP_VERSION = 'app.version';
    const APP_INSTALLED = 'app.installed';

    // Recaptcha Settings
    const RECAPTCHA_SITE_KEY = 'recaptcha.site-key';
    const RECAPTCHA_SECRET     = 'recaptcha.secret';
    const RECAPTCHA_DEBUG_MODE = 'recaptcha.debugMode';

    // Cache
    const CACHE_CLASS = 'cache.class';

    // Admin
    const ADMIN_INSTALL_ID = 'admin.installationId';

    // Yii2-User
    const USER_REGISTRATION                = 'user.enableRegistration';
    const USER_PASSWORD_RESET_TOKEN_EXPIRE = 'user.passwordResetTokenExpire';
    const USER_FORGOT_PASSWORD             = 'user.enableForgotPassword';
    const REMEMBER_ME_DURATION             = 'user.rememberMeDuration';
    const USER_LOGIN_TYPE                  = 'user.loginType';

    // Config File
    const CONFIG_FILE  = 'dynamicConfigFile';
    const PARAMS_FILE  = 'dynamicParamsFile';
    const MODULES_FILE = 'dynamicModulesFile';

    // Migrations Folder
    const MIGRATE_FOLDER = 'migrateFolder';

    // Mailer
    const MAILER_USE_TRANSPORT = 'mail.useTransport';
    const MAILER_HOST          = 'mail.host';
    const MAILER_USERNAME      = 'mail.username';
    const MAILER_PASSWORD      = 'mail.password';
    const MAILER_PORT          = 'mail.port';
    const MAILER_ENCRYPTION    = 'mail.encryption';

    // Authentication Clients

    // Google
    const GOOGLE_AUTH          = 'authClientCollection.google';
    const GOOGLE_CLIENT_ID     = 'authClientCollection.google.clientId';
    const GOOGLE_CLIENT_SECRET = 'authClientCollection.google.clientSecret';

    // Facebook
    const FACEBOOK_AUTH          = 'authClientCollection.facebook';
    const FACEBOOK_CLIENT_ID     = 'authClientCollection.facebook.clientId';
    const FACEBOOK_CLIENT_SECRET = 'authClientCollection.facebook.clientSecret';

    // Linked In
    const LINKED_IN_AUTH          = 'authClientCollection.linkedIn';
    const LINKED_IN_CLIENT_ID     = 'authClientCollection.linkedIn.clientId';
    const LINKED_IN_CLIENT_SECRET = 'authClientCollection.linkedIn.clientSecret';

    // Github
    const GITHUB_AUTH          = 'authClientCollection.github';
    const GITHUB_CLIENT_ID     = 'authClientCollection.github.clientId';
    const GITHUB_CLIENT_SECRET = 'authClientCollection.github.clientSecret';

    // Live
    const LIVE_AUTH          = 'authClientCollection.live';
    const LIVE_CLIENT_ID     = 'authClientCollection.live.clientId';
    const LIVE_CLIENT_SECRET = 'authClientCollection.live.clientSecret';

    // Twitter
    const TWITTER_AUTH            = 'authClientCollection.twitter';
    const TWITTER_CONSUMER_KEY    = 'authClientCollection.twitter.consumerKey';
    const TWITTER_CONSUMER_SECRET = 'authClientCollection.twitter.consumerSecret';

    const
        CONFIG_DB = 'db';

    const COLORS_PALETTE_SIMPLE_OPTIONS = [
        'showPalette' => true,
        'showPaletteOnly' => true,
        'showSelectionPalette' => true,
        'showAlpha' => false,
        'allowEmpty' => true,
        'preferredFormat' => 'name',
        'palette' => [
            [
                "white", "black", "grey", "silver", "gold", "brown",
            ],
            [
                "red", "orange", "yellow", "indigo", "maroon", "pink"
            ],
            [
                "blue", "green", "violet", "cyan", "magenta", "purple",
            ],
        ]
    ];

    protected static $buttonIcons = array(
        self::BUTTON_CREATE => 'fa fa-plus',
        self::BUTTON_SEARCH => 'fa fa-search',
        self::BUTTON_APPROVED => 'fa fa-check',
        self::BUTTON_UPDATE => 'fa fa-save',
        self::BUTTON_DELETE => 'fa fa-trash',
        self::BUTTON_RESET => 'fa fa-refresh',
        self::BUTTON_EDIT => 'fa fa-pencil',
        self::BUTTON_CANCEL => 'fa fa-cancel',
        self::BUTTON_COPY => 'fa fa-copy',
        self::BUTTON_ADD => 'fa fa-plus',
        self::BUTTON_REMOVE => 'fa fa-trash',
        self::BUTTON_SELECT => 'fa fa-share',
        self::BUTTON_MOVE => 'fa fa-move',
        self::BUTTON_OK => 'fa fa-ok',
        self::BUTTON_ACCEPT => 'fa fa-plus',
        self::BUTTON_REJECT => 'fa fa-lock',
        self::BUTTON_APPROVED => 'fa fa-ok-sign',
        self::BUTTON_BACK => 'fa fa-arrow-left',
        self::BUTTON_READ => 'fa fa-bookmark',
        self::BUTTON_UNREAD => 'fa fa-bookmark',
        self::BUTTON_CONFIRM => 'fa fa-signin',
        self::BUTTON_COMPLETE => 'fa fa-remove',
        self::BUTTON_REVERT => 'fa fa-share',
        self::BUTTON_SEND => 'm-fa fa-swapright',
        self::BUTTON_PROCESS => 'fa fa-play',
        self::BUTTON_PENDING => 'fa fa-pause',
    );

    public static $imageExtension = array('jpg', 'png', 'gif');

    const FIELDS_GROUP = [
        'lang*', '*type', '*status', '*parent_id', '*parentid', '*category_id', 'is_*'];

    const TABLES_COMMON = ['object_*', 'user*', 'application*', 'settings*', 'object*'];
    const FIELDS_PREVIEW = ['sort_order', 'count_*', '*_count', 'created_date', 'updated_date', 'modified_date', 'created_user', 'updated_user', 'modified_user', 'created_userid', 'updated_userid', 'modified_userid'];
    const FIELDS_HIDDEN = ['password*', 'auth_*', 'sort_order', 'application_id', 'created_date', 'updated_date', 'modified_date', 'created_user', 'updated_user', 'modified_user', 'created_userid', 'updated_userid', 'modified_userid'];
    const FIELDS_VISIBLE = ['code', 'name', 'image', 'description', 'overview', 'category_id', 'type', 'status', 'is_top', 'is_hot', 'is_active'];

    const FIELDS_COUNT = ['count_*', '*_count'];
    const FIELDS_UPLOAD = ['file*', 'document*', 'attachment*', 'thumbnail*', 'avatar*', 'image*', '*image', 'banner*', '*banner', 'banner', 'logo', 'logo*', '*logo'];
    const FIELDS_FILES = ['*file', '*document', '*attachment'];
    const FIELDS_IMAGES = array('image', 'thumbnail', 'avatar', 'banner', 'logo', '*thumbnail', '*avatar', '*image', '*banner', '*logo');
    const FIELDS_PRICE = ['cost*', '*cost', '*price', 'price*'];
    const FIELDS_DATE = ['date*', '*date', '*time', 'time*'];
    const FIELDS_HTML = ['content', 'note'];
    const FIELDS_COMMON = ['count*', 'created_date', 'updated_date', 'modified_date', 'created_user', 'updated_user', 'modified_user', 'created_userid', 'updated_userid', 'modified_userid'];
    const FIELDS_TEXTAREA = array('comment', '*_comment', 'overview', 'description', '*overview', 'overview*', '*description', 'description*');
    const FIELDS_TEXTAREASMALL = array('*_credit', '*_description', '*keywords', '*tags');
    const FIELDS_LOOKUP = array('type', 'type*', '*type', 'status', 'status*', '*status', '*id', '*user', '*userid', '*parent_id', '*parentid', 'lang', 'gender', 'gender_*', '*_gender', 'tax', '*_tax', 'tax_*', 'label_color', 'background_color');
    const FIELDS_TIME = array('*time', 'time_*');
    const FIELDS_DATETIME = array('*datetime', 'datetime*');
    const FIELDS_RATE = array('rates', 'rate');
    const FIELDS_BOOLEAN = array('is_*');
    const FIELDS_PERCENT = array('percent', 'progress', 'discount', '*_percent', '*_progress', '*_discount', 'percent_*', 'progress_*', 'discount_*');
    const FIELDS_PASSWORDS = array('password*', 'auth_*');
    const FIELDS_NAME = ['name', 'title', 'username'];
    const FIELDS_OVERVIEW = ['overview', 'description', 'summary'];
    const FIELDS_STATUS = ['category_id', 'type', 'status', 'is_top', 'is_hot', 'is_active'];
    const FIELDS_HISTORY = ['created_user', 'created_date', 'modified_user', 'modified_date'];

    const STARS = [
        1 => '★',
        2 => '★★',
        3 => '★★★',
        4 => '★★★★',
        5 => '★★★★★',
    ];

    const CURRENCY_SYMBOL = array(
        'AED' => '&#1583;.&#1573;', // ?
        'AFN' => '&#65;&#102;',
        'ALL' => '&#76;&#101;&#107;',
        'AMD' => '',
        'ANG' => '&#402;',
        'AOA' => '&#75;&#122;', // ?
        'ARS' => '&#36;',
        'AUD' => '&#36;',
        'AWG' => '&#402;',
        'AZN' => '&#1084;&#1072;&#1085;',
        'BAM' => '&#75;&#77;',
        'BBD' => '&#36;',
        'BDT' => '&#2547;', // ?
        'BGN' => '&#1083;&#1074;',
        'BHD' => '.&#1583;.&#1576;', // ?
        'BIF' => '&#70;&#66;&#117;', // ?
        'BMD' => '&#36;',
        'BND' => '&#36;',
        'BOB' => '&#36;&#98;',
        'BRL' => '&#82;&#36;',
        'BSD' => '&#36;',
        'BTN' => '&#78;&#117;&#46;', // ?
        'BWP' => '&#80;',
        'BYR' => '&#112;&#46;',
        'BZD' => '&#66;&#90;&#36;',
        'CAD' => '&#36;',
        'CDF' => '&#70;&#67;',
        'CHF' => '&#67;&#72;&#70;',
        'CLF' => '', // ?
        'CLP' => '&#36;',
        'CNY' => '&#165;',
        'COP' => '&#36;',
        'CRC' => '&#8353;',
        'CUP' => '&#8396;',
        'CVE' => '&#36;', // ?
        'CZK' => '&#75;&#269;',
        'DJF' => '&#70;&#100;&#106;', // ?
        'DKK' => '&#107;&#114;',
        'DOP' => '&#82;&#68;&#36;',
        'DZD' => '&#1583;&#1580;', // ?
        'EGP' => '&#163;',
        'ETB' => '&#66;&#114;',
        'EUR' => '&#8364;',
        'FJD' => '&#36;',
        'FKP' => '&#163;',
        'GBP' => '&#163;',
        'GEL' => '&#4314;', // ?
        'GHS' => '&#162;',
        'GIP' => '&#163;',
        'GMD' => '&#68;', // ?
        'GNF' => '&#70;&#71;', // ?
        'GTQ' => '&#81;',
        'GYD' => '&#36;',
        'HKD' => '&#36;',
        'HNL' => '&#76;',
        'HRK' => '&#107;&#110;',
        'HTG' => '&#71;', // ?
        'HUF' => '&#70;&#116;',
        'IDR' => '&#82;&#112;',
        'ILS' => '&#8362;',
        'INR' => '&#8377;',
        'IQD' => '&#1593;.&#1583;', // ?
        'IRR' => '&#65020;',
        'ISK' => '&#107;&#114;',
        'JEP' => '&#163;',
        'JMD' => '&#74;&#36;',
        'JOD' => '&#74;&#68;', // ?
        'JPY' => '&#165;',
        'KES' => '&#75;&#83;&#104;', // ?
        'KGS' => '&#1083;&#1074;',
        'KHR' => '&#6107;',
        'KMF' => '&#67;&#70;', // ?
        'KPW' => '&#8361;',
        'KRW' => '&#8361;',
        'KWD' => '&#1583;.&#1603;', // ?
        'KYD' => '&#36;',
        'KZT' => '&#1083;&#1074;',
        'LAK' => '&#8365;',
        'LBP' => '&#163;',
        'LKR' => '&#8360;',
        'LRD' => '&#36;',
        'LSL' => '&#76;', // ?
        'LTL' => '&#76;&#116;',
        'LVL' => '&#76;&#115;',
        'LYD' => '&#1604;.&#1583;', // ?
        'MAD' => '&#1583;.&#1605;.', //?
        'MDL' => '&#76;',
        'MGA' => '&#65;&#114;', // ?
        'MKD' => '&#1076;&#1077;&#1085;',
        'MMK' => '&#75;',
        'MNT' => '&#8366;',
        'MOP' => '&#77;&#79;&#80;&#36;', // ?
        'MRO' => '&#85;&#77;', // ?
        'MUR' => '&#8360;', // ?
        'MVR' => '.&#1923;', // ?
        'MWK' => '&#77;&#75;',
        'MXN' => '&#36;',
        'MYR' => '&#82;&#77;',
        'MZN' => '&#77;&#84;',
        'NAD' => '&#36;',
        'NGN' => '&#8358;',
        'NIO' => '&#67;&#36;',
        'NOK' => '&#107;&#114;',
        'NPR' => '&#8360;',
        'NZD' => '&#36;',
        'OMR' => '&#65020;',
        'PAB' => '&#66;&#47;&#46;',
        'PEN' => '&#83;&#47;&#46;',
        'PGK' => '&#75;', // ?
        'PHP' => '&#8369;',
        'PKR' => '&#8360;',
        'PLN' => '&#122;&#322;',
        'PYG' => '&#71;&#115;',
        'QAR' => '&#65020;',
        'RON' => '&#108;&#101;&#105;',
        'RSD' => '&#1044;&#1080;&#1085;&#46;',
        'RUB' => '&#1088;&#1091;&#1073;',
        'RWF' => '&#1585;.&#1587;',
        'SAR' => '&#65020;',
        'SBD' => '&#36;',
        'SCR' => '&#8360;',
        'SDG' => '&#163;', // ?
        'SEK' => '&#107;&#114;',
        'SGD' => '&#36;',
        'SHP' => '&#163;',
        'SLL' => '&#76;&#101;', // ?
        'SOS' => '&#83;',
        'SRD' => '&#36;',
        'STD' => '&#68;&#98;', // ?
        'SVC' => '&#36;',
        'SYP' => '&#163;',
        'SZL' => '&#76;', // ?
        'THB' => '&#3647;',
        'TJS' => '&#84;&#74;&#83;', // ? TJS (guess)
        'TMT' => '&#109;',
        'TND' => '&#1583;.&#1578;',
        'TOP' => '&#84;&#36;',
        'TRY' => '&#8356;', // New Turkey Lira (old symbol used)
        'TTD' => '&#36;',
        'TWD' => '&#78;&#84;&#36;',
        'TZS' => '',
        'UAH' => '&#8372;',
        'UGX' => '&#85;&#83;&#104;',
        'USD' => '&#36;',
        'UYU' => '&#36;&#85;',
        'UZS' => '&#1083;&#1074;',
        'VEF' => '&#66;&#115;',
        'VND' => '&#8363;',
        'VUV' => '&#86;&#84;',
        'WST' => '&#87;&#83;&#36;',
        'XAF' => '&#70;&#67;&#70;&#65;',
        'XCD' => '&#36;',
        'XDR' => '',
        'XOF' => '',
        'XPF' => '&#70;',
        'YER' => '&#65020;',
        'ZAR' => '&#82;',
        'ZMK' => '&#90;&#75;', // ?
        'ZWL' => '&#90;&#36;',
    );
}