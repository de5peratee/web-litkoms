import $ from 'jquery';

$(document).ready(function () {
    let currentComicId = null;
    let isDislike = false;

    // Handle tab clicks
    $('.submission-action-tab').on('click', function () {
        const $tab = $(this);
        const $submissionItem = $tab.closest('.submission-item');
        isDislike = $tab.hasClass('dislike-tab');
        currentComicId = $submissionItem.data('comic-id'); // Assuming comic ID is stored in data-comic-id

        // Toggle active tab
        $submissionItem.find('.submission-action-tab').removeClass('active-submission-tab');
        $tab.addClass('active-submission-tab');

        // Update icons
        $submissionItem.find('.submission-action-tab').each(function () {
            const $img = $(this).find('img');
            const src = $img.attr('src');
            const baseNameMatch = src.match(/([^/]+)-(?:primary|white)\.svg$/);
            if (baseNameMatch) {
                const baseName = baseNameMatch[1];
                const newSuffix = $(this).hasClass('active-submission-tab') ? 'white' : 'primary';
                $img.attr('src', `/images/icons/${baseName}-${newSuffix}.svg`);
            }
        });

        // Show/hide comment field based on tab
        const $commentField = $('#edit-comic-description').closest('.lit-field');
        $commentField.toggle(isDislike);

        // Update modal title and button text
        $('#delete-catalog-modal-title').text(isDislike ? 'Отклонить заявку' : 'Принять заявку');
        $('.primary-btn').text(isDislike ? 'Да, отклонить' : 'Да, принять');

        // Update form action
        $('#review-catalog-form').attr('action', `/editor/comics/${currentComicId}/${isDislike ? 'reject' : 'accept'}`);

        // Show modal
        $('#review-catalog-modal').addClass('show');
    });

    // Close modal on cancel button
    $('#cancel-delete-catalog').on('click', function () {
        closeModal();
    });

    // Close modal on close icon
    $('#delete-catalog-modal-close').on('click', function () {
        closeModal();
    });

    // Close modal when clicking outside
    $('#review-catalog-modal').on('click', function (e) {
        if ($(e.target).is('#review-catalog-modal')) {
            closeModal();
        }
    });

    // Handle form submission
    $('#review-catalog-form').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function (response) {
                closeModal();
                // Remove the submission item
                $(`.submission-item[data-comic-id="${currentComicId}"]`).remove();
            },
            error: function (xhr) {
                const errorMessage = xhr.responseJSON?.message || 'Произошла ошибка';
                $('#review-submission-error').text(errorMessage).addClass('error');
            }
        });
    });

    // Function to close modal and reset state
    function closeModal() {
        $('#review-catalog-modal').removeClass('show');
        $('.submission-action-tab').removeClass('active-submission-tab');
        $('.submission-action-tab img').each(function () {
            const src = $(this).attr('src');
            const baseNameMatch = src.match(/([^/]+)-(?:primary|white)\.svg$/);
            if (baseNameMatch) {
                const baseName = baseNameMatch[1];
                $(this).attr('src', `/images/icons/${baseName}-primary.svg`);
            }
        });
        $('#edit-comic-description').val('');
        $('#review-submission-error').text('').removeClass('error');
        // $('.primary-btn').text('Подтвердить выбор');
        currentComicId = null;
        isDislike = false;
    }
});
