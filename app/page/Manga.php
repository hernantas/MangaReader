<?php
    namespace Page;

    class Manga
    {
        public function directory()
        {
            $this->load->model('Manga');
            $this->load->library('Date');
            $this->load->library('Image');
            $this->load->helper('Paging');

            $cfg = $this->config->loadInfo('Manga');
            $count = $this->manga->getCount();
            $maxPage = $count / 36;

            $curPage = 1;
            if (($page = $this->uri->pair('page')) !== false)
            {
                $curPage = $page;
            }

            $result = $this->manga->getList($curPage-1);

            $this->load->storeView('MangaDirectory', [
                'mangalist'=>$result,
                'mangapath'=>$cfg['path'],
                'page'=>paging($curPage, $maxPage),
                'curpage'=>$curPage
            ]);

            $this->load->layout('Fresh', [
                'title'=>'Directory'
            ]);
        }

        public function hot()
        {

        }

        public function latest()
        {

        }

        private function chapter()
        {
            $this->load->model('Manga');
            $this->load->library('Manga', 'MangaLib');
            $this->load->library('Date');

            $manga = $this->manga->getMangaF($this->uri->segment(2));
            $result = $this->manga->getChapters($manga->id);

            $order = array();
            $chapters = array();
            while ($row = $result->row())
            {
                $name = $this->mangalib->nameFix($row->name, $manga->name);
                $order[] = $name;
                $chapters[$name] = $row;
            }

            natsort($order);

            $this->load->storeView('MangaChapter', [
                'manga'=>$manga,
                'chapters'=>$chapters,
                'order'=>$order
            ]);

            $this->load->layout('Fresh', [
                'title'=>$manga->name
            ]);
        }

        private function read($fchapter)
        {
            $this->load->model('Manga');
            $this->load->library('Manga', 'MangaLib');
            $this->load->library('Image');

            $cfg = $this->config->loadInfo('Manga');
            $fmanga = $this->uri->segment(2);
            $manga = $this->manga->getMangaF($fmanga);

            $result = $this->manga->getChapters($manga->id);
            while ($row = $result->row())
            {
                $order[] = $this->mangalib->nameFix($row->name, $manga->name);
            }

            natsort($order);
            $order = array_values($order);
            $count = count($order);

            // Get current index
            $curI = -1;
            for ($i = 0; $i < $count; $i++)
            {
                $order[$i] = $this->mangalib->toFriendlyName($order[$i]);
                if (strcmp($order[$i], $fchapter) === 0)
                {
                    $curI = $i;
                }
            }

            // Get start page
            $page = 0;
            if (($pair = $this->uri->pair('page')) !== false)
            {
                $page = $pair;
            }

            $chapter = $this->manga->getChapterF($order[$curI]);
            $prevChapter = $chapter;
            $nextChapter = $chapter;

            // Generate Prev Link
            $prevImage = 0;
            $pI = $curI;
            $prevLink = "manga/$fmanga";
            if ($page > 10)
            {
                $prevLink = "manga/$fmanga/chapter/$prevChapter->friendly_name";
                $prevLink .= "/page/".($page-10);
            }
            elseif ($page == 10)
            {
                $prevLink = "manga/$fmanga/chapter/$prevChapter->friendly_name";
            }
            while ($pI > 0 && $prevImage < 10)
            {
                $prevChapter = $this->manga->getChapterF(
                    $this->mangalib->toFriendlyName($order[$pI-1]));
                $maxImage = $this->manga->getImageCount($prevChapter->id_manga,
                    $prevChapter->id);
                $prevImage += $maxImage;
                $pI--;

                if ($pI >= 0)
                {
                    $prevLink = "manga/$fmanga/chapter/$prevChapter->friendly_name";
                    if ($maxImage-10 >= 1)
                    {
                        $prevLink .= "/page/".($maxImage-10);
                    }
                }
            }

            $images = array();
            $imageCount = 0;
            $nextLink = '';
            while ($imageCount < 10 && $nextChapter!==false)
            {
                $curPage = $imageCount===0 ? $page : 0;
                $result = $this->manga->getImages($nextChapter->id_manga,
                    $nextChapter->id, $curPage, 10-$imageCount);
                $maxImage = $this->manga->getImageCount($nextChapter->id_manga,
                    $nextChapter->id);

                while ($row = $result->row())
                {
                    $row->chapter = $nextChapter->name;
                    $images[] = $row;
                }

                $nextLink = "manga/$fmanga/chapter/$nextChapter->friendly_name";
                $nextChapter = $this->manga->getChapterF(
                    $this->mangalib->toFriendlyName($order[$curI+1]));
                if ($page+$result->count() < $maxImage)
                {
                    $nextLink .= "/page/".($page+$result->count()+1);
                }
                else
                {
                    $nextLink = "manga/$fmanga/chapter/$nextChapter->friendly_name";
                }

                $imageCount += $result->count();
                $curI++;
            }

            $this->load->storeView('Read', [
                'manga'=>$manga,
                'path'=>$cfg['path'],
                'images'=>$images,
                'prevLink'=>$prevLink,
                'nextLink'=>$nextLink
            ]);

            $this->load->layout('Fresh', [
                'simpleMode'=>true,
                'title'=>$this->mangalib->nameFix($chapter->name, $manga->name)
            ]);
        }

        public function route()
        {
            if (($chapter = $this->uri->pair('chapter')) !== false)
            {
                $this->read($chapter);
            }
            else
            {
                $this->chapter();
            }
        }
    }
?>
