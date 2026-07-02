(function ($) {

    $(document).ready(function () {

        // Copy title to alt text - hide button, show processing, then show message
        $(document).on('click', '.iat-copy-title-to-alt', function (e) {
            e.preventDefault();
            var $btn = $(this);
            var postId = $btn.data('post_id');
            var title = $btn.data('title');
            var $row = $btn.closest('tr');
            var $altTextInput = $row.find('.iat-alt-text-input');
            var $altTextDisplay = $row.find('.iat-alt-text-display');
            var $successMsg = $btn.siblings('.iat-copy-success');
            var $duplicateMsg = $btn.siblings('.iat-copy-duplicate');

            // Hide messages and button, show processing
            $successMsg.hide();
            $duplicateMsg.hide();
            $btn.hide();

            // Create and show processing message
            var $processingMsg = $('<span class="iat-copy-processing"><span class="dashicons dashicons-update iat-spin"></span> ' + iatObj.i18n.processing + '</span>');
            $btn.after($processingMsg);

            $altTextInput.val(title);
            $.ajax({
                url: iatObj.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'iat_copy_title_to_alt_text',
                    post_id: postId,
                    alt_text: title,
                    nonce: iatObj.nonce
                },
                success: function (response) {
                    // Remove processing message
                    $processingMsg.remove();

                    if (response.success && response.data) {
                        if (response.data.already_same) {
                            $duplicateMsg.text(response.data.message || iatObj.i18n.alreadySame).fadeIn();
                            // Keep message visible, don't auto-hide
                            $btn.show();
                        } else {
                            $altTextDisplay.text(response.data.alt_text);
                            $successMsg.text(response.data.message || iatObj.i18n.copied).fadeIn();
                            // Keep message visible, don't auto-hide
                            $btn.show();
                        }
                        // Update smart editor input field if it exists
                        var $smartInput = $row.find('.iat-smart-alt-input');
                        if ($smartInput.length) {
                            $smartInput.val(response.data.alt_text).data('original', response.data.alt_text);
                            var $container = $smartInput.closest('.iat-input-container');
                            $container.removeClass('has-changes').addClass('saved');
                            $container.find('.iat-save-btn').prop('disabled', true);
                            $container.find('.iat-reset-btn').hide();
                        }
                    } else {
                        $successMsg.text((response.data && response.data.message) || iatObj.i18n.errorSaving).fadeIn();
                        // Keep error message visible
                        $btn.show();
                    }
                },
                error: function () {
                    $processingMsg.remove();
                    //$successMsg.text('Server error!').fadeIn();
                    $successMsg
                        .text(iatObj.i18n.networkError)
                        .css({
                            background: '#fee2e2',
                            color: '#dc2626',
                            border: '1px solid #fca5a5'
                        })
                        .fadeIn();
                    // Keep error message visible
                    $btn.show();
                }
            });
        });

        // Copy filename to alt text - hide button, show processing, then show message
        $(document).on('click', '.iat-copy-filename-to-alt', function (e) {
            e.preventDefault();
            var $btn = $(this);
            var postId = $btn.data('post_id');
            var filename = $btn.data('filename');
            var $row = $btn.closest('tr');
            var $altTextInput = $row.find('.iat-alt-text-input');
            var $altTextDisplay = $row.find('.iat-alt-text-display');
            var $successMsg = $btn.siblings('.iat-update-success');
            var $duplicateMsg = $btn.siblings('.iat-update-duplicate');

            // Hide messages and button, show processing
            $successMsg.hide();
            $duplicateMsg.hide();
            $btn.hide();

            // Create and show processing message
            var $processingMsg = $('<span class="iat-copy-processing"><span class="dashicons dashicons-update iat-spin"></span> ' + iatObj.i18n.processing + '</span>');
            $btn.after($processingMsg);

            $altTextInput.val(filename);
            $.ajax({
                url: iatObj.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'iat_copy_filename_to_alt_text',
                    post_id: postId,
                    nonce: iatObj.nonce
                },
                success: function (response) {
                    // Remove processing message
                    $processingMsg.remove();

                    if (response.success && response.data) {
                        if (response.data.already_same) {
                            $duplicateMsg.text(response.data.message || iatObj.i18n.alreadySame).fadeIn();
                            // Keep message visible, don't auto-hide
                            $btn.show();
                        } else {
                            $altTextDisplay.text(response.data.alt_text);
                            $successMsg.text(response.data.message || iatObj.i18n.added).fadeIn();
                            // Keep message visible, don't auto-hide
                            $btn.show();
                        }
                        // Update smart editor input field if it exists
                        var $smartInput = $row.find('.iat-smart-alt-input');
                        if ($smartInput.length) {
                            $smartInput.val(response.data.alt_text).data('original', response.data.alt_text);
                            var $container = $smartInput.closest('.iat-input-container');
                            $container.removeClass('has-changes').addClass('saved');
                            $container.find('.iat-save-btn').prop('disabled', true);
                            $container.find('.iat-reset-btn').hide();
                        }
                    } else {
                        $successMsg.text((response.data && response.data.message) || iatObj.i18n.errorSaving).fadeIn();
                        // Keep error message visible
                        $btn.show();
                    }
                },
                error: function () {
                    $processingMsg.remove();
                    // $successMsg.text('Server error!').fadeIn();
                    $successMsg
                        .text(iatObj.i18n.networkError)
                        .css({
                            background: '#fee2e2',
                            color: '#dc2626',
                            border: '1px solid #fca5a5'
                        })
                        .fadeIn();
                    // Keep error message visible
                    $btn.show();
                }
            });
        });

        // Smart Editor - Input change detection
        $(document).on('input', '.iat-smart-alt-input', function () {
            var input = $(this);
            var container = input.closest('.iat-input-container');
            var originalValue = input.data('original');
            var currentValue = input.val().trim();
            var saveBtn = container.find('.iat-save-btn');
            var resetBtn = container.find('.iat-reset-btn');
            var statusText = input.closest('.iat-smart-alt-editor-wrapper').find('.iat-status-text');

            var hasChanges = currentValue !== originalValue;
            var isEmpty = currentValue.length === 0;

            container.toggleClass('has-changes', hasChanges && !isEmpty)
                .toggleClass('saved', false);

            saveBtn.prop('disabled', isEmpty || !hasChanges)
                .css('opacity', isEmpty ? '0.5' : '1');

            resetBtn.toggle(hasChanges);

            if (hasChanges && statusText.hasClass('success error warning')) {
                statusText.removeClass('success error warning info').hide();
            }
        });

        // Save button handler (simple, all logic/messages from PHP)
        $(document).on('click', '.iat-save-btn', function () {
            var button = $(this);
            var postId = button.data('post_id');
            var container = button.closest('.iat-input-container');
            var input = container.find('.iat-smart-alt-input');
            var statusText = input.closest('.iat-smart-alt-editor-wrapper').find('.iat-status-text');
            var altText = input.val().trim();
            if (button.prop('disabled') || button.hasClass('saving')) return;
            container.removeClass('has-changes').addClass('saving');
            button.addClass('saving').find('.dashicons').removeClass('dashicons-yes-alt').addClass('dashicons-update');
            statusText.removeClass().addClass('iat-status-text info').text(iatObj.i18n.saving).show();
            $.ajax({
                url: iatObj.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'iat_update_alt_text',
                    post_id: postId,
                    alt_text: altText,
                    nonce: iatObj.nonce
                },
                success: function (response) {
                    if (response.success && response.data) {
                        container.removeClass('saving').addClass('saved');
                        input.data('original', altText);
                        button.prop('disabled', true);
                        container.find('.iat-reset-btn').hide();
                        statusText.removeClass().addClass('iat-status-text success').text(response.data.message || iatObj.i18n.saved).show();
                    } else if (response.data && response.data.type === 'duplicate') {
                        container.removeClass('saving').addClass('has-changes');
                        statusText.removeClass().addClass('iat-status-text warning').text(response.data.message || iatObj.i18n.duplicateAlt).show();
                    } else {
                        container.removeClass('saving').addClass('has-changes');
                        statusText.removeClass().addClass('iat-status-text error').text((response.data && response.data.message) || iatObj.i18n.failedSave).show();
                    }
                    button.removeClass('saving').find('.dashicons').removeClass('dashicons-update').addClass('dashicons-yes-alt');
                },
                error: function () {
                    container.removeClass('saving').addClass('has-changes');
                    statusText.removeClass().addClass('iat-status-text error').text(iatObj.i18n.networkErrorConn).show();
                    button.removeClass('saving').find('.dashicons').removeClass('dashicons-update').addClass('dashicons-yes-alt');
                }
            });
        });

        // Reset button handler
        $(document).on('click', '.iat-reset-btn', function () {
            var button = $(this);
            var container = button.closest('.iat-input-container');
            var input = container.find('.iat-smart-alt-input');
            var originalValue = input.data('original');
            var statusText = input.closest('.iat-smart-alt-editor-wrapper').find('.iat-status-text');

            input.val(originalValue);
            container.removeClass('has-changes');
            container.find('.iat-save-btn').prop('disabled', true);
            button.hide();

            if (statusText.hasClass('info')) {
                statusText.removeClass('info').hide();
            }
        });

    });

})(jQuery);
