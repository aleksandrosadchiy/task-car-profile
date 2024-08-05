/* @api */
define([
    'jquery',
    'knockout',
    'Magento_Ui/js/model/messageList',
    'Razoyo_CarProfile/js/action/load-cars',
    'mage/translate',
    'Magento_Catalog/js/price-utils',
    'uiComponent',
    'domReady!'
], function (
    $,
    ko,
    globalMessageList,
    loadCarsAction,
    $t,
    priceUtils,
    Component
) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Razoyo_CarProfile/car-profile',
            myCar: ko.observable({}),
            makeOptions: ko.observable([]),
            selectedMake: ko.observable(),
            carOptions: ko.observable([]),
            selectedCar: ko.observable(),
            isCarSelectorVisible: ko.observable(false),
            priceFormat: {
                "pattern": "$%s",
                "precision": 2,
                "requiredPrecision": 2,
                "decimalSymbol": ".",
                "groupSymbol": ",",
                "groupLength": 3,
                "integerRequired": false
            }
        },

        /** @inheritdoc */
        initialize: function () {
            this._super();
            let self = this;

            $('body').trigger('processStart');
            loadCarsAction(this.myCar, 'getMyCar').always(function () {
                $('body').trigger('processStop');
                if (self.myCar().customer_id === 0) {
                    self.loadCarMakes();
                    self.isCarSelectorVisible(true);
                }
            });

            return this;
        },

        /**
         * Load car make options
         */
        loadCarMakes: function () {
            $('body').trigger('processStart');
            loadCarsAction(this.makeOptions, 'getMakes').always(function () {
                $('body').trigger('processStop');
            });
        },

        /**
         * Change or Add a car
         */
        changeAddCar: function () {
            this.makeOptions([]);
            this.selectedMake(null);
            this.carOptions([]);
            this.selectedCar(null);
            this.loadCarMakes();
            this.isCarSelectorVisible(true);
        },

        /**
         * Get car title
         */
        getCarTitle: function () {
            return this.myCar().year + ' ' + this.myCar().make + ' ' + this.myCar().model;
        },

        /**
         * On make change event
         */
        onMakeChange: function () {
            if (this.selectedMake() === undefined) {
                return this;
            }

            $('body').trigger('processStart');
            loadCarsAction(this.carOptions, 'getCarsByMake', { 'make': this.selectedMake() }).always(function () {
                $('body').trigger('processStop');
            });
        },

        /**
         * On car change event
         */
        onCarChange: function () {
            if (this.selectedCar() === undefined) {
                return this;
            }
            let self = this;

            $('body').trigger('processStart');
            loadCarsAction(this.myCar, 'saveCar', { 'carId': this.selectedCar() }).always(function () {
                self.isCarSelectorVisible(false);
                $('body').trigger('processStop');
                self.showSuccessMessage();
            });
        },

        /**
         * Success message when car saved
         */
        showSuccessMessage: function () {
            globalMessageList.addSuccessMessage({
                'message': $t('The car has been saved.')
            });
        },

        /**
         * Button text
         */
        getAddButtonText: function () {
            if (this.myCar().customer_id === 0) {
                return $t('Add a car');
            }
            return $t('Change the car');
        },

        /**
         * Format price
         */
        formatPrice: function (price) {
            return priceUtils.formatPrice(price, this.priceFormat);
        }
    });
});
