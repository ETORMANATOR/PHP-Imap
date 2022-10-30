<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ .'/../../vendor/box/spout/src/Spout/Autoloader/autoload.php';
require_once __DIR__ .'/../../vendor/autoload.php';


use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use benhall14\phpImapReader\Email as Email;
use benhall14\phpImapReader\EmailAttachment as EmailAttachment;
use benhall14\phpImapReader\Reader as Reader;

class Emailsender extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->library('email');
		$this->load->library('imap');
		define('GmailImapServer','imap.gmail.com:993/imap/ssl');
		define('SmtpImapServer','mail.thinklogicmediagroup.com:995/pop3/ssl/novalidate-cert');
		define('GmailSmtpServer','ssl://smtp.googlemail.com');
		define('GmailSmtpServerPort',465);
		define('OpsSmtpServer','mail.thinklogicmediagroup.com');
		define('OpsSmtpServerPort',587);
		define('EmailSenderTest','test@ops.thinklogicmediagroup.com');

		

    }
	public function index()
	{	
	
		if($_SERVER['REQUEST_METHOD']=='POST')
			{
				$upload_status =  $this->uploadDoc();
				if($upload_status!=false)
				{
					$inputFileName = 'assets/uploads/imports/'.$upload_status;
					$reader = ReaderEntityFactory::createReaderFromFile($inputFileName);
					$reader->open($inputFileName) ;
					$storedata = array();
					foreach ($reader->getSheetIterator() as $sheet) {
						foreach ($sheet->getRowIterator() as $row){
							$cells= $row->getCells() ;
							$FnameD = $cells[0]->getValue() ;
							$LnameD = $cells[1]->getValue() ;
							$EmailD = $cells[2]->getValue() ;
							$CodeD = $cells[3]->getValue() ;
							$data = array($FnameD,$LnameD,$EmailD,$CodeD);
							//array_push($allinfor, $data);
							$storedata[] = $data;

						}
					}
					$allinfo['allinfo'] = $storedata;
					$reader->close();

					
					$this->session->set_flashdata('success','Successfully Data Imported');
					
					//redirect(base_url());
					$serviceSelected = $this->input->post("serviceselection");
					if($serviceSelected == "sendemail"){
						$this->load->view('sendnow',$allinfo);	
					}
					elseif($serviceSelected == "checkemail"){
						$this->load->view('checknow',$allinfo);	
					}
					
				}
				else
				{
					$this->session->set_flashdata('error','File is not uploaded');
					redirect(base_url());
				}
			}
		
			else
			{
					$this->load->view('excelimport');	
				}
	}
	
	function uploadDoc()
	{
		$resetDIR = __DIR__ .'/../../assets';
		shell_exec("rm -R " . $resetDIR);
			$uploadPath = 'assets/uploads/imports/';
			if(!is_dir($uploadPath))
			{
				mkdir($uploadPath,0777,TRUE); // FOR CREATING DIRECTORY IF ITS NOT EXIST
			}

			$config['upload_path']=$uploadPath;
			$config['allowed_types'] = 'csv|xlsx|xls';
			$config['max_size'] = 1000000;
			$this->load->library('upload',$config);
			$this->upload->initialize($config);
			if($this->upload->do_upload('upload_excel'))
			{
				$fileData = $this->upload->data();
				return $fileData['file_name'];
			}
			else
			{
				return false;
			}
		
	}
	public function Ops()
	{
		$emailReceiver = $this->input->post('emailReceiver', TRUE);
		$emailSubject = $this->input->post('emailSubject', TRUE);
		$emailHtmlMessage = $this->input->post('emailHtmlMessage', TRUE);
		$senderEmail = $this->input->post('senderEmail', TRUE);
		$senderPassword = strval($this->input->post('senderPassword'));

		$config['protocol']    = 'smtp';
		$config['smtp_host']    = OpsSmtpServer;
		$config['smtp_port']    = OpsSmtpServerPort;
		$config['smtp_timeout'] = '300';
		$config['smtp_user']    = $senderEmail;
		$config['smtp_pass']    = $senderPassword;
		$config['smtp_crypto'] = 'tls';
		$config['charset']    = 'utf-8';
		$config['newline']    = "\r\n";
		$config['wordwrap']    = TRUE;
		$config['_smtp_auth'] = TRUE;
		$config['mailtype'] = 'html'; // or html
		$config['validation'] = TRUE;
					
		$this->email->initialize($config);
		$this->email->from($senderEmail);
		$this->email->to($emailReceiver);
		$this->email->subject($emailSubject);
		$this->email->message($emailHtmlMessage);
		if ($this->email->send()) {
			echo "Send";
		} else {
			echo "Error";
		}
	}
	public function Gmail()
	{	
		$emailReceiver = $this->input->post('emailReceiver', TRUE);
		$emailSubject = $this->input->post('emailSubject', TRUE);
		$emailHtmlMessage = $this->input->post('emailHtmlMessage', TRUE);
		$senderEmail = $this->input->post('senderEmail', TRUE);
		$senderPassword = strval($this->input->post('senderPassword'));

		$config['protocol']    = 'smtp';
		$config['smtp_host']    = GmailSmtpServer;
		$config['smtp_port']    = GmailSmtpServerPort;
		$config['smtp_timeout'] = '300';
		$config['smtp_user']    = $senderEmail;
		$config['smtp_pass']    = $senderPassword;
		$config['charset']    = 'utf-8';
		$config['mailtype'] = 'html'; // or html
		$config['validation'] = TRUE;
		
		$this->email->set_newline("\r\n");
		$this->email->initialize($config);
		$this->email->from($senderEmail);
		$this->email->to($emailReceiver);
		$this->email->subject($emailSubject);
		$this->email->message($emailHtmlMessage);
		if ($this->email->send()) {
				echo "Send";
		} else {
			echo "Error";
		}
	}

	public function emailCreditialsCheck(){
		$smatpServer = $this->input->post('smtpserver', TRUE);
		$smatpEmail = $this->input->post('smatpemail', TRUE);
		$smatpPassword = strval($this->input->post('smatppassword'));
		if($smatpServer == "Gmail"){
			$config['protocol']    = 'smtp';
			$config['smtp_host']    = GmailSmtpServer;
			$config['smtp_port']    = GmailSmtpServerPort;
			$config['smtp_timeout'] = '300';
			$config['smtp_user']    = $smatpEmail;
			$config['smtp_pass']    = $smatpPassword;
			$config['charset']    = 'utf-8';
			$config['mailtype'] = 'text'; // or html
			$config['validation'] = TRUE;
			
			$this->email->set_newline("\r\n");
			$this->email->initialize($config);
			$this->email->from($smatpEmail);
			$this->email->to(EmailSenderTest);
			$this->email->subject("test");
			$this->email->message("test");
			if ($this->email->send()) {
				echo "Valid";
			} else {
				echo "Invalid account";
				//echo show_error($this->email->print_debugger());
			}

		}
		elseif($smatpServer == "Ops"){

			$config['protocol']    = 'smtp';
			$config['smtp_host']    = OpsSmtpServer;
			$config['smtp_port']    = OpsSmtpServerPort;
			$config['smtp_timeout'] = '300';
			$config['smtp_user']    = $smatpEmail;
			$config['smtp_pass']    = $smatpPassword;
			$config['smtp_crypto'] = 'tls';
			$config['charset']    = 'utf-8';
			$config['newline']    = "\r\n";
			$config['wordwrap']    = TRUE;
			$config['_smtp_auth'] = TRUE;
			$config['mailtype'] = 'text'; // or html
			$config['validation'] = TRUE;
						
			$this->email->initialize($config);
			$this->email->from($smatpEmail);
			$this->email->to(EmailSenderTest);
			$this->email->subject("test");
			$this->email->message("test");
			if ($this->email->send()) {
					echo "Valid";
			} else {
				echo "Invalid account";
			}
		}else{
			echo "Invalid SMTP Server";
		}
	}
	public function resetConfig(){
		$resetDIR = __DIR__ .'/../../assets';
		shell_exec("rm -R " . $resetDIR);
		redirect(base_url());

	}
	function scanbounce()
    {
		$gmail_or_ops = $this->input->post('gmail_or_ops', TRUE);
		$server_email_imap = $this->input->post('server_email_imap', TRUE);
		$server_password_imap = $this->input->post('server_password_imap');
		$email_scan = $this->input->post('email_scan', TRUE);
		$email_subject = $this->input->post('email_subject', TRUE);
		
		if($gmail_or_ops == 'Ops'){
			$server_imap = "{".SmtpImapServer."}";
			$subject_imap = 'Undelivered Mail Returned to Sender';
		}
		if($gmail_or_ops == 'Gmail'){
			$server_imap = "{".GmailImapServer."}";
			$subject_imap = 'Delivery Status Notification (Failure)';
		}
		define('IMAP_USERNAME', $server_email_imap); 				# your imap user name
		define('IMAP_PASSWORD', $server_password_imap); 				# your imap password
		define('IMAP_MAILBOX', $server_imap); 				# your imap address EG. {mail.example.com:993/novalidate-cert/ssl}
		define('ATTACHMENT_PATH', __DIR__ . '/../../attachments'); 	# the path to save attachments to or false to skip attachments
		
		$mark_as_read = true;
		$encoding = 'UTF-8';
		$imap = new Reader(IMAP_MAILBOX, IMAP_USERNAME, IMAP_PASSWORD, ATTACHMENT_PATH, $mark_as_read, $encoding);
		if($imap){
			$imap->searchSubject($subject_imap)->searchBody($email_scan)->onDate('now')->reset()->get();
			if(count($imap->emails()) > 0 ){
				echo "550";
				
			}else{
				echo "200";
			}
		}else{
			print_r('not connected');
		}

    }
}	