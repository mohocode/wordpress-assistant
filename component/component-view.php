<?php 

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Custom_Resumes_List_Table extends WP_List_Table {
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        usort($data, array(&$this, 'sort_data'));

        $perPage = 10;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ));

        $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    public function get_columns()
    {
        $columns = array(
            'fullname' => 'Full Name',
            'subject' => 'Subject',
            'file' => 'File Resume',
            // Add more columns as needed
        );

        return $columns;
    }

    public function get_hidden_columns()
    {
        return array();
    }

    public function get_sortable_columns()
    {
        return array('fullname' => array('fullname', false));
    }

    private function table_data()
    {
        global $wpdb;
        $data = array();
        $query = "SELECT * FROM {$wpdb->prefix}resumes";
        $results = $wpdb->get_results($query, ARRAY_A);

        foreach ($results as $item) {
            $data[] = array(
                'fullname' => $item['fullname'],
                'subject' => $item['subject'],
                'file' => $item['file'],
                'id' => $item['ID'],
                // Map additional columns as needed
            );
        }

        return $data;
    }

    function column_fullname($item) {
        // Edit and View links for simplicity; adjust as needed
        $edit_link = esc_url(add_query_arg(['action' => 'edit', 'resume' => $item['id']], menu_page_url('your-menu-slug', false)));
        $view_link = esc_url(add_query_arg(['action' => 'view', 'resume' => $item['id']], menu_page_url('your-menu-slug', false)));
        $delete_nonce = wp_create_nonce('delete_resume_' . $item['id']);
        
        // Use a class 'wp-swal-confirm' to hook our SweetAlert confirmation
        $delete_link = sprintf('<a href="?page=%s&action=%s&resume=%s&_wpnonce=%s" class="wp-swal-confirm">Delete</a>', $_REQUEST['page'], 'delete', $item['id'], $delete_nonce);

        $actions = [
            'edit' => '<a href="' . $edit_link . '">Edit</a>',
            'view' => '<a href="' . $view_link . '">View</a>',
            'delete' => $delete_link
        ];

        return sprintf('%1$s %2$s', $item['fullname'], $this->row_actions($actions));
    }

     // Implement column_default and optionally, column_{column_name} for custom columns
     public function column_default($item, $column_name)
     {
         switch ($column_name) {
             case 'fullname':
             case 'subject':
 
                 return $item[$column_name];
 
             case 'file':
                 return '<a href="' . home_url() . "/wp-content/uploads/resume/" . $item[$column_name] . '">Download Resume</a>';
             default:
                 return print_r($item, true); // For debugging purposes
         }
     }
 
     // Optional: Implement sorting function if needed
     private function sort_data($a, $b)
     {
         // Sorting logic goes here
     }
}


function enqueue_admin_scripts() {
    wp_enqueue_script('sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11');
    wp_enqueue_script('custom-admin-js', plugin_dir_url(__FILE__) . 'js/custom-admin.js', array('sweetalert2'), null, true);
}
add_action('admin_enqueue_scripts', 'enqueue_admin_scripts');


function custom_resumes_admin_page() {
    $myListTable = new Custom_Resumes_List_Table();
    echo '<div class="wrap"><h2>My Custom Resumes</h2>';
    $myListTable->prepare_items(); 
    $myListTable->display(); 
    echo '</div>';
}

function add_custom_resumes_menu_item() {
    add_menu_page('Custom Resumes', 'Custom Resumes', 'manage_options', 'custom-resumes', 'custom_resumes_admin_page');
}

add_action('admin_menu', 'add_custom_resumes_menu_item');


function handle_delete_action() {
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && wp_verify_nonce($_GET['_wpnonce'], 'delete_resume_' . $_GET['resume'])) {
        global $wpdb;
        $wpdb->delete($wpdb->prefix . 'custom_resumes', ['id' => $_GET['resume']], ['%d']);
        wp_redirect(remove_query_arg(['action', 'resume', '_wpnonce']));
        exit;
    }
}
add_action('admin_init', 'handle_delete_action');

