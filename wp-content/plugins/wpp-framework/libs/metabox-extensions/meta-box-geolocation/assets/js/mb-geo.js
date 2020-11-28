/**
 * Meta Box Geolocation
 *
 * @version 1.1
 * @author tanng <tan@binaty.org>
 */
(function ($) {

    'use strict';

    var Location = {

        /**
         * Store instances of google.maps.places.Autocomplete
         *
         * @var Array
         */
        autoComplete: [],

        /**
         * Store Geolocation configs
         *
         * @var Array
         */
        dataGeo: null,

        /**
         * Store selected place
         */
        place: {},

        /**
         * Constructor method. Set the config for auto complete and run Auto complete
         *
         * @return void
         */
        init: function () {
            this.dataGeo = this.getDataGeo();
            // Only run auto complete when data-geo is set
            if (typeof this.dataGeo != 'undefined')
                this.initAutoComplete();
        },

        /**
         * Get data geo config
         *
         * @return Array
         */
        getDataGeo: function () {
            if (this.dataGeo != null)
                return this.dataGeo;

            return $('.data-geo').data('geo');
        },

        /**
         * Get auto complete field. Which is text field and contains `address` in their id
         *
         * @return Array
         */
        getAutoCompleteField: function() {
            var autoCompleteFields = [];
            
            $('.rwmb-input input[name*=address]').each(function () {
                autoCompleteFields.push($(this).attr('name'));
            });

            return autoCompleteFields;
        },

        /**
         * Setup the auto complete and run
         *
         * @return void
         */
        initAutoComplete: function () {

            var autoCompletes = this.getAutoCompleteField(),
                // Create alias of `this` keyword to use inside other functions
                _this = this;

            // Loop through auto complete fields and setup
            $.each(autoCompletes, function (i, autoComplete) {

                var autoCompleteSelector = $("input[name='" +  autoComplete + "']");

                // For each auto complete field. Create and instance of google.maps.places.Autocomplete
                _this.autoComplete[i] = new google.maps.places.Autocomplete(
                    (autoCompleteSelector[0]), // = document.getElementById
                    _this.dataGeo
                );

                // When user select a place in drop down, bind data to related fields
                _this.autoComplete[i].addListener('place_changed', function()
                {
                    // Trigger for Map and other addons
                    autoCompleteSelector.trigger('selected_address');

                    var place = this.getPlace();

                    if (typeof place != 'undefined') {

                        // If auto complete field and related field inside group. Only populate data inside that
                        // group. And vice versa.
                        var isGroup = autoCompleteSelector.parents().hasClass('rwmb-group-clone'),

                            $scope = isGroup ? autoCompleteSelector.parents('.rwmb-group-clone') : autoCompleteSelector.parents('.rwmb-meta-box'),

                            $elements = $scope.find('.rwmb-geo-binding');

                        $elements.each(function () {
                            // What data is prepared to bind to that field
                            var dataBinding = $(this).data('binding'),
                                // What is that data's value
                                fieldValue = Location.getFieldData(dataBinding, place);

                            // If value is set. Then populate to related field.
                            if (dataBinding != 'address' && typeof fieldValue != 'undefined')
                                $(this).siblings('.rwmb-input').children().val(fieldValue);
                        });
                    }
                });
            });
        },

        /**
         * Get value of a binding field
         *
         * @param String type
         * @param Object place
         * @returns String
         */
        getFieldData: function (type, place) {

            // If field is not in address_component then try to find them in another place
            if (['formatted_address', 'id', 'name', 'place_id', 'reference', 'url', 'vicinity'].indexOf(type) > -1
            && typeof place != 'undefined' && typeof place[type] != 'undefined') {
                return place[type];
            }

            if (type === 'lat')
                return place.geometry.location.lat();

            if (type === 'lng')
                return place.geometry.location.lng();

            if (type === 'geometry')
                return place.geometry.location.lat() + ',' + place.geometry.location.lng();

            var val = '';

            // We also allows users merge data. For example: `shortname:country + ' ' + postal_code`
            // The code in two `if` statements below to do that
            if (type.indexOf('+') > -1)
                type = type.split('+');

            if ($.isArray(type)) {

                $.each(type, function (i, field) {
                    field = field.trim();

                    if (field.indexOf("'") > -1 || field.indexOf('"') > -1) {
                        field = field.replace(/['"]+/g, '');
                        val += field;
                    }
                    else {
                        val += Location.getFieldData(field, place);
                    }
                });

                return val;
            }
            else {
                // Find value in `address_components`
                $.each(place.address_components, function (index, component) {
                    var longName = true,
                        fieldType = type;

                    if (type.indexOf('short:') > -1) {
                        longName = false;
                        fieldType = type.replace('short:', '');
                    }

                    if (component.types.indexOf(fieldType) > -1) {
                        val = ( longName ) ? component.long_name : component.short_name;
                        // Stop the function right after val has found
                        return false;
                    }
                });
                return val;
            }
        }
    };

    Location.init();

    // Handle group clone event.
    $('#wpbody').on('clone_completed', function () 
    {
        Location.init();
    });

})(jQuery);