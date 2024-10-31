<?php

  /*
    Plugin Name: Papershare Paperclips
    Plugin URI: http://papershare.com
    Description: Special PaperShare plug-in
    Author URI: http://papershare.com
    Author: PaperShare
    Version: 1.0
  */
  
  /*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301  USA
	*/
 
  @require_once(ABSPATH."wp-admin/includes/upgrade.php");
  
  global $_plugin;
  
  $_plugin = new CPaperShare1();
  
  class CPaperShare1
    {
      function Map($a)
        {
          global $wpdb;
          
          @extract(shortcode_atts(array("id" => "0"), $a));
          
          $b = $wpdb->get_col("SELECT x FROM ".$wpdb->prefix.self::$table." WHERE k=".intval($id)." LIMIT 1",0);
          
          return stripslashes($b[0]);
        }
        
      function Init()
        {
          add_shortcode(self::$base_code, Array(get_class(), "Map"));
        }
        
      function Activate()
        {
          global $wpdb;
          
          dbDelta("CREATE TABLE IF NOT EXISTS ".$wpdb->prefix.self::$table." (k int primary key auto_increment, t varchar(25), x text)");
          
        }
        
      function Deactivate()
        {
          remove_shortcode(self::$base_code);
        }
        
      function Uninstall()
        {
          global $wpdb;
          
          $wpdb->query("DROP TABLE IF EXISTS ".$wpdb->prefix.self::$table);
        }
        
      public function __construct()
        {
          self::$table = strtolower(get_class());
          
          register_activation_hook(__FILE__, Array(&$this, "Activate"));
          
          register_deactivation_hook(__FILE__, Array(&$this, "Deactivate"));
          
          register_uninstall_hook(__FILE__, Array(&$this, "Uninstall"));
          
          add_action("init", Array(&$this, "Init"));
          
          add_action('admin_init', array(&$this, 'admin_init'));
          
          add_action('admin_menu', array(&$this, 'add_menu'));
        }
        
      public function admin_init()
        {
          register_setting(get_class().'-group', 'c');
          register_setting(get_class().'-group', 'e');
        }
        
      public function add_menu()
        {
          add_options_page(self::$identified." Settings", self::$identified, "manage_options", strtolower(get_class()), array(&$this, "plugin_settings_page"));
        }
        
      public function plugin_settings_page()
        {
          if (current_user_can("manage_options"))
            {
              @include(sprintf("%s/settings.php", dirname(__FILE__)));
            }
          else
            {
              wp_die(__('You do not have sufficient permissions to access this page.'));
            }
        }
        
      private static $identified = "Papershare";
      private static $base_code = "paperclip";
      private static $table = "";
    }
    
    if(isset($_plugin))
      {
        function plugin_settings_link($links)
          {
            global $_plugin;
            
            $settings_link = '<a href="options-general.php?page='.strtolower(get_class($_plugin)).'">Settings</a>';
            
            array_unshift($links, $settings_link);
            
            return $links;
          }
          
        $plugin = plugin_basename(__FILE__);
        
        add_filter("plugin_action_links_$plugin", 'plugin_settings_link');
      }
      
    register_uninstall_hook(__FILE__, Array(get_class($_plugin), "Uninstall"));

?>