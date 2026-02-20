<?php
//todo set up different shortcode cases, for now keep all three filters
?>
<script>
  BR_AJAX_URL = '<?php echo esc_url(admin_url('admin-ajax.php', 'relative')); ?>';
  LOCATIONID = '<?php echo $locationId; ?>';
  TYPEID = '<?php echo $typeId; ?>';
  DATEID = '<?php echo $dateId; ?>';
  CATEGORYID = '<?php echo $categoryId; ?>';
  STYLE = '<?php echo $style; ?>';
</script>
<!--todo implement proper styling controls for links-->
<style>
    a {
        text-decoration: none; !important;
    }
</style>
<div class="row">
    <div class="col-md-4" <?php echo ($hideSelect === 1) ? "hidden" : ""?>>
        <label id="#location-label" for="br-event-location" hidden>location</label>
        <select class="br-select" name="br-event-location" id="br-event-location">
            <option id="location-placeholder" value="<?php echo $locationId; ?>" selected></option>
        </select>
    </div>
    <div class="col-md-4" <?php echo ($hideSelect === 1) ? "hidden" : ""?>>
        <label for="br-event-type" hidden>Type</label>
        <select class="br-select" name="br-event-type" id="br-event-type">
            <option id="type-placeholder" value="<?php echo $typeId; ?>" selected></option>
        </select>
    </div>
    <div class="col-md-4" <?php echo ($hideSelect === 1) ? "hidden" : ""?>>
        <label for="br-event-date" hidden>date</label>
        <select class="br-select" name="br-event-date" id="br-event-date">
            <option id="date-placeholder" value="<?php echo $dateId; ?>" selected></option>
        </select>
    </div>
</div>
<div class="row" id="prodTable">

</div>
