(function ($) {

    $(document).ready(function () {

        fnListDataTable();

        // Review notice — "Review" button: dismiss permanently after clicking
        $(document).on('click', '#iat-review-btn-review', function () {
            var nonce = $(this).data('nonce');
            $.post(iatObj.ajaxUrl, { action: 'iat_review_dismiss', nonce: nonce });
        });

        // Review notice — "Remind me later" button
        $(document).on('click', '#iat-review-btn-remind', function () {
            var nonce = $(this).data('nonce');
            $.post(iatObj.ajaxUrl, { action: 'iat_review_remind_later', nonce: nonce }, function () {
                $('#iat-review-notice').fadeOut(300);
            });
        });

        // Review notice — "Do not show again" button
        $(document).on('click', '#iat-review-btn-dismiss', function () {
            var nonce = $(this).data('nonce');
            $.post(iatObj.ajaxUrl, { action: 'iat_review_dismiss', nonce: nonce }, function () {
                $('#iat-review-notice').fadeOut(300);
            });
        });

        // Copy URL to clipboard
        $(document).on('click', '.iat-copy-url', function (e) {
            e.preventDefault();
            var url = $(this).data('url');
            var $copiedMsg = $(this).closest('.iat-url').find('.iat-url-copied');
            if (navigator.clipboard) {
                navigator.clipboard.writeText(url).then(function () {
                    $copiedMsg.text('URL copied!').fadeIn(200);
                    setTimeout(() => $copiedMsg.fadeOut(400), 1200);
                });
            } else {
                var tempInput = $('<input>').val(url);
                $('body').append(tempInput);
                tempInput.select();
                document.execCommand('copy');
                tempInput.remove();
                $copiedMsg.text('URL copied!').fadeIn(200);
                setTimeout(() => $copiedMsg.fadeOut(400), 1200);
            }
        });

    });

    function fnListDataTable() {
        const currentPage = iatObj.currentPage;
        let columns;
        if (currentPage === 'iat-with-alt-text') {
            columns = [
                { data: 'checkbox', orderable: false, width: '2%' },
                { data: 'image', orderable: false, width: '5%' },
                { data: 'title', width: '15%' },
                { data: 'url', width: '20%' },
                { data: 'alt_text', orderable: false, width: '30%' },
                { data: 'size', width: '5%' },
                { data: 'date', width: '10%' },
                { data: 'action', orderable: false, width: '2%' }
            ];
        } else if (currentPage === 'iat-without-alt-text') {
            columns = [
                { data: 'checkbox', orderable: false, width: '5%' },
                { data: 'image', orderable: false, width: '5%' },
                { data: 'title', width: '20%' },
                { data: 'url', width: '20%' },
                { data: 'alt_text', orderable: false, width: '30%' },
                { data: 'size', width: '5%' },
                { data: 'date', width: '10%' },
                { data: 'action', orderable: false, width: '' }
            ];
        }
        $('.iat-datatable').DataTable({
            destroy: true,
            paging: true,
            processing: true,
            serverSide: true,
            pageLength: 10,
            ordering: false,
            searching: true,
            lengthChange: true,
            responsive: true,
            stateSave: true,
            ajax: {
                type: 'POST',
                url: iatObj.ajaxUrl,
                data: function (d) {
                    d.action = 'iat_get_list';
                    d.nonce = iatObj.nonce;
                    d.type = currentPage;

                    return d;
                },
                dataSrc: function (json) {
                    return json.data;
                }
            },
            columns: columns
        });

        // Store DataTable instance globally
        window.IATDataTable = $('.iat-datatable').DataTable();
    }

    // Expose fnListDataTable globally so it can be called from other scripts
    window.fnListDataTable = fnListDataTable;

})(jQuery);
