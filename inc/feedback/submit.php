<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/config.php';
require_once  HOME . '/inc/account/logged.php';
?>

<div class="modal fade shd_modal" id="seminarFeedbackModal" tabindex="-1" aria-labelledby="seminarFeedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Feedback: <span id="seminarFeedbackTitle"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="seminar_feedback">
                    <div class="course_thread-alert"></div>

                    <form id="seminar_feedback" action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" autocomplete="off">
                        <input name="row_type" type="hidden" value="">
                        <input name="row_id" type="hidden" value="">
                        <div class="form-group">
                            <label>Rating</label>
                            <div class="vv-stars">
                                <label>
                                    <input type="radio" name="rating" value="1" />
                                    <span class="icon"><i class="fa fa-star"></i></span>
                                </label>

                                <label>
                                    <input type="radio" name="rating" value="2" />
                                    <span class="icon"><i class="fa fa-star"></i></span>
                                    <span class="icon"><i class="fa fa-star"></i></span>
                                </label>

                                <label>
                                    <input type="radio" name="rating" value="3" />
                                    <span class="icon"><i class="fa fa-star"></i></span>
                                    <span class="icon"><i class="fa fa-star"></i></span>
                                    <span class="icon"><i class="fa fa-star"></i></span>
                                </label>

                                <label>
                                    <input type="radio" name="rating" value="4" />
                                    <span class="icon"><i class="fa fa-star"></i></span>
                                    <span class="icon"><i class="fa fa-star"></i></span>
                                    <span class="icon"><i class="fa fa-star"></i></span>
                                    <span class="icon"><i class="fa fa-star"></i></span>
                                </label>

                                <label>
                                    <input type="radio" name="rating" value="5" />
                                    <span class="icon"><i class="fa fa-star"></i></span>
                                    <span class="icon"><i class="fa fa-star"></i></span>
                                    <span class="icon"><i class="fa fa-star"></i></span>
                                    <span class="icon"><i class="fa fa-star"></i></span>
                                    <span class="icon"><i class="fa fa-star"></i></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Feedback</label>
                            <textarea name="feedback" class="form-control" placeholder="Your Feedback" required></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-blue btn-sm">
                                <i class="fa fa-comment-alt"></i>
                                <span>Submit Feedback</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>