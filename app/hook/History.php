<?php
    namespace Hook;

    class History
    {
        public function view($data)
        {
            page()->load->model('Manga');

            $id = $data['manga']->id;
            $idUser = page()->auth->getUserId();
            return ['history'=>page()->manga->getMangaHistory($idUser, $id)];
        }
    }

?>
