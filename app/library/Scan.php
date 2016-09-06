<?php
    namespace Library;

    class Scan
    {
        private $mangaPath;
        private $model = null;

        private $scanWarning = array();

        private $addToScan = array();
        private $scanLength = 0;
        private $addToUpdate = array();
        private $updateLength = 0;

        public function __construct()
        {
            page()->load->model('Scan', 'ScanModel');
            page()->load->library('Manga');
            $this->model =& page()->scanmodel;

            $cfg = page()->config->loadInfo('Manga');

            $this->mangaPath = $cfg['path'];
        }

        public function path()
        {
            return $this->mangaPath;
        }

        public function getScanWarning()
        {
            return $this->scanWarning;
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
                        page()->manga->toFriendlyName($manga),
                        page()->manga->toFriendlyName(
                            page()->manga->nameFix($chapter, $manga))];
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

            $this->updateLength++;
            $this->addToUpdate[] = $id;
        }

        private function scanManga($mangas)
        {
            $newManga = array();
            $existsManga = array();

            foreach ($mangas as $manga)
            {
                $fmanga = page()->manga->toFriendlyName($manga);

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

                $chp = $this->model->getChapterF($id, $fchapter);
                if ($chp === false)
                {
                    $newChapter[] = [$id, $chapter, $fchapter, $manga];
                    $this->addUpdate($id);
                }
                else
                {
                    if (strcasecmp($chp->name, $chapter) == 0)
                    {
                        $scChapter[] = [$id, $chp->id, $manga, $chapter];
                    }
                    else
                    {
                        $this->scanWarning[] = "Found almost identical/duplicate manga chapter name:".
                            "<ul><li>$chapter</li><li>$chp->name</li></ul>" .
                            "Please remove one of them since having them both may cause issues.";
                    }
                }
            }

            if (!empty($newChapter))
            {
                $this->model->addChapter($newChapter);
            }

            if (!empty($scChapter))
            {
                $this->model->setExistsChapter($scChapter);
            }

            foreach ($newChapter as $new)
            {
                // Complete ID for the new chapter
                $chp = $this->model->getChapterF($new[0], $new[2]);
                if (strcasecmp($chp->name, $new[1]) == 0)
                {
                    $scChapter[] = [$new[0], $chp->id, $new[3], $new[1]];
                }
                else
                {
                    $this->scanWarning[] = "There is duplicate/almost similar".
                        " manga chapter name '$new[1]' and '$chp->name'." .
                        " Please remove one of them since having them both may cause issues.";
                }
            }

            $removeImage = array();
            $newImage = array();
            foreach ($scChapter as $chapter)
            {
                $imgCount = $this->model->countImage($chapter[0], $chapter[1]);
                $imgDirs = scandir($this->mangaPath . '/' . $chapter[2] . '/' . $chapter[3]);

                $count = 0;
                $newImageData = array();
                $page = 0;
                foreach ($imgDirs as $img)
                {
                    if ($img != '.' && $img != '..' &&
                        is_file($this->mangaPath . '/' . $chapter[2] . '/' . $chapter[3] . '/' . $img))
                    {
                        $newImageData[] = [$chapter[0], $chapter[1], $img, ++$page];
                    }
                }
                $count += $page;

                if ($count != $imgCount)
                {
                    $removeImage[] = [$chapter[0], $chapter[1]];
                    $newImage = array_merge($newImage, $newImageData);
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
