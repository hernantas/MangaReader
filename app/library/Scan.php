<?php
    namespace Library;

    class Scan
    {
        private $mangaPath;
        private $maxScanPerBatch = 200;
        private $maxRemovedManga = 200;
        private $maxRemovedChapter = 100;

        private $model = null;
        private $scanWarning = array();

        private $addToScan = array();
        private $scanLength = 0;

        private $updateMark = array();
        private $addToUpdate = array();
        private $updateLength = 0;

        public function __construct()
        {
            page()->load->model('Scan', 'ScanModel');
            page()->load->library('Manga');
            $this->model =& page()->scanmodel;

            page()->config->setDefaultInfo('Manga', [
                'scanMaxPerRequest'=>200,
                'scanMaxMangaRemoved'=>200,
                'scanMaxChapterRemoved'=>100
            ]);
            $this->loadConfig();
        }

        private function loadConfig()
        {
            $cfg = page()->config->loadInfo('Manga');
            $this->mangaPath = $cfg['path'];
            $this->maxScanPerBatch = $cfg['scanMaxPerRequest'];
            $this->maxRemovedManga = $cfg['scanMaxMangaRemoved'];
            $this->maxRemovedChapter = $cfg['scanMaxChapterRemoved'];
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
            $mangas = new \FilesystemIterator($this->mangaPath,
                \FilesystemIterator::SKIP_DOTS);

            $scan = array();
            foreach ($mangas as $manga)
            {
                if ($manga->isDir())
                {
                    $scan[] = [$manga->getFilename()];
                }
            }

            $this->model->existsSet();
            $this->model->addScan($scan);
        }

        public function flushScan()
        {
            $result = $this->model->currentScan($this->maxScanPerBatch);

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
            if (isset($this->updateMark[$id]))
            {
                return false;
            }

            $this->updateMark[$id] = true;
            $this->updateLength++;
            $this->addToUpdate[] = $id;
            return true;
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

                $chapterDirs = new \FilesystemIterator($this->mangaPath . '/' . $manga,
                   \FilesystemIterator::SKIP_DOTS);

                foreach ($chapterDirs as $chapter)
                {
                    if ($chapter->isDir())
                    {
                        $this->addScan([$manga, $chapter->getFilename()]);
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
                    $dataManga = $this->model->getMangaF($fmanga);
                    $id = $dataManga->id;
                    $lastId = $id;
                    $lastFManga = $fmanga;

                    if (strcasecmp($dataManga->name, $manga) !== 0)
                    {
                        $this->scanWarning[] = "Found almost identical/duplicate manga:".
                            "<ul><li>$dataManga->name</li><li>$manga</li></ul>";
                    }
                }

                $chp = $this->model->getChapterF($id, $fchapter);
                if ($chp === false)
                {
                    $newChapter[] = [$id, $chapter, $fchapter, $manga];
                    $this->addUpdate($id);
                }
                else
                {
                    if (strcasecmp($chp->name, $chapter) === 0)
                    {
                        $scChapter[] = [$id, $chp->id, $manga, $chapter];
                    }
                    else
                    {
                        $this->scanWarning[] = "Found almost identical/duplicate chapters:".
                            "<ul><li>$chapter</li><li>$chp->name</li></ul>";
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
                    $this->scanWarning[] = "Found almost identical/duplicate chapters:".
                        "<ul><li>$new[1]</li><li>$chp->name</li></ul>";
                }
            }

            $removeImage = array();
            $newImage = array();
            foreach ($scChapter as $chapter)
            {
                $imgCount = $this->model->countImage($chapter[0], $chapter[1]);
                $imgs = new \FilesystemIterator($this->mangaPath.
                    '/'.$chapter[2].'/'.$chapter[3], \FilesystemIterator::SKIP_DOTS);
                $fileCount = iterator_count($imgs);

                if ($fileCount === 0)
                {
                    $this->scanWarning[] = "Found empty manga chapter(s):".
                        "<ul><li>$chapter[3]</li></ul>";
                }
                elseif ($fileCount != $imgCount)
                {
                    $removeImage[] = [$chapter[0], $chapter[1]];

                    $newImageData = array();
                    $page = 0;
                    foreach ($imgs as $img)
                    {
                        $newImageData[] = [$chapter[0], $chapter[1], $img->getFilename(), ++$page];
                    }

                    $newImage = array_merge($newImage, $newImageData);
                }
            }

            if (!empty($removeImage))
            {
                $this->model->removeImage($removeImage);
                $this->model->addImage($newImage);
            }
        }

        public function cleanUp()
        {
            return $this->model->removeDeleted($this->maxRemovedManga,
                $this->maxRemovedChapter);
        }
    }

?>
