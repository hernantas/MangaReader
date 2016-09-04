<?php
    namespace Library;

    class Manga
    {
        private $mangaPath;
        private $model = null;

        private $addToScan = array();
        private $scanLength = 0;
        private $addToUpdate = array();
        private $updateLength = 0;

        public function __construct()
        {
            page()->load->model('Manga', 'MangaModel');
            $this->model =& page()->mangamodel;

            $cfg = page()->config->loadInfo('Manga');

            $this->mangaPath = $cfg['path'];
        }

        public function path()
        {
            return $this->mangaPath;
        }

        public function toFriendlyName($name)
        {
            $name = preg_replace('/[^a-z0-9 ]/i', '', $name);
            $name = preg_replace('/\s+/', ' ', $name);
            return strtolower(str_replace(' ', '_', $name));
        }

        public function isScanEmpty()
        {
            return $this->model->isScanEmpty();
        }

        public function startScan()
        {
            $mangas = scandir($this->mangaPath);

            $scan = array();
            foreach ($mangas as $manga)
            {
                if ($manga != '.' && $manga != '..' &&
                    is_dir($this->mangaPath . '/' . $manga))
                {
                    $scan[] = [$manga];
                }
            }

            $this->model->existsSet();
            $this->model->addScan($scan);
        }

        public function flushScan()
        {
            $result = $this->model->currentScan(200);

            $mangas = array();
            $chapters = array();

            $removeScan = array();

            while ($current = $result->row())
            {
                $removeScan[] = $current->id;
                $manga = $current->manga;
                $chapter = $current->chapter;
                $image = $current->image;

                if ($chapter === '' && $image === '')
                {
                    $mangas[] = $manga;
                }
                elseif ($image === '')
                {
                    $chapters[] = [$manga, $chapter,
                        $this->toFriendlyName($manga), $this->toFriendlyName($chapter)];
                }
            }

            $this->model->removeScan($removeScan);

            if (!empty($mangas))
            {
                $this->scanManga($mangas);
            }

            if (!empty($chapters))
            {
                $this->scanChapter($chapters);
            }

            if ($this->scanLength > 0)
            {
                $this->model->addScan($this->addToScan);
            }

            if ($this->updateLength > 0)
            {
                $this->model->updateMangaTime($this->addToUpdate);
            }
        }

        private function addScan($array)
        {
            $this->addToScan[] = $array;
            $this->scanLength++;
        }

        private function addUpdate($id)
        {
            foreach ($this->addToUpdate as $update)
            {
                if ($update === $id)
                {
                    return false;
                }
            }

            $this->addToUpdate[] = $id;
        }

        private function scanManga($mangas)
        {
            $newManga = array();
            $existsManga = array();

            foreach ($mangas as $manga)
            {
                $fmanga = $this->toFriendlyName($manga);

                if (!$this->model->hasMangaF($fmanga))
                {
                    // Insert new manga
                    $newManga[] = [$manga, $fmanga];
                }
                else
                {
                    $existsManga[] = $fmanga;
                }

                $chapterDirs = scandir($this->mangaPath . '/' . $manga);

                foreach ($chapterDirs as $chapter)
                {
                    if ($chapter != '.' && $chapter != '..' &&
                        is_dir($this->mangaPath . '/' . $manga . '/' . $chapter))
                    {
                        $this->addScan([$manga, $chapter]);
                    }
                }
            }

            if (!empty($newManga))
            {
                $this->model->addManga($newManga);
            }

            if (!empty($existsManga))
            {
                $this->model->setExistsManga($existsManga);
            }
        }

        private function scanChapter($chapters)
        {
            $lastId = -1;
            $lastFManga = '';
            $newChapter = array();
            $scChapter = array();
            foreach ($chapters as $data)
            {
                $manga = $data[0];
                $chapter = $data[1];
                $fmanga = $data[2];
                $fchapter = $data[3];
                $id = -1;

                // Cache previous chapter
                if ($lastFManga == $fmanga)
                {
                    $id = $lastId;
                }
                else
                {
                    $id = $this->model->getMangaF($fmanga)->id;
                    $lastId = $id;
                    $lastFManga = $fmanga;
                }

                if (!$this->model->hasChapterF($id, $fchapter))
                {
                    $newChapter[] = [$id, $chapter, $fchapter, $manga];
                    $this->addUpdate($id);
                }
                else
                {
                    $idChapter = $this->model->getChapterF($id, $fchapter)->id;
                    $scChapter[] = [$id, $idChapter, $manga, $chapter];
                }
            }

            if (!empty($newChapter))
            {
                $this->model->addChapter($newChapter);
            }

            foreach ($newChapter as $new)
            {
                // Complete ID for the new chapter
                $idChapter = $this->model->getChapterF($new[0], $new[2])->id;
                $scChapter[] = [$new[0], $idChapter, $new[3], $new[1]];
            }

            $removeImage = array();
            if (!empty($scChapter))
            {
                $this->model->setExistsChapter($scChapter);
            }

            $newImage = array();
            foreach ($scChapter as $chapter)
            {
                $imgCount = $this->model->countImage($id, $idChapter);
                $imgDirs = scandir($this->mangaPath . '/' . $chapter[2] . '/' . $chapter[3]);

                $imgs = array();
                $count = 0;
                foreach ($imgDirs as $img)
                {
                    if ($img != '.' && $img != '..' &&
                        is_file($this->mangaPath . '/' . $chapter[2] . '/' . $chapter[3] . '/' . $img))
                    {
                        $imgs[] = $img;
                        $count++;
                    }
                }

                if ($count != $imgCount)
                {
                    $removeImage[] = [$chapter[0], $chapter[1]];
                    $i = 1;
                    foreach ($imgs as $img)
                    {
                        $newImage[] = [$chapter[0], $chapter[1], $img, $i++];
                    }
                }
            }

            if (!empty($removeImage))
            {
                $this->model->removeImage($removeImage);
                $this->model->addImage($newImage);
            }
        }
    }

?>
