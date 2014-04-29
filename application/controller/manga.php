<?php if (!defined("BASEPATH")) exit("NO DIRECT SCRIPT ACCESS ALLOWED");
	
class manga extends SF_Controller
{
	function directory()
	{
		$limit_page = 12;
		
		$data['cur_page'] = $this->param_value('page',1);
		$data['last_page'] = ceil($this->db->query('select * from manga_name')->length/$limit_page);
		
		if ($data['cur_page'] < 1) $data['cur_page'] = 1;
		else if ($data['cur_page'] > $data['last_page']) $data['cur_page'] = $data['last_page'];
		
		$page = ($data['cur_page']-1)*$limit_page;
		$data['next_page'] = $data['cur_page']+1;
		$data['prev_page'] = $data['cur_page']-1;
		
		$data['first_page_number'] = $data['cur_page']-$limit_page/2;
		if ($data['cur_page'] <= $limit_page/2) $data['first_page_number'] = 1;
		$data['last_page_number'] = $data['cur_page'] + $limit_page/2;
		if ($data['last_page'] < $data['last_page_number']) $data['last_page_number'] = $data['last_page']; 
		
		$manga = $this->db->query("select manga_name.*, count(manga_chapter.id) 
			as chapter_count from manga_name, manga_chapter where 
			manga_name.id=manga_chapter.id_manga group by manga_name.id order 
			by name asc limit $page,$limit_page");
			
		$pAdd = "(0";
		while ($dat = $manga->get_next())
		{
			//if (!isset($pAdd)) $pAdd = "id_manga='".$dat['id']."'";
			//else $pAdd .= " or id_manga='".$dat['id']."'";
			$pAdd .= ",".$dat['id'];
			$data['manga'][$dat['name']] = $dat;
			$data['sorted_manga'][] = $dat['name'];
		}
		$pAdd .= ")";
		
		$pict_result = $this->db->query("select * from manga_pict where id_manga in ".$pAdd." group by id_manga");
		while ($dat = $pict_result->get_next())
		{
			$data['picture'][$dat['id_manga']] = $dat['id']+2;
		}
		
		
		natsort($data['sorted_manga']);
		$data['pAdd'] = $pAdd;
		$data['view'] = "manga";
		$this->loader->view("home",$data);
	}

	function chapter_list($id, $chapter)
	{
		$data['id_manga'] = $id;
		$query = $this->db->query("select manga_chapter.*, manga_name.name as manganame 
									from manga_chapter, manga_name where manga_chapter.id_manga='".$id."' 
									and manga_name.id=manga_chapter.id_manga
									group by manga_chapter.id 
									order by manga_chapter.name desc");
		
		if ($query->length == 0)
		{
			$data['view'] = '404';
			$this->loader->view('home',$data);
			return;
		} 
		
		$sort = array();
		$chapter_title = '';
		while ($dat = $query->get_next())
		{
			$data['manganame'] = $dat['manganame'];
			
			$sort[] = $dat['name'];
			$data['manga'][$dat['name']] = $dat;
			
			if ($chapter !== FALSE)
				if ($chapter == $dat['id'])
					$chapter_title = $dat['name'];
		}
		
		natsort($sort);
		$sort = array_reverse($sort);
		$data['sort'] = $sort;
		
			
		if ($chapter === FALSE) 
		{
			$data['title'] = $data['manganame'];
			$data['view'] = 'chapter';
			$this->loader->view('home',$data);
		}
		elseif ($chapter == 'readall' || $chapter == 'completed')
		{
			
		}
		else
		{
			$data['id_chapter'] = $chapter;
			$data['picture'] = $this->db->query("select * from manga_pict where id_manga='".$data['id_manga']."' and id_chapter='".$data['id_chapter']."'");
			$data['title'] = $chapter_title;
			$this->loader->view('read', $data);	
		}
	}

	function index()
	{
		if ($this->param[1] == '' || $this->param[1] == 'directory' || $this->param[1] == 'latest' || $this->param[1] == 'popular')
		{
			$this->seek_method();
		} else
		{
			$this->chapter_list($this->param[1], isset($this->param[2])?$this->param[2]:FALSE);
		}
	}
}
?>