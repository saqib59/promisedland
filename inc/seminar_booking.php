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

<div class="modal fade shd_modal" id="seminarModal" tabindex="-1" aria-labelledby="seminarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Seminar Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="course_thread">
                    <div class="course_thread-alert"></div>
                    <div class="seminar_details">
                        <table>
                            <tr>
                                <td>Subject</td>
                                <td id="subject"></td>
                            </tr>
                            <tr>
                                <td>Date</td>
                                <td id="date"></td>
                            </tr>
                            <tr>
                                <td>Location</td>
                                <td id="location"></td>
                            </tr>
                            <tr>
                                <td>Method</td>
                                <td id="method"></td>
                            </tr>
                        </table>
                    </div>
                    <form id="seminar_booking" action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" autocomplete="off">
                        <input name="seminar_id" type="hidden" value="">
                        <input name="user_id" type="hidden" value="<?= isset($_SESSION['user']) ? $_SESSION['user'] : ''; ?>">
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