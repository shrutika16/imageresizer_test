<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
	public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
    }
	
	public function index()
	{
		$this->load->view('welcome_message', array('error' => '' ));
	}
	
	public function submit(){
		$upload_status = [];
		$imageUploadType = $this->input->post('imageUploadType');
		if($imageUploadType == 'ImageText'){
			$iup = $this->input->post('iup');
			$image_name = (stristr($iup,'?',true))?stristr($iup,'?',true):$iup;
			$pos = strrpos($image_name,'/');
			$image_name = substr($image_name,$pos+1);
			$extension = stristr($image_name,'.');
			$image_full_path = $this->config->item('local_server_images').$image_name;
			$content = file_get_contents($iup);
			file_put_contents($image_full_path, $content);
			$size = getimagesize($image_full_path);
			$image_width = $size[0];
			$image_height = $size[1];
			list($img, $ext) = explode('/',$size['mime']);
			$extension = $image_extension = $ext;
		}else{
			$config['upload_path'] = $this->config->item('local_server_images');
			$config['allowed_types'] = 'gif|jpg|png';
			$this->load->library('upload', $config);
			
			if (!$this->upload->do_upload('userfile')) {
				$error = array('error' => $this->upload->display_errors());
				$this->load->view('welcome_message', array('error' => $error));
			} else {
				$data = array('image_metadata' => $this->upload->data());
				$image_name = $data['image_metadata']['file_name'];
				$image_width = $data['image_metadata']['image_width'];
				$image_height = $data['image_metadata']['image_height'];
				$image_extension = $data['image_metadata']['image_height'];
				$extension = strtolower($data['image_metadata']['image_type']);
			}
		}
		
		if(isset($image_name)){
			$save = $this->config->item('local_server_resized_images')."sml_" . $image_name ;
			$file = $this->config->item('local_server_images'). $image_name ;
			$width = $image_width;
			$height = $image_height;
			$modwidth = $this->input->post('rw');
			$modheight = $this->input->post('rh'); 
			$tn = imagecreatetruecolor($modwidth, $modheight) ;
			
			if($extension == "jpg" || $extension == "jpeg" ){
				$image = imagecreatefromjpeg($file);
			} else if($extension == "png"){
				$image = imagecreatefrompng($file);
			} else {
				$image = imagecreatefromgif($file);
			}
			
			imagecopyresampled($tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height) ;
			imagejpeg($tn, $save, 100) ; 
			
			$upload_status = $this->imgur($save);
		}
		//echo "<pre>"; print_r($upload_status);
		$this->load->view('welcome_message', array('error' => '' , 'upload_status'=> $upload_status));
	}
	
	public function imgur($imageUpload){
		$img_path = [];
		$filename = $imageUpload;
		$client_id = $this->config->item('imgur_client');
		$handle = fopen($filename, "r");
		$data = fread($handle, filesize($filename));
		$pvars   = array('image' => base64_encode($data));
		$timeout = 30;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);
		$out = curl_exec($curl);
		//echo "<pre>"; print_r($out);exit;
		curl_close ($curl);
		$pms = json_decode($out,true);
		$url=$pms['data']['link'];
		if($url!=""){
			$img_path = ['upload'=>'success', 'path'=> $url];
		}else{
			$img_path = ['upload'=>'fail', 'error'=> $pms['data']['error']];
		} 
		return $img_path;
	}
}
