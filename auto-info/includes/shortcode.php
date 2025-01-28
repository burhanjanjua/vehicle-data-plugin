<?php

// Shortcode to display vehicle data form
function vehicle_data_shortcode() {
    ob_start(); // Start output buffering
    ?>
    <style>
    <?php echo esc_attr(get_option('auto_info_css')); ?>
    </style>
    <div class="vehicle_reg_main">
    <!-- Vehicle Registration Form -->
    <form id="vehicle-form" method="post">
        <input type="text" class="customInputS" name="vehicle_reg" placeholder="ENTER REG" id="vehicle_reg" oninput="validateInput()" oninput="this.value = this.value.toUpperCase()" style="text-transform:uppercase" required>
        <input type="button" value="How Much?" id="submit-btn" disabled>
    </form>
    <!-- Multi-Step Form for Vehicle Details -->
    <form id="vehicle-forms" method="post" style="display:none;">
        <div>
            <h4>Complete The Steps To Get The Price</h4>
        </div>
        <!-- Step 1: Vehicle Make and Model -->
        <div class="step" id="step1">
            <label for="vehicle_make">Vehicle Make:<span style="color:red;"> *</span></label>
            <input type="text" id="vehicle_make" name="vehicle_make" value="<?php if(isset($make)) { echo esc_attr($make); } ?>" required />
            
            <label for="vehicle_model">Vehicle Model:<span style="color:red;"> *</span></label>
            <input type="text" id="vehicle_model" name="vehicle_model" value="<?php if(isset($model)) { echo esc_attr($model); } ?>" required />
            <button type="button" id="next1">Next</button>
        </div>

        <!-- Step 2: First Name, Last Name, and Current Mileage -->
        <div class="step" id="step2" style="display:none;">
            <label for="first_name">First Name:<span style="color:red;"> *</span></label>
            <input type="text" id="first_name" name="first_name" required />
            
            <label for="last_name">Last Name:<span style="color:red;"> *</span></label>
            <input type="text" id="last_name" name="last_name" required />
            
            <label for="current_mileage">Current Mileage:<span style="color:red;"> *</span></label>
            <input type="number" id="current_mileage" name="current_mileage" required />
            
            <button type="button" id="prev2">Previous</button>
            <button type="button" id="next2">Next</button>
        </div>
        <!-- Step 3: Email and Phone Number -->
        <div class="step" id="step3" style="display:none;">
        <label for="email">Email:<span style="color:red;"> *</span></label>
        <input type="email" id="email" name="email" required />

        <label for="phone">Phone Number:<span style="color:red;"> *</span></label>
        <input type="number" pattern="" id="phone" oninput="validateInput12(this)" name="phone" required />
        
        <label for="timeframe">Selling Time Frame:<span style="color:red;"> *</span></label>
        <select id="timeframe" name="timeframe" required style="margin-bottom: 0;" required />
            <option value="">-- Select a timeframe --</option>
            <option value="urgently">Urgently</option>
            <option value="next_week">Within the next week</option>
            <option value="next_month">Within the next month</option>
            <option value="exploring">Just exploring for now</option>
        </select></br>

        <label>
            <input type="checkbox" id="togglePrice" style="margin: 0;height:fit-content;"/> Have an ideal price in mind?
        </label>

        <div id="priceContainer"></div></br>

        <button type="button" id="prev3">Previous</button>
        <button type="button" id="next3">Next</button>
        </div>

        <!-- Step 4: Vehicle Details -->
        <div class="step" id="step4" style="display:none;">
            <label for="transmission_type">Transmission Type:<span style="color:red;"> *</span></label>
            <input type="text" id="transmission_type" name="transmission_type" value="<?php if(isset($trans)) { echo esc_attr($trans); } ?>" required />
            
            <label for="seating_capacity">Seating Capacity:<span style="color:red;"> *</span></label>
            <input type="text" id="seating_capacity" name="seating_capacity" value="<?php if(isset($seating)) { echo esc_attr($seating); } ?>" required />

            <label for="vehicle_vrm">Vehicle Registration:<span style="color:red;"> *</span></label>
            <input type="text" id="vehicle_vrm" name="vehicle_vrm" value="<?php if(isset($seating)) { echo esc_attr($vrm); } ?>" required />
            
            <button type="button" id="prev4">Previous</button>
            <button type="submit" id="form-submit">Submit</button>
        </div>
        <!-- Step 5: Thank You Message -->
        <div class="step" id="step5" style="display:none;">
            <h3>Thank you for submitting your details!</h3>
            <br>
            <h3>Redirecting Now...</h3>
        </div>
        <input type="hidden" name="action" value="my_form_submission">
    </form>
</div>
    <script>
    function validateInput12(input) {
            const value = input.value;
            // Regex to allow numbers starting with 0 or 9 only
            if (!/^[0987]\d*$/.test(value)) {
                input.value = ""; // Clear input if not valid
                input.classList.add('error')
            }
            if (value.length < 10 || value.length > 13){
                input.classList.add('error')
            }
            else{
                input.classList.remove('error')
            }
        }
    const toggleCheckbox = document.getElementById('togglePrice');
    const priceContainer = document.getElementById('priceContainer');

    toggleCheckbox.addEventListener('change', function () {
    if (this.checked) {
      priceContainer.innerHTML = `
        <label for="price">Ideal Selling Price:</label>
        <input type="number" id="price" name="price" />
      `;
    } else {
      priceContainer.innerHTML = ''; // Clear the div when unchecked
    }
    });

        function validateInput() {
            const inputField = document.getElementById('vehicle_reg');
            const submitBtn = document.getElementById('submit-btn');

            // Check if the input field is empty
            if (inputField.value.trim() === "") {
                // Add red border if empty
                inputField.style.border = "2px solid red";
                // Disable the submit button
                submitBtn.disabled = true;
            } else {
                // Remove red border if not empty
                inputField.style.border = "";
                // Enable the submit button
                submitBtn.disabled = false;
            }
        }

        function formatInput(input) {
            // Remove all symbols, keeping only alphanumeric characters (letters and numbers)
            let sanitizedValue = input.value.replace(/[^a-zA-Z0-9]/g, '');
            
            // Add space after every 4 characters (alphanumeric only)
            input.value = sanitizedValue.replace(/(.{4})/g, '$1  ').trim();
        }

        // Apply formatting to all elements with the class 'customInput'
        document.querySelectorAll('.customInputS').forEach(element => {
            element.addEventListener('input', function() {
                formatInput(this);
            });
        });

        // Helper function to validate required fields in a step
        function validateStep(stepId) {
            let isValid = true;
            const inputs = document.querySelectorAll(`#${stepId} input`);
            inputs.forEach(input => {
                if (!input.value) {
                    input.classList.add('error');  // Add a visual cue if needed
                    isValid = false;
                } else {
                    input.classList.remove('error');  // Remove error class if input is valid
                }
            });
            return isValid;
        }
        // Handle transitions between steps
        document.getElementById('submit-btn').addEventListener('click', function() {
            document.getElementById('vehicle-form').style.display = 'none';
            document.getElementById('vehicle-forms').style.display = 'block';
            document.getElementById('step1').style.display = 'block';
        });

        document.getElementById('next1').addEventListener('click', function() {
            if (validateStep('step1')) {
                document.getElementById('step1').style.display = 'none';
                document.getElementById('step2').style.display = 'block';
            }
        });

        document.getElementById('prev2').addEventListener('click', function() {
            document.getElementById('step2').style.display = 'none';
            document.getElementById('step1').style.display = 'block';
        });

        document.getElementById('next2').addEventListener('click', function() {
            if (validateStep('step2')) {
                document.getElementById('step2').style.display = 'none';
                document.getElementById('step3').style.display = 'block';
            }
        });

        document.getElementById('prev3').addEventListener('click', function() {
            document.getElementById('step3').style.display = 'none';
            document.getElementById('step2').style.display = 'block';
        });

        document.getElementById('next3').addEventListener('click', function() {
            if (validateStep('step3')) {
                document.getElementById('step3').style.display = 'none';
                document.getElementById('step4').style.display = 'block';
            }
        });

        document.getElementById('prev4').addEventListener('click', function() {
            document.getElementById('step4').style.display = 'none';
            document.getElementById('step3').style.display = 'block';
        });

        document.getElementById('form-submit').addEventListener('click', function(e) {
            e.preventDefault(); // Prevent actual form submission
            if (validateStep('step4')) {
                document.getElementById('step4').style.display = 'none';
                document.getElementById('step5').style.display = 'block';
            }
        });
    </script>

    <div id="form-response" style="display:none;"></div>
    <div id="vehicle-data-result"></div>

    <?php
    return ob_get_clean(); // Return the buffered content
}
add_shortcode('vehicle_data', 'vehicle_data_shortcode');
