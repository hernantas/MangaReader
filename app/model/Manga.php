<?php
    namespace Model;

    class Manga
    {
        public function getList($page=0)
        {
            return $this->db->table('manga')
                ->join('manga_chapter','manga.id', 'manga_chapter.id_manga')
                ->order('manga.friendly_name')->limit($page, 30)
                ->group('manga.id')->get("manga.*, count(manga.id) as cnt");
        }

        public function addReadCount($id)
        {
            $this->db->table('manga')->where('id', $id)
                ->update('`views`=`views`+1');
            $current = $this->db->table('manga')->where('id', $id)
                ->get()->first();

            $above = $this->db->table('manga')->order('rankings', false)
                ->limit(0,1)->where('rankings','<', $current->rankings)->get();

            if ($above->isEmpty())
            {
                $this->db->table('manga')->where('id', $id)
                    ->update(['rankings'=>'1']);
                $this->db->table('manga')->where('id', '!=', $id)
                    ->where('rankings', '>=', 1)
                    ->update('`rankings`=`rankings`+1');
            }
            else
            {
                // Find placement
                $this->db->table('manga')->where('id', $id)
                    ->update(['rankings'=>$above->first()->rankings+1]);
                $this->db->table('manga')->where('id', '!=', $id)
                    ->where('rankings', '>=', $above->first()->rankings+1)
                    ->update('`rankings`=`rankings`+1');
            }
        }
    }

?>
