<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Update application
 */
class Update extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
        
        // Each page served by this controller requires user to be logged in.
        if ($this->regnick_auth->logged_in() === false)
        {
            store_location();
            redirect('ucp/login', 'refresh');
        }
        
        // Each page served by this controller requires user to have `administrator` access.
        if ($this->regnick_auth->isOwner($this->session->userdata('user_id')) === false)
        {
            notify($this->lang->line('insuficient_access'), 'success');
            redirect('', 'refresh');
        }
        
        $this->load->library('update_manager');
    }
    
    public function index()
    {    
        $db_update      = $this->migration->db_update_available();
        
        if ($db_update)
        {
            notify('Database update available. [ <a href="'. site_url('update/database') .'">update now</a> ]', 'info');
        }
        else
        {
            notify('Dabase is up to date.', 'success');
        }
        
        redirect('');
    }
    
    public function database()
    {
        if ( $this->migration->db_update_available() )
        {
            $this->update_manager->database();
            notify($this->update_manager->get_status());
        }
        
        redirect('');
    }
    
    //@todo: implement FS update
}

/* End of file update.php */