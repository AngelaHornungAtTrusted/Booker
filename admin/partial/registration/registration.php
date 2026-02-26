<?php

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<h2>Registration Management</h2>
<div class="row">
    <div class="col-md-6">
        <label for="event-select" hidden>Event Select</label>
        <select id="event-select" style="min-width: 100%;">
            <option selected="selected" value="" disabled>Select an Event</option>
        </select>
    </div>
    <div class="col-md-3">
        <a class="btn btn-success" id="export-registrations"><i class="fa fa-floppy-o"></i> Export Registrations</a>
    </div>
</div>
<div class="row" style="margin-top:10px">
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Number</th>
            <th>Party Count</th>
            <th>Registration Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody id="registration-table"></tbody>
    </table>
</div>
