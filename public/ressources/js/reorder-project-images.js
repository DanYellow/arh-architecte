$(function () {
    $projectImagesInputContainer = $("#Project_projectImages");

    const resetInputPosition = () => {
        $('[data-input-position]').each((index, el) => {
            $(el).val(index)
        })
    }

    resetInputPosition()

    const initSortable = () => {
        // $projectImagesInputContainer.sortable( "destroy" );
        $projectImagesInputContainer.sortable({
            axis: "y",
            cursor: "move",
            handle: ".handle",
            opacity: 0.75,
            deactivate: () => {
                resetInputPosition();
            },
        })
    }

    initSortable()

    $('.field-collection-add-button').on('click', () => {
        resetInputPosition();
    })
});
