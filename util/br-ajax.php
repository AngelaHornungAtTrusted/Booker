<?php
function wp_ajax_br_init_options($options): bool
{
    global $wpdb;

    try {
        //can't find one, insert it
        foreach ($options as $option) {
            if (sizeof($wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "options WHERE option_name = '" . $option['option_name'] . "'")) < 1) {
                $wpdb->insert(
                    $wpdb->prefix . 'options',
                    array(
                        'option_name' => sanitize_text_field($option['option_name']),
                        'option_value' => sanitize_text_field($option['option_value']),
                    )
                );
            }
        }

    } catch (Exception $e) {
        return false;
    }

    return true;
}

function wp_ajax_br_init_registrations()
{
    global $wpdb;

    try {
        $charset_collate = $wpdb->get_charset_collate();

        $itemTables = "CREATE TABLE booker_registrations (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        fname varchar(255) DEFAULT '' NOT NULL,
        lname varchar(255) DEFAULT '' NOT NULL,
        pcount mediumint(9) NOT NULL,
        email varchar(255) DEFAULT '' NOT NULL,
        pnumber mediumint(9) NOT NULL,
        approved tinyint(1) NOT NULL DEFAULT '0',
        create_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        update_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY (id)) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($itemTables);
    } catch (exception $e) {
        return false;
    }

    return true;
}

//separated as getting labels needs to be accessible to all, but updating only to administrators
function wp_ajax_br_get_labels()
{
    $response = new stdClass();
    global $wpdb;

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $labels = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "options WHERE option_name LIKE '%BookerLabel%'");

        $response->code = 200;
        $response->status = 'success';
        $response->data = $labels;
    } else {
        $response->code = 400;
        $response->status = 'Invalid Request';
    }

    wp_send_json($response);
}

function wp_ajax_br_update_labels()
{
    $response = new stdClass();
    global $wpdb;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            $option_name = (intval($_POST['data']['type']) === 1) ? 'BookerLabelLocation' : ((intval($_POST['data']['type']) === 2) ? 'BookerLabelType' : 'BookerLabelDate');

            $wpdb->update($wpdb->prefix . "options", array(
                'option_value' => sanitize_text_field($_POST['data']['content']),
            ),
                array('option_name' => $option_name)
            );

            $response->code = 200;
            $response->status = 'success';
            $response->message = "Data Update Successful";
        } catch (\Exception $e) {
            $response->code = 500;
            $response->status = 'error';
            $response->message = $e->getMessage();
        }
    } else {
        $response->code = 400;
        $response->message = "Invalid Request";
    }

    wp_send_json($response);
}

function wp_ajax_br_get_events()
{
    $response = new stdClass();
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        global $wpdb;

        try {
            //return data
            $posts = array();
            $filters = array();
            $thumbnails = array();

            //todo remove need for $times variable
            $times = 0;
            $conditional = "";
            $conditions = array(intval($_GET['data']['location']), intval($_GET['data']['type']), intval($_GET['data']['category']));
            foreach ($conditions as $condition) {
                if (intval($condition) > 0) {
                    $conditional .= ((strlen($conditional) > 0) ? " OR " : " WHERE ") . "term_taxonomy_id = " . intval($condition);
                    $times++;
                }
            }

            //todo format posted dates from js then run comparison, if date values are 0 (set them to this by default), then only load events today and after
            foreach (array_count_values(array_column($wpdb->get_results("SELECT object_id FROM " . $wpdb->prefix . "term_relationships" . $conditional), 'object_id')) as $key => $count) {
                if ($count >= $times && sizeof($wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "posts WHERE post_type = 'event' AND post_status = 'publish' AND id = " . intval($key))) > 0) {
                    $date = date($wpdb->get_results("SELECT meta_value FROM " . $wpdb->prefix . "postmeta WHERE post_id = " . intval($key) . " AND meta_key = 'booker-event-meta-date'")[0]->meta_value);
                    if ($date >= date($_GET['data']['sDate']) && $date <= date($_GET['data']['eDate'])) {
                        $posts[$key] = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "posts" . " LEFT JOIN " . $wpdb->prefix . "postmeta" . " ON " . $wpdb->prefix . "postmeta" . ".post_id = " . $wpdb->prefix . "posts" . ".id WHERE post_type = 'event' AND post_status = 'publish' AND meta_key = '_thumbnail_id' AND id = " . intval($key));
                        $thumbnails[$key] = get_site_url() . '/wp-content/uploads/' . $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "postmeta" . " WHERE meta_key = '_wp_attached_file' AND post_id = " . intval($posts[$key][0]->meta_value))[0]->meta_value;
                        foreach ($wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "term_relationships WHERE " . $wpdb->prefix . "term_relationships.object_id = " . intval($key)) as $taxId) {
                            $term = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "term_taxonomy" . " LEFT JOIN " . $wpdb->prefix . "terms" . " ON " . $wpdb->prefix . "terms.term_id = " . $wpdb->prefix . "term_taxonomy.term_id" . " WHERE term_taxonomy_id = " . intval($taxId->term_taxonomy_id));

                            if (!in_array($term, $filters)) {
                                $filters[] = $term;
                            }
                        }
                    }
                }
            }

            $response->data = [$posts, $thumbnails, $filters];
            $response->message = "Got Events";
            $response->status = 'success';
        } catch (\Exception $e) {
            $response->status = 'error';
            $response->message = $e->getMessage();
        }
    } else {
        $response->code = 400;
        $response->status = 'error';
        $response->message = "Method not allowed";
    }

    wp_send_json($response);
}

function wp_ajax_br_registration()
{
    global $wpdb;
    $response = new stdClass();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        var_dump($_POST['data']);
        die();
    } else {
        $response->code = 400;
        $response->message = "Invalid Request";
    }

    wp_send_json($response);
}

function wp_ajax_br_get_registrations()
{
    global $wpdb;
    $response = new stdClass();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    } else {
        $response->code = 400;
        $response->message = "Invalid Request";
    }

    wp_send_json($response);
}