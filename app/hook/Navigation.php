<?php
    namespace Hook;

    class Navigation
    {
        public function data($data)
        {
            return [
                'nav' => [
                    [
                        [
                            'url'=>'user/signin',
                            'img'=>'user48.png',
                            'text'=>'Sign In / Sign Up'
                        ]
                    ],
                    [
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
                            'url'=>'manga/directory/hot',
                            'img'=>'hot48.png',
                            'text'=>'Popular Manga'
                        ],
                        [
                            'url'=>'manga/directory/latest',
                            'img'=>'news48.png',
                            'text'=>'Latest Updated'
                        ]
                    ]
                ]
            ];
        }
    }

?>
