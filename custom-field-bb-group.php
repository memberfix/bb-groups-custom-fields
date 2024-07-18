<?php
/**
 * Plugin Name: Custom Group's Field MFX
 * Description: add custom field for group's short descrition and a checkbox to displah or hide "request accedd" button
 * Plugin URI:
 * Version: 1.0
 * Author: Memberfix
 * Author URI: memberfix.rocks
 */
// Add the meta box to the group admin screen


// Add the meta box to the group admin screen
add_action( 'bp_groups_admin_meta_boxes', 'bpgcp_add_admin_metabox' );
function bpgcp_add_admin_metabox() {
    add_meta_box(
        'bb_group_short_description', // Meta box ID 
        'Group Short Description', // Meta box title
        'bpgsd_render_admin_metabox', // Meta box callback function
        get_current_screen()->id, // Screen on which the metabox is displayed.
        'normal', // Where the meta box is displayed
        'core' // Meta box priority
    );
}

// Render the meta box on the group admin screen
function bpgsd_render_admin_metabox() {
    $group_id = intval( $_GET['gid'] );
    $bb_group_short_description_mfx = groups_get_groupmeta( $group_id, 'bb_group_short_description_mfx' );
    ?>

    <div class="bp-groups-settings-section" id="bp-groups-settings-section-short-description">
        <fieldset>
            <legend>Short description for the group:</legend>
            <label>
                <input type="text" name="bb_group_short_description_mfx" value="<?php echo esc_attr( $bb_group_short_description_mfx ); ?>" placeholder="Enter short description text">
            </label>
        </fieldset>
    </div>

    <?php
}

// Save the meta box data
add_action( 'bp_group_admin_edit_after', 'bpgcp_save_metabox_fields' );
function bpgcp_save_metabox_fields( $group_id ) {
    if ( isset( $_POST['bb_group_short_description_mfx'] ) ) {
        $bb_group_short_description_mfx = sanitize_text_field( $_POST['bb_group_short_description_mfx'] );
        groups_update_groupmeta( $group_id, 'bb_group_short_description_mfx', $bb_group_short_description_mfx );
    }
}

// Display the short description on the groups directory page
add_action( 'bp_directory_groups_item', 'bpgcp_display_group_short_description' );
function bpgcp_display_group_short_description() {
    $group_id = bp_get_group_id();
    $short_description = groups_get_groupmeta( $group_id, 'bb_group_short_description_mfx' );

    echo '<div class="group-short-description item-meta">';
    echo '<p>' . esc_html( $short_description ) . '</p><br>';
    echo '</div>';
}


/////Hide Access Button 

// Add the meta box for hiding request access button
add_action( 'bp_groups_admin_meta_boxes', 'bpgcp_add_hide_request_button_metabox' );
function bpgcp_add_hide_request_button_metabox() {
    add_meta_box(
        'bb_group_hide_request_button', // Meta box ID 
        'Hide Request Access Button', // Meta box title
        'bpgcp_render_hide_request_button_metabox', // Meta box callback function
        get_current_screen()->id, // Screen on which the metabox is displayed.
        'normal', // Where the meta box is displayed
        'core' // Meta box priority
    );
}

// Render the meta box content
function bpgcp_render_hide_request_button_metabox() {
    $group_id = intval( $_GET['gid'] );
    $hide_request_button = groups_get_groupmeta( $group_id, 'bb_group_hide_request_button' );
    ?>

    <div class="bp-groups-settings-section" id="bp-groups-settings-section-hide-request-button">
        <fieldset>
            <legend>Hide Request Access Button:</legend>
            <label>
                <input type="checkbox" name="bb_group_hide_request_button" value="1" <?php checked( $hide_request_button, 1 ); ?>>
                Hide the request access button for this group
            </label>
        </fieldset>
    </div>

    <?php
}

// Save the meta box data
add_action( 'bp_group_admin_edit_after', 'bpgcp_save_hide_request_button_metabox_fields' );
function bpgcp_save_hide_request_button_metabox_fields( $group_id ) {
    if ( isset( $_POST['bb_group_hide_request_button'] ) ) {
        $hide_request_button = intval( $_POST['bb_group_hide_request_button'] );
        groups_update_groupmeta( $group_id, 'bb_group_hide_request_button', $hide_request_button );
    }
}

add_action( 'bp_directory_groups_item', 'bpgcp_display_group_info_and_request_access_button' );

function bpgcp_display_group_info_and_request_access_button() {
    $group_id = bp_get_group_id();
    $hide_request_button = groups_get_groupmeta( $group_id, 'bb_group_hide_request_button' );

    // Check if hide request button checkbox is checked
    $hide_request_button_checked = ! empty( $hide_request_button ) && $hide_request_button == 1;

    if ( $hide_request_button_checked ) {
        // 
        echo '<style>
            #groupbutton-' . esc_attr( $group_id ) . ' > .request-membership {
                display: none !important;
            }
        </style>';
    }
}


