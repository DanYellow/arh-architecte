$(function () {
    $projectImagesInputContainer = $("#Project_projectImages");

    const initSortable = () => {
        // $projectImagesInputContainer.sortable( "destroy" );
        $projectImagesInputContainer.sortable({
            axis: "y",
            cursor: "move",
            handle: ".handle",
            opacity: 0.75,
            deactivate: () => {
                $('[data-input-position]').each((index, el) => {
                    $(el).val(index)
                })
            },
        })
    }

    initSortable()

    $('.field-collection-add-button').on('click', () => {
        initSortable()
        $projectImagesInputContainer.sortable( "refreshPositions" )
        $projectImagesInputContainer.sortable( "refresh" )
    })
});
