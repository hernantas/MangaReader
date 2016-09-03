<?php
    namespace Page;

    class Install
    {
        public function index()
        {
            $this->setup->installOnly();

            if ($this->input->hasPost())
            {
                if ($this->input->hasPost('agree'))
                {
                    $this->setup->finish();
                    $this->router->redirect();
                }
                else
                {
                    $this->message->error("If you want to use the website, you must agree
                        to the license.");
                }
            }

            $this->load->storeView('InstallWelcome');

            $this->load->layout('Fresh', [
                'simpleMode'=>true,
                'title'=>'Welcome'
            ]);
        }

        public function database()
        {
            $this->setup->installOnly();

            if ($this->config->has('DB'))
            {
                $this->setup->finish();
                $this->router->redirect();
            }

            if ($this->input->hasPost())
            {
                $this->db->selectDriver('mysqli');
                $res = $this->db->connect($this->input->post('host'),
                    $this->input->post('username'),
                    $this->input->post('password'));

                if ($res !== true)
                {
                    $this->message->error('Your database host configuration is not valid.');
                }
                else
                {
                    $this->db->database($this->input->post('name'), true);
                    $this->config->saveInfo('DB', [
                        'driver'=>'mysqli',
                        'host'=>$this->input->post('host'),
                        'username'=>$this->input->post('username'),
                        'password'=>$this->input->post('password'),
                        'database'=>$this->input->post('name')
                    ]);
                    $this->createTable();

                    $this->setup->finish();
                    $this->router->redirect();
                    // $this->db->schema->create();
                }
            }

            $this->load->storeView('InstallDatabase');

            $this->load->layout('Fresh', [
                'simpleMode'=>true,
                'title'=>'Database Setup'
            ]);
        }

        private function createTable()
        {
            $this->db->schema->create('manga', function ($table)
            {
                $table->increment('id');
                $table->string('name')->unique();
                $table->string('friendly_name')->unique();
                $table->int('added_at');
                $table->int('update_at');
                $table->bool('completed');
            });
            $this->db->schema->create('manga_chapter', function ($table)
            {
                $table->increment('id');
                $table->int('id_manga')->index();
                $table->string('name')->index();
                $table->string('friendly_name')->index();
                $table->int('added_at');
            });
            $this->db->schema->create('manga_image', function ($table)
            {
                $table->increment('id');
                $table->int('id_manga')->index();
                $table->int('id_chapter')->index();
                $table->string('name');
                $table->int('page');
            });
            $this->db->schema->create('manga_scan', function ($table)
            {
                $table->increment('id');
                $table->string('manga');
                $table->string('chapter')->nullable();
                $table->string('image')->nullable();
            });
            $this->db->schema->create('user_history', function ($table)
            {
                $table->increment('id');
                $table->int('id_manga')->index();
                $table->int('id_chapter')->index();
                $table->int('page');
            });
            $this->auth->install();
        }

        public function path()
        {
            if ($this->input->hasPost())
            {
                $path = $this->input->post('path');
                if ($path !== '')
                {
                    if (file_exists($path) && is_readable($path))
                    {
                        $this->config->saveInfo('Manga', [
                            'path'=>$path
                        ]);
                        $this->setup->finish();
                        $this->router->redirect();
                    }
                    else
                    {
                        $this->message->error("Can't access directory '$path'");
                    }
                }
                else
                {
                    $this->message->error('Path must not empty.');
                }
            }

            $this->load->storeView('InstallPath');

            $this->load->layout('Fresh', [
                'simpleMode'=>true,
                'title'=>'Database Setup'
            ]);
        }
    }
?>
