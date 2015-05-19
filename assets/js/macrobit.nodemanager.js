jQuery(function ($) {

    // Get and hide the options
    var nodeOptions, nodeShowClass;

    nodeOptions = $('.foodcatalog-nodes-type').hide();

    // Watch for changes
    $('.foodcatalog-nodes-type-controller').on('change', 'select', function (event) {
        nodeOptions.hide();
        setNodeType($(this).val());
        nodeOptions.filter(nodeShowClass).show();
    });

    // Set the initial state
    setNodeType($('.foodcatalog-nodes-type-controller select').trigger('change'));

    /**
     * Set the class to filter by
     * @param selectedValue
     */
    function setNodeType(selectedValue) {
        nodeShowClass = '.type-' + selectedValue;
    }
});