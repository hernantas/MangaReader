<?php if (!defined("BASEPATH")) exit("NO DIRECT SCRIPT ACCESS ALLOWED");
	
class scan extends SF_controller
{
	private $path = "D:/manga";
	
	function home()
	{
		$folder_list = scandir($this->path);
		$manga = $this->db->query("select * from manga_name");
		$manga_exist = array();
		while ($dat = $manga->get_next()) {
			$manga_exist[$dat['name']] = $dat;
		}
		for ($i=0,$counter=0;$i<count($folder_list);$i++) {
			if ($folder_list[$i] != '.' && $folder_list[$i] != '..' && $folder_list[$i] != 'desktop.ini')
			{
				$folder_list[$counter] = $folder_list[$i];
				$counter++;
			}
			$data['i'] = $i;
			$data['counter'] = $counter;
		}
		for ($i=$data['counter'];$i<=$data['i'];$i++)
		{
			array_pop($folder_list);
		}
		
		$data['folder'] = $folder_list;
		$data['manga'] = $manga_exist;
		$data['view'] = "scan";
		$this->loader->view('home', $data);
	}
	
	function scan_algorithm()
	{
		$manga = htmlspecialchars_decode($_GET['manga']);
		$stTime = time();
		
		$dManga = array();
		$newChapter = false;
		$startTime = microtime(true);
	
		$chDirs = scandir($this->config->get('path','manga') . "/" . $manga);
		
		// Skipp if empty (why 2? because we skipped '.' and '..')
		if (count($chDirs) > 2) {
			$query = $this->db->query("select * from manga_name where name=\"" . $manga . "\" limit 0,1");
			if ($query->length == 0) {
				$db->query("insert into manga_name values('', \"" . $manga . "\", " . $stTime . ", " . $stTime . ", 0, 0, 1)"); 
				$query = $this->db->query("select * from manga_name where name=\"" . $manga . "\" limit 0,1");
			}
			$dManga = $query->get_next();
			
			$newCh = "";
			$sValCh = "";
			foreach($chDirs as $chDir) {
				if (is_dir($this->config->get('path','manga') . "/" . $manga . "/" . $chDir) && $chDir != '.' && $chDir != "..") {
					$query = $this->db->query("select * from manga_chapter where id_manga=\"" . $dManga['id'] . "\" and (name=\"" .  $chDir . "\") limit 0,1");	
					if ($query->length == 0) {
						if ($newCh == "")
							$newCh = "('',\"" . $chDir . "\"," . $dManga['id'] . "," . $stTime . ",'1')";
						else
							$newCh .= ",('',\"" . $chDir . "\"," . $dManga['id'] . "," . $stTime . ",'1')";
						$newChapter = true;
					} else {
						$dChapter = $query->get_next();
						if ($sValCh == "")
							$sValCh = "id=" . $dChapter['id'];
						else
							$sValCh .= " or id=" . $dChapter['id'];	
					}
				}
			}
			if ($sValCh != "") $this->db->query("update manga_chapter set valid='1' where " . $sValCh);
			
			if ($newChapter) {
				$this->db->query("update manga_name set last_update='" . $stTime . "', valid='1' where id=" . $dManga['id']);
				$this->db->query("insert into manga_chapter values" . $newCh);
			} else {
				$this->db->query("update manga_name set valid='1' where id=" . $dManga['id']);
			}
			
			$query = $this->db->query("select manga_chapter.* from manga_chapter where manga_chapter.id_manga='" . $dManga['id'] . "'");
			$picIns = "";
			while ($dat = $query->get_next()) {
				// echo $dat['name'] . ": " . $dat['date_add'] . "-" . $stTime . "<br />";
				if ($dat['date_add']==$stTime) {
					$pcScs = scandir($this->config->get('path','manga') . "/" . $dManga['name'] . "/" . $dat['name']);
					natsort($pcScs);
					
					//if (count($pcScs) == $dat['c_pc']) {
						//$db->Query("delete from manga_pict where id_chapter='".$dat['id']."'");	
						$page= 1;
						foreach($pcScs as $pcSc) {
							if ($pcSc != '.' && $pcSc != '..') {
								if ($picIns == "")
									$picIns = "('',\"" . $pcSc . "\",'" . $dManga['id'] . "','" . $dat['id'] . "','".$page."')";
								else
									$picIns .= ",('',\"" . $pcSc . "\",'" . $dManga['id'] . "','" . $dat['id'] . "','".$page."')";
								$page++;
							}
						}
					//}
				}
			}
			if ($picIns != "") $this->db->query("insert into manga_pict values".$picIns);
		}
		$endTime = microtime(true);
		
		echo number_format($endTime - $startTime, 2);
	}
	function confirmed()
	{
		$manga = htmlspecialchars_decode($_GET['manga']);
		$this->db->query("update manga_name set valid='1' where name='".$manga."'");
	}
	function end()
	{
		$this->log->write("Delete removed manga, chapter, picture and history from database");
		$this->db->query("delete from manga_name where valid='0'");
		$this->db->query("delete from manga_chapter where valid='0'");
		
		$this->db->query("delete from history where history.id_manga not in (select id from manga_name) or history.id_chapter not in (select id from manga_chapter)");
		$this->db->query("delete from manga_pict where manga_pict.id_manga not in (select id from manga_name) or manga_pict.id_chapter not in (select id from manga_chapter)");
		$this->log->write("Finish Scanning Manga");
	}
	function index()
	{
		if (!isset($this->param[1]))
		{
			$this->home();	
		}
		elseif ($this->param[1] == "init") 
		{
			//$this->db->query("update manga_name set valid='0'");
		}
		elseif ($this->param[1] == "ajax") 
		{
			$this->scan_algorithm();	
		}
		elseif ($this->param[1] == "confirmed") 
		{
			$this->confirmed();	
		}
		elseif ($this->param[1] == "end") 
		{
			$this->confirmed();	
		}
		else 
		{
			$data['view'] = '404';
			$this->loader->view('home',$data);
		}
		
	}
}
?>