<?php
    namespace Page;

    class Manga
    {
        private $pageLimit = 10;

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
            $order = array_reverse($order);

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

            $res = $this->manga->getChapters($manga->id);
            $chapters = array();
            while ($row = $res->row())
            {
                $fixName = $this->mangalib->nameFix($row->name, $manga->name);
                $order[] = $fixName;
                $chapters[$this->mangalib->toFriendlyName($fixName)] =
                    $row;
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
            $curFChapter = $order[$curI];

            // Get start page
            $page = 0;
            if (($pair = $this->uri->pair('page')) !== false)
            {
                $page = $pair-1;
            }

            $chapter = $this->manga->getChapterF($order[$curI]);
            $prevChapter = $chapter;
            $nextChapter = $chapter;

            // Generate Prev Link
            $pI = $curI;
            $pImageCount = $page;
            $prevLink = "manga/$fmanga";

            if ($pImageCount >= $this->pageLimit)
            {
                $prevLink = "mangas/$fmanga/chapter/$chapter->friendly_name";
                if ($pImageCount > 0)
                {
                    $prevLink .= "/pages/".(($pImageCount-$this->pageLimit)+1);
                }
            }
            else
            {
                while ($pI > 0 && $pImageCount < $this->pageLimit)
                {
                    $pI--;
                    $prevChapter = $this->manga->getChapterF(
                        $this->mangalib->toFriendlyName($order[$pI]));
                    $maxImage = $this->manga->getImageCount($prevChapter->id_manga,
                        $prevChapter->id);

                    $need = $this->pageLimit - $pImageCount;

                    if ($maxImage >= $need)
                    {
                        $prevLink = "manga/$fmanga/chapter/$prevChapter->friendly_name";
                        $prevLink .= "/page/".(($maxImage-$need)+1);
                    }

                    $pImageCount += $maxImage;
                }
            }

            $images = array();
            $imageCount = 0;
            $nextLink = "manga/$fmanga";
            while ($imageCount < $this->pageLimit && $nextChapter!==false)
            {
                $curPage = $imageCount==0 ? $page : 0;
                $result = $this->manga->getImages($nextChapter->id_manga,
                    $nextChapter->id, $curPage, $this->pageLimit-$imageCount);
                $maxImage = $this->manga->getImageCount($nextChapter->id_manga,
                    $nextChapter->id);

                while ($row = $result->row())
                {
                    $row->chapter = $nextChapter->name;
                    $row->fchapter = $nextChapter->friendly_name;
                    $images[] = $row;
                }

                $imageCount += $result->count();
                $nextLink = "manga/$fmanga/chapter/$nextChapter->friendly_name";
                if ($curPage+$result->count() < $maxImage)
                {
                    $nextLink .= "/page/".($curPage+$result->count()+1);
                }
                else
                {
                    $curI++;
                    if ($curI < $count)
                    {
                        // There is still chapters
                        $nextChapter = $this->manga->getChapterF(
                            $this->mangalib->toFriendlyName($order[$curI]));
                        $nextLink = "manga/$fmanga/chapter/$nextChapter->friendly_name";
                    }
                    else
                    {
                        // No more chapters
                        $nextLink = "manga/$fmanga";
                        break;
                    }
                }
            }

            $this->load->storeView('Read', [
                'manga'=>$manga,
                'chapters'=>$chapters,
                'chapterOrder'=>$order,
                'chapterCurrent'=>$curFChapter,
                'path'=>$cfg['path'],
                'images'=>$images,
                'prevLink'=>$prevLink,
                'nextLink'=>$nextLink,
                'count'=>$imageCount
            ]);

            $this->load->layout('Fresh', [
                'simpleMode'=>true,
                'readMode'=>true,
                'additionalJs'=>['read'],
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
