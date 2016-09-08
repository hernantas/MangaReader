<?php
    namespace Hook;

    class Navigation
    {
        public function view($data)
        {
            $nav = array();

            if (page()->auth->isLoggedIn())
            {
                $nav[] = [[
                    'url'=>'user/profile',
                    'img'=>'catuser48.png',
                    'text'=>'My Profiles'
                ]];
            }
            else
            {
                $nav[] = [[
                    'url'=>'user/signin',
                    'img'=>'user48.png',
                    'text'=>'Sign In / Sign Up'
                ]];
            }

            $nav[] = [
                [
                    'url'=>'',
                    'img'=>'home48.png',
                    'text'=>'Home'
                ],
                [
                    'url'=>'manga/directory',
                    'img'=>'directory48.png',
                    'text'=>'Manga Directory'
                ],
                [
                    'url'=>'manga/hot',
                    'img'=>'hot48.png',
                    'text'=>'Popular Manga'
                ],
                [
                    'url'=>'manga/latest',
                    'img'=>'news48.png',
                    'text'=>'Latest Updated'
                ]
            ];

            if (page()->auth->isLoggedIn())
            {
                if (page()->auth->getUserOption('privilege') == 'admin')
                {
                    $nav[] = [
                        [
                            'url'=>'admin/scan',
                            'img'=>'radar48.png',
                            'text'=>'Scan Directory'
                        ],
                        [
                            'url'=>'admin/uninstall',
                            'img'=>'destruct48.png',
                            'text'=>'Uninstall'
                        ]
                    ];
                }

                $nav[] = [
                    [
                        'url'=>'user/history',
                        'img'=>'paper48.png',
                        'text'=>'My History'
                    ],
                    [
                        'url'=>'user/signout',
                        'img'=>'leave48.png',
                        'text'=>'Sign Out'
                    ]
                ];
            }

            return [ 'nav' => $nav ];
        }
    }

?>
