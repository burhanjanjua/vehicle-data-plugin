<?php
// Register settings page
add_action('admin_menu', 'auto_info_register_settings_page');
function auto_info_register_settings_page() {
    add_options_page(
        'Auto Info Settings',
        'Auto Info',
        'manage_options',
        'auto-info',
        'auto_info_settings_page'
    );
}
// Register settings
add_action('admin_init', 'auto_info_register_settings');
function auto_info_register_settings() {
    register_setting('auto_info_settings', 'auto_info_api_key');
    register_setting('auto_info_settings', 'auto_info_api_url');
    register_setting('auto_info_settings', 'auto_info_email');
    register_setting('auto_info_settings', 'auto_info_email2');
    register_setting('auto_info_settings', 'auto_info_thank');
    register_setting('auto_info_settings', 'auto_info_css');
}
// Settings page content
function auto_info_settings_page() {
    ?>
    <div class="wrap">
        <h1>Auto Info Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('auto_info_settings');
            do_settings_sections('auto_info_settings');
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="auto_info_api_key">API Key</label></th>
                    <td><input type="text" name="auto_info_api_key" value="<?php echo esc_attr(get_option('auto_info_api_key')); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="auto_info_api_url">API URL</label></th>
                    <td><input type="text" name="auto_info_api_url" value="<?php echo esc_attr(get_option('auto_info_api_url')); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="auto_info_email">Recipient Email 1</label></th>
                    <td><input type="email" name="auto_info_email" value="<?php echo esc_attr(get_option('auto_info_email')); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="auto_info_email2">Recipient Email 2</label></th>
                    <td><input type="email" name="auto_info_email2" value="<?php echo esc_attr(get_option('auto_info_email2')); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="auto_info_thank">After Submission page link</label></th>
                    <td><input style="" type="text" name="auto_info_thank" value="<?php echo esc_attr(get_option('auto_info_thank')); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="auto_info_css">After Submission page link</label></th>
                    <td><textarea name="auto_info_css" rows="15" class="large-text"><?php $value_txt = esc_attr(get_option('auto_info_css'));if ($value_txt == '') {$value_txt = '
    .vehicle_reg_main{
        display: flex;
        justify-content: center;
    }
    .vehicle_reg_main form {
    }
    #vehicle_reg{
    padding: 10px;
    font-size: 50px;
    font-weight: 900;
    border-radius: 12px;
    text-align: center;
    padding-left: 80px !important;
    background: url(https://www.rapidcarcheck.co.uk/CARLOGOS/ukreg.png) #f9c038 no-repeat !important;
    background-size: auto;
    background-size: auto 100%, 100% auto !important;
    }
    #vehicle-form #submit-btn{
        background: #33c4ed;
        font-style: italic;
        font-weight: 900;
        font-size: 20px;
        width: 100%;
        border-radius: 10px;
        margin-top: 15px;
    }
    #vehicle-data{
        padding: 40px;
        background: #ffffffba;
        border-radius: 15px;
        margin-top: 41px;
        color: #204867;
    }
    #vehicle-data {
    display: flex;
    width: 100%;
    }
    .vehicle-data-right div strong{
        width:50%;
    }
    .vehicle-data-right div p{
        width:50%;
    }
    .vehicle-data img{
        margin-bottom: 20px;
    }
    .error {
        border: 2px solid red!important;
    }
    #vehicle-forms .step input,
    #vehicle-forms .step select{
        height:45px;
        border: 2px solid #0b3858;
        border-radius: 8px;
        margin: 10px 0px 20px 0px;
    }
    #vehicle-forms{
    padding: 40px;
    background:#ffffffba;
    border-radius: 15px;
    }
    #vehicle-forms button{
    padding: 14px 22px 14px 22px;
    font-family: "Arimo", Sans-serif;
    font-size: 12px;
    font-weight: 900;
    text-transform: uppercase;
    line-height: 1em;
    letter-spacing: 1px;
    color: var(--e-global-color-accent);
    background-color: var(--e-global-color-primary);
    border-radius: 5px 5px 5px 5px;
    width:49%;
    }
    #vehicle-forms h4,
    #vehicle-forms h3,
    #vehicle-forms p{
        text-align:center;
        color:#0e3c5e;
    }
    #vehicle-forms label{
        color:#00345f;
    }
    .vehicle-data-right div{
        display:flex;
    }
    .vehicle-data-right {
        width:inherit;
    padding: 0px 0px 0px 25px;
    }';echo $value_txt;} else {echo $value_txt;}?></textarea></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}