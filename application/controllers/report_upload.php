<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Report_Upload extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this -> load -> helper(array('form', 'url'));
	}

	function index() {
		$this -> upload_interface();
	}

	public function upload_interface() {
		$data['content_view'] = "report_upload_v";
		$this -> base_params($data);
	}

	public function base_params($data) {
		$data['title'] = "Report Upload";
		$data['banner_text'] = "Report Upload";
		$data['quick_link'] = "report_upload";
		$data['link'] = "admin_management";
		$this -> load -> view("template_v", $data);
	}

	public function upload_file() {
	//load the helper
		$this->load->helper('form');
    $dates =date('Y');
		//Configure
		//set the path where the files uploaded will be copied. NOTE if using linux, set the folder to permission 777
		$config['upload_path'] = "../idsr/Bulletins/$dates/";
		
    // set the filter image types
		$config['allowed_types'] = 'pdf|doc|epub|docx';
		$config['max_size'] = '20000';
		//load the upload library
		$this->load->library('upload', $config);
    
    $this->upload->initialize($config);
    
    //$this->upload->set_allowed_types('*');
        
if (!is_dir('../idsr/Bulletins/'.$dates)) {
    mkdir('../idsr/Bulletins/' . $dates, 0777, TRUE);

}  
		$data['upload_data'] = '';
    
		//if not successful, set the error message
		if (!$this->upload->do_upload('userfile')) {
			//$data = array('msg' => $this->upload->display_errors());
			$upload_error=$this->upload->display_errors();
			$this->session->set_flashdata('error_alert',1);
			$this->session->set_flashdata('upload_errors',$upload_error);
			
			redirect('Report_Upload/upload_interface');
			

		} else { //else, set the success message
			$data = array('msg' => "Upload success!");
             $data['upload_data'] = $this->upload->data();
			 $this->session->set_flashdata('error_alert',2);
			 redirect('Report_Upload/upload_interface');
			 //$data['content_view'] = "upload_success";
			//$this -> base_params($data);

		}
	
	}
	public function drag_drop(){
	$allowed = array('doc', 'epub', 'pdf','docx');
	$folder_year=$_POST['current_year'];
	//$folder_year=date('Y');
	/*if($_POST['current_year']){
    $folder_year=$_POST['current_year'];
	}*/
if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

	$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error"}';
		exit;
	}
	
	if (!is_dir('../idsr/Bulletins/'.$folder_year)) {
    mkdir('../idsr/Bulletins/' . $folder_year, 0777, TRUE);

} 

	if(move_uploaded_file($_FILES['upl']['tmp_name'], '../idsr/Bulletins/'.$folder_year.'/'.$_FILES['upl']['name'])){
		echo '{"status":"success"}';
		exit;
	}
}

echo '{"status":"error"}';
exit;
	}

}
?>