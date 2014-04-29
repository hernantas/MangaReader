<?php if (!defined("BASEPATH")) exit("NO DIRECT SCRIPT ACCESS ALLOWED");

	class home extends SF_controller
	{
		function index()
		{
			$page = $this->param_value("page", 1);
			if ($page < 1) $page = 1;
			$this->renamer = &load_class("renamer","library",'');
			
			$start = time();//strtotime($strtotime);
			$contQ = 0;
			for ($i=$page;$contQ==0;$i++) {
				$beforeStart = strtotime("-".$i." week", $start);
				$beforeEnd = strtotime("+7 days", $beforeStart);
				
				$sql = "select manga_chapter.*, manga_name.name as name_manga, 
					manga_name.id as id_manga, manga_name.add_time as add_manga, 
					manga_name.last_update as update_manga from manga_name, 
					manga_chapter where manga_name.id=manga_chapter.id_manga and 
					manga_chapter.date_add > ".($beforeStart)." and 
					manga_chapter.date_add < ".($beforeEnd)." group by 
					manga_chapter.id order by manga_chapter.id desc";
				$data["newsfeed"] = $this->db->query($sql);
				
				$contQ = $data["newsfeed"]->length;
			}
			
			$hAdd = NULL;
			$iAdd = NULL;
			while ($dat = $data["newsfeed"]->get_next()) {
				if ($hAdd == NULL) 
					$hAdd = "history.id_chapter='".$dat['id']."'";
				else
					$hAdd .= " or history.id_chapter='".$dat['id']."'";	
					
				if ($iAdd == NULL)
					$iAdd = "id_chapter='".$dat['id']."'";
				else
					$iAdd .= " or id_chapter='".$dat['id']."'";
			}
			
			$pic = $this->db->query("select * from manga_pict where (".$iAdd.") 
				group by id_chapter");
			while ($dat = $pic->get_next())
			{
				$data["picture"][$dat['id_chapter']] = $dat['id'];
			}
						
			$data["newsfeed"]->restart();
			$data["history_search"] = $hAdd;
			$data['view'] = "newsfeed";
			
			$ajax = $this->seek_param('ajax');
			$data['ajax'] = $ajax;
			
			if ($ajax === FALSE)
				$this->loader->view("home", $data);
			else
				$this->loader->view($data['view'], $data);
		}
	} 
?>