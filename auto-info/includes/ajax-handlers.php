<?php
// Handle AJAX request to get vehicle data
add_action('wp_ajax_get_vehicle_data', 'handle_vehicle_data_ajax');
add_action('wp_ajax_nopriv_get_vehicle_data', 'handle_vehicle_data_ajax');

function handle_vehicle_data_ajax() {
    if (isset($_POST['vehicle_reg'])) {
        $vehicle_reg_1 = sanitize_text_field($_POST['vehicle_reg']);
        $vehicle_reg = str_replace(' ', '', $vehicle_reg_1);
        $vehicle_data = get_vehicle_data($vehicle_reg);
        if ($vehicle_data) {
            echo display_vehicle_data_table($vehicle_data);
        } else {
            echo "<br><p style='text-align:center;'>No data found or there was an error.</p>";
        }
    }
    wp_die(); // Terminate the AJAX request properly
}
// Function to get vehicle data from API
function get_vehicle_data($vehicle_reg) {
    $curl = curl_init();
    $ApiKey = get_option('auto_info_api_key'); // Replace with your actual API key
    $url = get_option('auto_info_api_url');
    $url = sprintf($url, $vehicle_reg, $ApiKey);

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET"
    ));
    $response = curl_exec($curl);

    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
        return false;
    } else {
        return json_decode($response, true);
    }
}
// Function to display vehicle data as a table
function display_vehicle_data_table($data) {
    $res = esc_html($data['Response']['StatusCode']);
    if ($res=='Success') {
        // Extract vehicle details
    $make = esc_html($data['Response']['DataItems']['VehicleRegistration']['Make']);
    $vrm = esc_html($data['Response']['DataItems']['VehicleRegistration']['Vrm']);
    $model = esc_html($data['Response']['DataItems']['VehicleRegistration']['MakeModel']);
    $trans = esc_html($data['Response']['DataItems']['VehicleRegistration']['TransmissionType']);
    $seating = esc_html($data['Response']['DataItems']['VehicleRegistration']['SeatingCapacity']);
    $fuel = esc_html($data['Response']['DataItems']['VehicleRegistration']['FuelType']);
    $date = esc_html($data['Response']['DataItems']['VehicleRegistration']['YearMonthFirstRegistered']);
    $color = esc_html($data['Response']['DataItems']['VehicleRegistration']['Colour']);
    ob_start(); // Start buffering the output
    ?>
    <div  style="" id="vehicle-data" 
         data-make="<?php echo $make; ?>" 
         data-vrm="<?php echo $vrm; ?>" 
         data-model="<?php echo $model; ?>"
         data-trans="<?php echo $trans; ?>"
         data-seating="<?php echo $seating; ?>"
         data-fuel="<?php echo $fuel; ?>"
         data-date="<?php echo $date; ?>"
         data-color="<?php echo $color; ?>">
            <div>
                <img src="https://cdn.vdicheck.com/badges/<?php echo $make; ?>.png?width=130">
            </div>
            <div class="vehicle-data-right">
                <div><strong>Vehicle Make: </strong><p><?php echo $make; ?></p></div>
                <div><strong>Vehicle Registration: </strong><p><?php echo $vrm; ?></p></div>
                <div><strong>Vehicle Model: </strong><p><?php echo $model; ?></p></div>
                <div><strong>Transmission Type: </strong><p><?php echo $trans; ?></p></div>
                <div><strong>Seating Capacity: </strong><p><?php echo $seating; ?></p></div>
                <div><strong>Fuel Type: </strong><p><?php echo $fuel; ?></p></div>
                <div><strong>Registration Date: </strong><p><?php echo $date; ?></p></div>
                <div><strong>Vehicle Color: </strong><p><?php echo $color; ?></p></div>
            </div>
    </div>
    <?php
    return ob_get_clean(); // Return buffered content
    }
    else if($res=='KeyInvalid'){
        return '<br><p style="text-align:center;">No data available, Please enter a valiid vehicle registration,<br> or enter the details manually. </p>';
    }
}
// Handle AJAX form submission to send email
add_action('wp_ajax_my_form_submission', 'handle_my_form_submission');
add_action('wp_ajax_nopriv_my_form_submission', 'handle_my_form_submission');

function handle_my_form_submission() {
    if (isset($_POST['vehicle_make']) && isset($_POST['vehicle_model'])) {
        // Sanitize and collect form inputs
        $vehicle_make = sanitize_text_field($_POST['vehicle_make']);
        $vehicle_vrm = sanitize_text_field($_POST['vehicle_vrm']);
        $vehicle_model = sanitize_text_field($_POST['vehicle_model']);
        $transmission_type = sanitize_text_field($_POST['transmission_type']);
        $seating_capacity = sanitize_text_field($_POST['seating_capacity']);
        $first_name = sanitize_text_field($_POST['first_name']);        
        $last_name = sanitize_text_field($_POST['last_name']);
        $current_mileage = sanitize_text_field($_POST['current_mileage']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $timeframe = sanitize_text_field($_POST['timeframe']);
        $price_in = sanitize_text_field($_POST['price']);
        if (empty($timeframe)) {
            $timeframe='No Input Given';
        }
        if (empty($price_in)) {
            $price_in='No Input Given';
        }
        // Prepare email details
        $to = get_option('auto_info_email');
        $to_1 = get_option('auto_info_email2'); // Replace with your actual email
        $subject = 'Vehicle Information Submission';
        $body = "
            <h2>Vehicle Information</h2>
            <p><strong>Make:</strong> $vehicle_make</p>
            <p><strong>Registration:</strong> $vehicle_vrm</p>
            <p><strong>Model:</strong> $vehicle_model</p>
            <p><strong>Transmission:</strong> $transmission_type</p>
            <p><strong>Seating Capacity:</strong> $seating_capacity</p>
            <p><strong>Client's First Name:</strong> $first_name</p>
            <p><strong>Client's Last Name:</strong> $last_name</p>
            <p><strong>Current Mileage:</strong> $current_mileage</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Phone:</strong> $phone</p>
            <p><strong>Time Frame:</strong> $timeframe</p>
            <p><strong>Ideal Price:</strong> $price_in</p>
        ";
        // WordPress email headers
        $headers = array('Content-Type: text/html; charset=UTF-8');

        // Send email using wp_mail
        if (wp_mail($to, $subject, $body, $headers)&&wp_mail($to_1, $subject, $body, $headers)) {
            $redirect_url = get_option('auto_info_thank'); // Replace with your custom URL
            echo '<p>Email sent successfully! Redirecting...</p>';
            echo '<script>window.location.href = "' . $redirect_url . '";</script>';
        } else {
            echo '<p>Failed to send the email.</p>';
        }
    } else {
        echo '<p>Invalid form data.</p>';
    }

    wp_die(); // Properly terminate the AJAX request
}