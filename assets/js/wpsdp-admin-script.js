jQuery(document).ready(function ($) {
    let selectArgs = {
        allowClear: true,
        tags: "true",
        minimumResultsForSearch: Infinity
    }
    jQuery('.wpsdp-category-selection').select2(selectArgs);
    jQuery('.wpsdp-tags-selection').select2(selectArgs);

    jQuery('.wpsdp-category-selection, .wpsdp-tags-selection').on('select2:opening select2:closing', function( event ) {
        var $searchfield = $(this).parent().find('.select2-search__field');
        $searchfield.prop('disabled', true);
    });
});
