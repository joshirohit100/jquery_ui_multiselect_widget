(function($) {
    /**
     * Initialization
     */
    Drupal.behaviors.jquery_ui_multiselect_widget = {
        /**
         * Run Drupal module JS initialization.
         *
         * @param context
         * @param settings
         */
        attach: function(context, settings) {
            // Only select list which support multiple selection.
            var filter = 'select[multiple=multiple]';
            var elements = $(filter, context);
            elements.each(function() {
                // Item with multiple attribute name like 'item_name[]',
                // so, just remove the last two brackets.
                var name = $(this).context.name;
                name = name.substring(0, name.length - 2);
                if (settings.jquery_ui_multiselect_widget[name]) {
                    var multiSelectFilter = $(this).multiselect({
                        header: !!settings.jquery_ui_multiselect_widget[name].header,
                        click: function(e){
                            var cardinality = settings.jquery_ui_multiselect_widget[name].cardinality;

                            // If cardinality is limited, then limit checked checkboxes upto cardinality.
                            if( cardinality !== -1 && $(this).multiselect("widget").find("input:checked").length > cardinality ){
                                return false;
                            }
                        }
                    });

                    // Show the filter.
                    if (!!settings.jquery_ui_multiselect_widget[name].filter) {
                        multiSelectFilter.multiselectfilter({
                            placeholder: settings.jquery_ui_multiselect_widget[name].filter_placeholder,
                        });
                    }
                }
            });
        }
    };
})(jQuery);
