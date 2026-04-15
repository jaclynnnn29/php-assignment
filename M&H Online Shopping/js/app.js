// ============================================================================
// Global Window Listeners
// ============================================================================

// Prevent browser from opening files dropped outside the zone
$(window).on('dragover drop', e => e.preventDefault());

$(() => {

    // ============================================================================
    // 1. General Form Helpers
    // ============================================================================

    // Autofocus logic
    $('form :input:not(button):first').focus();
    $('.err:first').prev().focus();
    $('.err:first').prev().find(':input:first').focus();
    
    // Confirmation message
    $('[data-confirm]').on('click', e => {
        const text = e.target.dataset.confirm || 'Are you sure?';
        if (!confirm(text)) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    });

    // Initiate GET request
    $('[data-get]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.get;
        location = url || location;
    });

    // Initiate POST request
    $('[data-post]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.post;
        const f = $('<form>').appendTo(document.body)[0];
        f.method = 'POST';
        f.action = url || location;
        f.submit();
    });

    // Reset form
    $('[type=reset]').on('click', e => {
        e.preventDefault();
        location = location;
    });

    // Auto uppercase
    $('[data-upper]').on('input', e => {
        const a = e.target.selectionStart;
        const b = e.target.selectionEnd;
        e.target.value = e.target.value.toUpperCase();
        e.target.setSelectionRange(a, b);
    });

    // Traditional Photo preview (ONLY for label.upload styles)
    $('label.upload input[type=file]').on('change', e => {
        const f = e.target.files[0];
        const img = $(e.target).siblings('img')[0];

        if (!img) return;

        img.dataset.src ??= img.src;

        if (f?.type.startsWith('image/')) {
            img.src = URL.createObjectURL(f);
        }
        else {
            img.src = img.dataset.src;
            e.target.value = '';
        }
    });

    // ============================================================================
    // 2. Drag-and-Drop Photo Upload Logic
    // ============================================================================
    
    const dropZone = $('#drop-zone');
    const fileInput = $('#photo-input');
    const preview = $('#img-preview');

    // Only run if these elements exist on the page
    if (dropZone.length && fileInput.length) {

        // Click to browse
        dropZone.on('click', () => fileInput.click());

        // Dragover visual effect
        dropZone.on('dragover', (e) => {
            e.preventDefault();
            dropZone.addClass('hover');
        });

        dropZone.on('dragleave', () => dropZone.removeClass('hover'));

        // Handle the Drop Event
        dropZone.on('drop', (e) => {
            e.preventDefault();
            dropZone.removeClass('hover');

            // Access the file via originalEvent for jQuery
            const files = e.originalEvent.dataTransfer.files;

            if (files.length > 0 && files[0].type.startsWith('image/')) {
                // Assign the dropped file to the hidden input
                fileInput[0].files = files; 
                
                // Show local preview using FileReader
                const reader = new FileReader();
                reader.onload = (event) => {
                    preview.attr('src', event.target.result).show();
                };
                reader.readAsDataURL(files[0]);
            }
        });

        // Handle manual browse selection preview
        fileInput.on('change', () => {
            const file = fileInput[0].files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    preview.attr('src', event.target.result).show();
                };
                reader.readAsDataURL(file);
            }
        });
    }
});