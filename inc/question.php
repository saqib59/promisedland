<div class="modal fade shd_modal" id="threadModal" tabindex="-1" aria-labelledby="threadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Neue Frage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="course_thread">
                    <div class="course_thread-alert"></div>

                    <form id="faq_question" action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" autocomplete="off">
                        <input name="course_id" type="hidden" value="<?= isset($course['id']) ? $course['id'] : '' ?>">
                        <input name="course_slug" type="hidden" value="<?= isset($course_slug) ? $course_slug : '' ?>">
                        <div class="form-group">
                            <label>Frage Titel</label>
                            <input name="title" type="text" class="form-control" placeholder=" Frage Titel">
                        </div>
                        <div class="form-group">
                            <label>Frage</label>
                            <textarea id="mce_editor" name="question" class="form-control mce_editor" placeholder="Stelle hier deine Frage..."></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-blue btn-sm">
                                <i class="fa fa-comment-alt"></i>
                                <span>Beitrag senden</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>