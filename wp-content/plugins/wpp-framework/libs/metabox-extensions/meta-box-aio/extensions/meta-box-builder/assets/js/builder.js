;(function ($, angular, hljs) {
    'use strict';

    // Define module and dependencies
    // All directives store in `directives.js`
    var app = angular.module('Builder', ['builderDirectives', 'tg.dynamicDirective', 'ui.sortable']);

    /**
     * Create array from range. Useful for loop.
     * For example, range | 9 will return array[1..9]
     *
     * @param  int total Range
     * @return array array from range.
     */
    app.filter('range', function () {
        return function (input, total) {
            total = parseInt(total);
            for (var i = 0; i < total; i++)
                input.push(i);

            return input;
        };
    });

    /**
     * Add focus=callback() directive
     *
     * @param  callback Callback function
     * @return callback
     */
    app.factory('focus', function ($rootScope, $timeout) {
        return function (name) {
            $timeout(function () {
                $rootScope.$broadcast('focusOn', name);
            });
        }
    });

    /**
     * Builder Controller. Which contains all method for builder
     * @see https://docs.angularjs.org/guide/controller
     */
    app.controller('BuilderController', function ($scope, $window, focus) {
        /**
         * Default Meta Box value
         *
         * @type {[type]}
         */
        $scope.meta = {
            title: 'Untitled Meta Box',
            id: 'untitled-meta-box',
            post_types: [{
                slug: 'post',
                name: 'Post',
            }],
            context: 'normal',
            priority: 'high',
            status: 'publish',
            autosave: 'false',
            attrs: [],
            fields: [],
            tab_style: 'default',
            tab_wrapper: 'true',
            is_id_modified: false,
            for: 'post_types',
        };

        /**
         * Configs for ui-sortable
         * @type {Object}
         */
        $scope.sortableOptions = {
            connectWith: ".apps-container",
            placeholder: "ui-state-highlight",
            // You cannot move the first tab
            items: 'li:not(.tab:first-child)',
            dropOnEmpty: false
        };

        // Shortcut for access fields
        $scope.fields = $scope.meta.fields;

        // Store current editing item
        $scope.active = {};

        // Store all default field value
        $scope.attrs = {};

        /**
         * When remove item from object. For example. Remove option from select,
         * the current editing field will lost focus. So we have to use this variable
         * to force the builder keep editing that field
         * @type {Boolean}
         */
        $scope.shouldContinue = true;

        /**
         * Store all post types in array so user can select in frontend
         * @type {Array}
         */
        $scope.post_types = [];

        $scope.tabExists = false;
        /**
         * Available fields for Conditional Logic
         *
         * @type {Array}
         * @todo  Write PHP code to list all available fields
         */
        $scope.available_fields = ['post_format', 'title', 'post_category', 'parent_id', 'menu_order', 'post_status'];

        $scope.pane = 'general';

        $scope.searchKeyword = '';

        $scope.group_subfield = [];

        /**
         * Initial method. Run when the page loaded
         *
         * @return void
         */
        $scope.init = function () {
            // When load a saved meta box. Use old collection.
            if (typeof $window.meta != 'undefined')
                $scope.meta = $window.meta;

            $scope.attrs            = $window.attrs;
            $scope.addons           = $window.addons;
            $scope.post_types       = $window.post_types;
            $scope.taxonomies       = $window.taxonomies;
            $scope.settings_pages   = $window.settings_pages;

            if (typeof $scope.meta.fields[0] != 'undefined' && $scope.meta.fields[0].type === 'tab') {
                $scope.tabExists = true;
            }

            $scope.setAvailableFields();
            // $scope.setCategory();
        };

        $scope.replace_slug = function( str, sep ) {
            sep = sep || '-';
            str = str.replace(/^\s+|\s+$/g, ''); // trim
            str = str.toLowerCase();

            // remove accents, swap ñ for n, etc
            var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
            var to   = "aaaaeeeeiiiioooouuuunc------";
            for (var i=0, l=from.length ; i<l ; i++) {
                str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
            }

            str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
            .replace(/\s+/g, sep) // collapse whitespace and replace by -
            .replace(/-+/g, '-'); // collapse dashes

            return str;
        }
        $scope.onchangetitle = function() {
            if ( $scope.meta.is_id_modified != true ) {
                $scope.meta.id = $scope.replace_slug( $scope.meta.title, '-' );
            }
        }
        $scope.setAvailableFields = function () {
            var options_key = '',
                array_field = ['select', 'radio', 'checkbox_list', 'select_advanced'];
            angular.forEach($scope.meta.fields, function (field) {

                if (field.id && $scope.available_fields.indexOf(field.id) < 0) {
                    $scope.available_fields.push( field.id );
                }
                $scope.group_subfield.push( field.id );

                angular.forEach(field.fields, function (field_group) {
                     $scope.group_subfield.push( field_group.id );
                });

                if ( $.inArray( field.type, array_field ) > -1 ) {
                     angular.forEach( field.options, function (field_option) {
                         if ( field_option.key ) {
                           options_key += field_option.key + ':' + field_option.value + '\n';
                         }
                    });

                    if ( options_key !== '' ) {
                        field.options = options_key;
                    }
                }

            });
        };

        $scope.setCategory = function() {
            angular.forEach($scope.meta.fields, function (field) {
                if ( field.taxonomy ) {
                     field.terms = $window.terms[field.taxonomy];
                }
            });
        };

        $scope.getView = function (item) {
            return 'nestable_item.html';
        };

        /**
         * Action when use click on each field on sidebar,
         * add default field value to `meta` collection
         *
         * @param string $type Field Type
         *
         * @return void
         */
        $scope.addField = function ($type) {
            // Must copy to angular object before process
            var field = angular.copy($scope.attrs[$type]),
                count;

            angular.forEach($scope.meta.fields, function (field) {
                if (field.type === 'tab')
                    $scope.tabExists = true;
            });

            if ($type === 'tab' && !$scope.tabExists) {
                $scope.meta.fields.unshift(field);
                $scope.tabExists = true;
            }
            else {
                $scope.meta.fields.push(field);
            }
            if (typeof field.id !== 'undefined')
                field.id = $type + '_' + $scope.meta.fields.length;

            // For autocomplete in Conditional Logic
            // Todo: Remove non-form fields. For example: Tab, Heading, Group...
            $scope.available_fields.push(field.id);
            $scope.group_subfield.push( field.id );
            count = $scope.meta.fields.length - 1;
            if ( 'taxonomy' === $scope.meta.fields[count].type || 'taxonomy_advanced' === $scope.meta.fields[count].type ) {
                $scope.meta.fields[count].terms = $window.terms[$scope.meta.fields[count].taxonomy];
            }
        };

        /**
         * Action when user click on clone button
         *
         * @param  Builder Field $field Field that got clicked
         *
         * @return void
         */
        $scope.cloneField = function ($field) {
            var field = angular.copy($field);
            field.id += "_copy_" + meta.fields.length;

            $scope.meta.fields.push(field);

            // For autocomplete in Conditional Logic
            $scope.available_fields.push(field.id);
            $scope.group_subfield.push( field.id );
        };

        /**
         * Action when user click on trash button.
         * Remove field from Metabox
         *
         * @param  int $index Index of the field
         *
         * @return void
         */
        $scope.removeField = function (field) {

            var tabCount = 0;

            if (field.type === 'tab' && $scope.meta.fields.indexOf(field) === 0) {
                angular.forEach($scope.meta.fields, function (field) {
                    if (field.type === 'tab')
                        tabCount++;
                });

                if (tabCount > 1) {
                    alert('You cannot delete the first tab until the rest are deleted!');
                    return;
                }
            }

            $scope.findAndRemove($scope.meta.fields, field);

            if (tabCount === 1)
                $scope.tabExists = false;

            // Todo: Remove field from autocomplete
        };

        $scope.findAndRemove = function (fields, field) {

            var index = fields.indexOf(field);

            if ( index >= 0 ) {
                fields.splice(index, 1);
            } else {
                angular.forEach(fields, function (f) {
                   if (typeof f.fields != 'undefined')
                       $scope.findAndRemove(f.fields, field);
                });
            }
        };

        /**
         * Set active field for edit
         * @param  Object $field Field object
         * @param  Event $event Event to check
         */
        $scope.editField = function ($field, $event) {
            $scope.active = $field;

            // Todo: When edit field, change conditional logic array
        };

        /**
         * Return to view mode
         * @return void
         */
        $scope.unEdit = function ($event) {
            if ($scope.shouldContinue)
                $scope.active = {};
            else
                $scope.shouldContinue = true;
        };

        /**
         * Toggle Edit Field
         *
         * @param  object $field Meta Box Field
         * @param  Event $event JS Event
         *
         * @return void
         */
        $scope.toggleEdit = function ($field, $event) {
            if ($scope.active == $field)
                $scope.unEdit();
            else
                $scope.editField($field, $event);
        };

        /**
         * Clear all other checked values but current checkbox if is not multiple select or checkboxlist
         *
         * @param  int $index Index of array options
         * @return void
         */
        $scope.toggleSelect = function ($index) {
            if (!( $scope.active.multiple || $scope.active.type === 'checkbox_list' )) {
                angular.forEach($scope.active.options, function (option, index) {
                    if ($index !== index)
                        $scope.active.options[index].selected = false;
                });
            }
        };

        /**
         * Clear all checked values when switching from multiple select to single select
         *
         * @return void
         */
        $scope.toggleMultiple = function () {
            if (!$scope.active.multiple
                && typeof $scope.active.multiple !== 'undefined'
                && $scope.active.options.length > 0
            )
                $scope.toggleSelect(-1); // little trick ;)
        };

        /**
         * When user typing value, auto fill to key if it not set
         *
         * @param  int $index Index of item in options
         * @return void
         */
        $scope.autoFillValue = function ($index) {
            var option = $scope.active.options[$index];

            if (option.value === option.key.substr(0, option.key.length - 1))
                $scope.active.options[$index].value = option.key;
        };

        /**
         * Add empty item to the end of datalist array then focus to it
         */
        $scope.addDatalistItem = function () {
            if (typeof $scope.active.datalist.options == 'undefined')
                $scope.active.datalist.options = [];

            $scope.active.datalist.options.push('');

            var length = $scope.active.datalist.options.length - 1;

            focus($scope.active.id + '_datalist_' + length);
        };

        /**
         * Add Conditional Logic to the given target
         *
         * @param {String} $target Target to add. 'active' or 'meta'
         */
        $scope.addConditionalLogic = function ($target) {
            $target = $target || 'active';

            if (typeof $scope[$target].logic == 'undefined') {
                $scope[$target].logic = {};
                $scope[$target].logic.visibility = 'visible';
                $scope[$target].logic.relation = 'and';
            }

            if (typeof $scope[$target].logic.when == 'undefined')
                $scope[$target].logic.when = [];

            $scope[$target].logic.when.push(['', '=', '']);
        };

        /**
         * Remove datalist item
         * @param  int $index Index of datalist item in array
         * @return void
         */
        $scope.removeDatalistItem = function ($index) {
            $scope.active.datalist.options.splice($index, 1);

            // By default, after remove item, it continue go to $scope.unEdit method.
            // use this to avoid that
            $scope.shouldContinue = false;
        };

        /**
         * Remove Conditional Logic from a given target
         *
         * @param  {Int} $index  Index of Conditional Logic
         * @param  {Boolean} $target 'active' or 'meta'
         *
         * @return {void}
         */
        $scope.removeConditionalLogic = function ($index, $target) {
            $target = $target || 'active';

            $scope[$target].logic.when.splice($index, 1);

            $scope.shouldContinue = false;
        };

        /**
         * Clear unused data from fields
         * @param  object $field Meta box field
         * @return void
         */
        $scope.sanitize = function ($field) {

        };

        $scope.navigate = function ($event, $id, $index, $focusOn) {
            var $up = 38,
                $down = 40;

            if ($event.which != $up && $event.which != $down)
                return;

            if ($event.which == $up) {
                $index--;

                if ($index < 0)
                    return;
            }

            if ($event.which == $down)
                $index++;

            focus($scope.active.id + '_' + $focusOn + '_' + $index);
        };

        /**
         * Add key:value object
         *
         * @param string prop The collection property to add
         */
        $scope.addObject = function (prop) {
            if (typeof $scope.active[prop] === 'undefined')
                $scope.active[prop] = [];

            var object = {
                key: '',
                value: ''
            };

            var focusOn = '_key_';

            if (prop === 'options') {
                object.selected = false;
                //focusOn 		= '_value_';
            }

            $scope.active[prop].push(object);

            var length = $scope.active[prop].length - 1;

            focus($scope.active.id + focusOn + length);
        };

        /**
         * Add Custom Attribute for Meta Box
         *
         * @since  2.0
         */
        $scope.addMetaBoxAttribute = function () {
            if (typeof $scope.meta.attrs === 'undefined')
                $scope.meta.attrs = [];

            $scope.meta.attrs.push({
                key: '',
                value: ''
            });

            var length = $scope.meta.attrs.length - 1;
            focus('metabox_key_' + length);
        };

        /**
         * Remove Custom Attribute from Meta Box
         * @param  {Integer} $index Index of current attribute
         * @return {void}
         */
        $scope.removeMetaBoxAttribute = function ($index) {
            if (typeof $scope.meta.attrs !== 'undefined' && $scope.meta.attrs.length > 0) {
                $scope.meta.attrs.splice($index, 1);
                $scope.shouldContinue = false;
            }
        };

        /**
         * Remove object from collection
         *
         * @param  string prop   Collection property
         * @param  int $index Index of property to remove
         *
         * @return void
         */
        $scope.removeObject = function (prop, $index) {
            if (typeof $scope.active[prop] !== 'undefined' && $scope.active[prop].length > 0) {
                $scope.active[prop].splice($index, 1);
                $scope.shouldContinue = false;
            }
        };

        $scope.setActivePane = function (pane) {
            $scope.pane = pane;
        };

        // Set available fields for autocomplete
        $('.field-id').live('change', function () {
            $scope.setAvailableFields();
        });

        $(function () {
            $("#select-post-types").select2({
                width: 'resolve'
            });
            $("#select-term").select2({
                width: 'resolve'
            });
            $("#select-page").select2({
                width: 'resolve'
            });

            // Copy to clipboard.
            var icon = '<svg class="mbb-icon--copy" aria-hidden="true" role="img"><use href="#mbb-icon-copy" xlink:href="#icon-copy"></use></svg> ',
                clipboard = new Clipboard( '.mbb-button--copy', {
                    target: function ( trigger ) {
                        return trigger.previousElementSibling;
                    }
                } );
            clipboard.on('success', function(e) {
                e.clearSelection();
                e.trigger.innerHTML = icon + 'Copied';
                setTimeout(function() {
                    e.trigger.innerHTML = icon + 'Copy';
                }, 3000);
            } );
            clipboard.on('error', function() {
                alert( 'Press Ctrl-C to copy' );
            });

            // click tab
            $( '.nav-tab-js' ).on( 'click', function( e ) {
                e.preventDefault();
                var fields = $( this ).data('tab');
                $('.nav-tab-js').removeClass('nav-tab-active');
                $('.builder-code-tab').removeClass('metabox-tab-show');
                if (! $( this ).hasClass('nav-tab-active')) {
                    $( this ).addClass('nav-tab-active');
                    $( '.builder-code-tab--' + fields ).addClass('metabox-tab-show');
                }
            } );
        });
    });

    hljs.initHighlightingOnLoad();
})(jQuery, angular, hljs);
