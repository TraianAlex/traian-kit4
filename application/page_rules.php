<?php
   
/*
 * defaul class and page
 */
    $config_page['default_class'] = 'users';
    $config_page['default_page'] = 'index';
    
//------ REGISTER PAGE NAMES -------------------------------------------------

/*
* a white list with user pages - only validation
*/
    $config_page['all_pages'] = ['index', 'login', 'register', 'log_out', 'forgot_password', 'send_pass',
           'profile', 'personal_data', 'password', 'update_data', 'change_pass', 'oath_ajax_login', 'oath_fb_login',
           'login_area', 'nologin_area', 'test',
           null];
/*
* in this page must log in first
*/
       $config_page['page_login'] = ['login_area', 'profile', 'personal_data', 'password', 'update_data',
           'change_pass'];
/*
 * if is a condition
 */
       $config_page['aside_left'] = array();

/*
 * include sidebar float right
 */       
       $config_page['sidebar2'] = array();
/*
 * a white list for admin pages
 */
       $config_page['admin'] = ['index', 'admin', 'login', 'users', 'register', 'profile', 'visitors', 'test'];
/*
* in this page must log in first
*/
       $config_page['login_admin'] = ['users', 'register', 'profile', 'visitors', 'test'];
       
//---------REGISTER CLASSES NAMES FROM URL ----------------------------------------------------------------------
       
       $config_class['allowed'] = ['users', 'admins'];