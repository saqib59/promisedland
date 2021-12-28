<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//require_once '../config/config.php';

/* if (user() == false) {
    redirect("Please login first!", USER . '/login.php');
    exit();
} */
?>

<div class="modal fade shd_modal" id="consultingModal" tabindex="-1" aria-labelledby="consultingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Consultant Booking: <span id="consultant_name"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="course_thread">
                    <div class="course_thread-alert"></div>
                    <form id="consulting_booking" action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" autocomplete="off" enctype="multipart/form-data">
                        <input name="consulting_id" type="hidden" value="">

                        <div class="form-group">
                            <label>Object ID</label>
                            <input name="object_id" type="text" placeholder="Please enter object id" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Contact Number *</label>
                            <input name="contact_number" type="text" placeholder="Please enter a contact number" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Time *</label>
                            <select class="form-select" name="time" required>
                                <option value="">Please select a time</option>
                                <option value="morning">Morning</option>
                                <option value="evening">Evening</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Attachment</label>
                            <input name="attachment" type="file" class="form-control" accept=".png, .jpg, .jpeg, .pdf, .doc">
                            <div id="emailHelp" class="form-text">Support Extenstions: PDF, DOC, JPG, JPEG, & PNG</div>
                        </div>

                        <div class="form-group">
                            <button id="submit_booking" type="submit" class="btn btn-blue btn-sm">
                                <i class="fa fa-comment-alt"></i>
                                <span>Submit Booking</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>