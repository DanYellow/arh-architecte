$(function () {
    $("#Project_projectImages").sortable({
        axis: "y",
        cursor: "move",
        // handle: ".handle",
        opacity: 0.5,
        deactivate: function( event, ui ) {
            // const newIndex = ui.item.index()
            // ui.item.find('[data-input-position]').val(newIndex)
            $('[data-input-position]').each((index, el) => {
                $(el).val(index)
            })
        }
    })
});
