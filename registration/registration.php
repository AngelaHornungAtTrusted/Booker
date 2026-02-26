<?php
wp_enqueue_script('registration-js', BR_REGISTRATION_URL . '/registration.js', array('jquery'));
?>
<script>
  BR_AJAX_URL = '<?php echo esc_url(admin_url('admin-ajax.php', 'relative')); ?>'
  EVENTID = '<?php echo $eventId; ?>'
</script>
<h2>Event Registration Form</h2>
<form class="form booker-registration-form" id="booker-registration-form">
    <div class="row">
        <div class="col-md-4">
                <label for="firstName">First Name</label>
                <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter first name" minlength="2"
                       required aria-required="true">
        </div>
        <div class="col-md-4">
                <label for="lastName">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter last name" minlength="5"
                       required aria-required="true">
        </div>
        <div class="col-md-4">
                <label for="partyCount">Party Count</label>
                <input type="number" id="partyCount" class="form-control" name="partyCount" placeholder="Enter party count" required
                       aria-required="true">
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" required
                   aria-required="true">
        </div>
        <div class="col-md-5">
            <label for="phone">Phone</label>
            <input type="number" class="form-control" name="number" id="phone" placeholder="Enter Phone number" required
                   aria-required="true">
            <label for="eventId" hidden>Event ID</label>
            <input type="number" class="form-control" name="eventId" id="eventId" placeholder="Enter event ID" hidden>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary" style="margin-top: 20px;" id="booker-submit">Register
            </button>
        </div>
    </div>
</form>
<p>This event requires online registration prior to attendance.</p>