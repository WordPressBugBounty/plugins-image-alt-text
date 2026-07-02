(function ($) {
    $(document).ready(function () {
        // Variables
        var selectedItems = [];
        var isProcessing = false;
        // Suppress flag to avoid re-entering the select2 change handler
        // when we programmatically update the select value.
        var suppressSelectChange = false;

        // Disable bulk actions until items are selected (BEFORE Select2 initialization)
        $('#iat-bulk-action-select').prop('disabled', true);
        $('#iat-bulk-action-process-btn').prop('disabled', true);

        // Initialize Select2 for bulk action dropdown AFTER disabling
        $('#iat-bulk-action-select').select2({
            width: '100%',
            placeholder: iatObj.i18n.chooseAction,
            allowClear: true,
            minimumResultsForSearch: Infinity
        });

        // Checkbox selection
        $(document).on('change', 'input[type="checkbox"].iat-bulk-select', function () {
            var postId = parseInt($(this).data('post-id'));
            var isChecked = $(this).is(':checked');
            if (isChecked) {
                if (selectedItems.indexOf(postId) === -1) {
                    selectedItems.push(postId);
                }
            } else {
                selectedItems = selectedItems.filter(function (id) {
                    return id !== postId;
                });
            }
            updateUI();
        });

        // Select all checkbox
        $(document).on('change', '#iat-select-all', function () {
            var isChecked = $(this).is(':checked');
            var checkboxes = $('input[type="checkbox"].iat-bulk-select');
            checkboxes.prop('checked', isChecked);
            if (isChecked) {
                selectedItems = [];
                checkboxes.each(function () {
                    var postId = parseInt($(this).data('post-id'));
                    if (postId && selectedItems.indexOf(postId) === -1) {
                        selectedItems.push(postId);
                    }
                });
            } else {
                selectedItems = [];
            }
            updateUI();
        });

        // Bulk action process button
        $(document).on('click', '#iat-bulk-action-process-btn', function (e) {
            e.preventDefault();
            if (isProcessing) return;
            var actionType = $('#iat-bulk-action-select').val();
            if (!actionType) {
                showStatusMessage('error', iatObj.i18n.selectActionFirst);
                return;
            }
            if (selectedItems.length === 0) {
                showStatusMessage('error', iatObj.i18n.selectAtLeastOne);
                return;
            }
            // SweetAlert confirmation
            function ensureSweetAlert(callback) {
                if (typeof Swal !== 'undefined') {
                    callback();
                    return;
                }
                // SweetAlert2 is already enqueued, so do nothing
                // If not found, you may want to show an error or fallback
            }
            ensureSweetAlert(function () {
                var actionNames = {
                    'copy_title': iatObj.i18n.actionCopyTitle,
                    'copy_filename': iatObj.i18n.actionCopyFilename
                };
                var noticeMessages = {
                    'copy_title': [
                        { type: 'warning', icon: '<i class="fas fa-exclamation-triangle"></i>', text: iatObj.i18n.noticeNoTitleSkip },
                        { type: 'info', icon: '<i class="fas fa-info-circle"></i>', text: iatObj.i18n.noticeTitleSameSkip }
                    ],
                    'copy_filename': [
                        { type: 'warning', icon: '<i class="fas fa-exclamation-triangle"></i>', text: iatObj.i18n.noticeNoUrlSkip },
                        { type: 'info', icon: '<i class="fas fa-info-circle"></i>', text: iatObj.i18n.noticeFileSameSkip }
                    ]
                };
                var actionLabel = iatObj.i18n.confirmBulkFor
                    .replace('%1$s', actionNames[actionType])
                    .replace('%2$d', selectedItems.length);
                var html = '<div class="iat-swal-content">' +
                    '<div class="iat-swal-action-title">' +
                    '<span class="dashicons dashicons-admin-tools"></span>' +
                    '<span>' + actionLabel + '</span>' +
                    '</div>' +
                    '<div class="iat-swal-notices">';
                noticeMessages[actionType].forEach(function (msg) {
                    var iconClass = msg.type === 'warning' ? 'dashicons-warning' : 'dashicons-info';
                    html += '<div class="iat-swal-notice iat-swal-notice-' + msg.type + '">' +
                        '<span class="dashicons ' + iconClass + '"></span>' +
                        '<span>' + msg.text + '</span>' +
                        '</div>';
                });
                html += '</div></div>';
                Swal.fire({
                    title: iatObj.i18n.confirmBulkTitle,
                    html: html,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#764ba2',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<span>' + iatObj.i18n.confirmYes + '</span>',
                    cancelButtonText: '<span>' + iatObj.i18n.cancel + '</span>',
                    customClass: {
                        popup: 'iat-swal-popup',
                        confirmButton: 'iat-swal-confirm',
                        cancelButton: 'iat-swal-cancel'
                    },
                    buttonsStyling: true,
                    width: '420px',
                    padding: '20px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        processBulkAction(actionType, selectedItems);
                    }
                });
            });
        });

        // UI update
        function updateUI() {
            var hasSelection = selectedItems.length > 0;
            var hasAction = $('#iat-bulk-action-select').val() !== '';
            $('#iat-bulk-action-selection-count').text(selectedItems.length + '\u00A0');
            $('#iat-bulk-action-select').prop('disabled', !hasSelection);
            $('#iat-bulk-action-process-btn').prop('disabled', !hasSelection || !hasAction || isProcessing);
            if (!hasSelection) {
                var $select = $('#iat-bulk-action-select');
                if ($select.val() !== '') {
                    suppressSelectChange = true;
                    $select.val('');
                    if (typeof $.fn.select2 !== 'undefined') {
                        $select.trigger('change');
                    }
                    suppressSelectChange = false;
                } else if (typeof $.fn.select2 !== 'undefined') {
                    suppressSelectChange = true;
                    $select.val(null).trigger('change');
                    suppressSelectChange = false;
                }
                hideStatusMessage();
            }
        }
        $(document).on('change', '#iat-bulk-action-select', function () {
            if (suppressSelectChange) return;
            updateUI();
        });

        // Bulk action AJAX
        function processBulkAction(actionType, postIds) {
            isProcessing = true;
            updateUI();
            showProcessingStatus(actionType, postIds.length);
            $.ajax({
                url: iatObj.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'iat_bulk_process_action',
                    action_type: actionType,
                    post_ids: postIds,
                    nonce: iatObj.nonce
                },
                success: function (response) {
                    isProcessing = false;
                    hideProcessingStatus();
                    if (response.success) {
                        handleProcessingSuccess(response.data);
                        clearSelections();
                        if (typeof $('.iat-datatable').DataTable === 'function') {
                            $('.iat-datatable').DataTable().ajax.reload();
                        }
                    } else {
                        handleProcessingError(response.data.message || iatObj.i18n.processingFailed);
                    }
                    updateUI();
                },
                error: function (xhr, status, error) {
                    isProcessing = false;
                    hideProcessingStatus();
                    handleProcessingError(iatObj.i18n.networkError);
                    updateUI();
                }
            });
        }
        function showProcessingStatus(actionType, itemCount) {
            var actionNames = {
                'copy_title': 'Copying titles to alt text',
                'copy_filename': 'Copying filenames to alt text'
            };

            // Don't show processing message for any action - only show button loader
            // var message = actionNames[actionType] + ' for ' + itemCount + ' image(s)...';
            // showStatusMessage('processing', message);

            // Always show button loader for all actions
            $('#iat-bulk-action-process-btn .iat-bulk-action-btn-loader').show();
            $('#iat-bulk-action-process-btn .iat-bulk-action-btn-text').text(iatObj.i18n.processing);
            $('#iat-bulk-action-process-btn').addClass('processing').prop('disabled', true);
        }
        function hideProcessingStatus() {
            $('#iat-bulk-action-process-btn .iat-bulk-action-btn-loader').hide();
            $('#iat-bulk-action-process-btn .iat-bulk-action-btn-text').text(iatObj.i18n.process);
            $('#iat-bulk-action-process-btn').removeClass('processing');
        }
        function handleProcessingSuccess(data) {
            $('.iat-bulk-action-status-area').empty();
            var msg = '<div class="iat-bulk-action-status-msg success">' +
                '<i class="fas fa-check-circle"></i> '
                + '<span>' + iatObj.i18n.bulkSuccess + '</span>' +
                '</div>';
            $('.iat-bulk-action-status-area').append(msg);
            if (typeof $('.iat-datatable').DataTable === 'function') {
                $('.iat-datatable').DataTable().ajax.reload();
            }
            $('#iat-bulk-action-select').val('');
            if (typeof $.fn.select2 !== 'undefined') {
                $('#iat-bulk-action-select').trigger('change');
            }
            setTimeout(function () {
                $('.iat-bulk-action-status-area').empty();
            }, 2000);
        }
        function handleProcessingError(message) {
            $('.iat-bulk-action-status-area').empty();
            var msg = '<div class="iat-bulk-action-status-msg error">' +
                '<i class="fas fa-times-circle"></i> '
                + '<span>' + iatObj.i18n.bulkError.replace('%s', message) + '</span>' +
                '</div>';
            $('.iat-bulk-action-status-area').append(msg);
            setTimeout(function () {
                $('.iat-bulk-action-status-area').empty();
            }, 3000);
        }
        function clearSelections() {
            selectedItems = [];
            $('input[type="checkbox"].iat-bulk-select').prop('checked', false);
            $('#iat-select-all').prop('checked', false);
            var $select = $('#iat-bulk-action-select');
            if ($select.val() !== '') {
                suppressSelectChange = true;
                $select.val('');
                if (typeof $.fn.select2 !== 'undefined') {
                    $select.trigger('change');
                }
                suppressSelectChange = false;
            } else if (typeof $.fn.select2 !== 'undefined') {
                suppressSelectChange = true;
                $select.val(null).trigger('change');
                suppressSelectChange = false;
            }
        }
        function showStatusMessage(type, message, duration) {
            duration = duration || 5000;
            var $statusMsg = $('#iat-bulk-action-status');
            var $statusText = $('.iat-bulk-action-status-text');
            var $statusIcon = $statusMsg.find('.dashicons');
            $statusMsg.removeClass('hidden success error warning info processing');
            var iconClass = 'dashicons-info';
            switch (type) {
                case 'success':
                    iconClass = 'dashicons-yes-alt';
                    break;
                case 'error':
                    iconClass = 'dashicons-warning';
                    break;
                case 'warning':
                    iconClass = 'dashicons-flag';
                    break;
                case 'processing':
                    iconClass = 'dashicons-update';
                    break;
            }
            $statusIcon.removeClass().addClass('dashicons ' + iconClass);
            $statusText.text(message);
            $statusMsg.addClass(type).removeClass('hidden').show();
            if (type !== 'processing') {
                setTimeout(function () {
                    hideStatusMessage();
                }, duration);
            }
        }
        function hideStatusMessage() {
            $('#iat-bulk-action-status').addClass('hidden');
        }
    });
})(jQuery)