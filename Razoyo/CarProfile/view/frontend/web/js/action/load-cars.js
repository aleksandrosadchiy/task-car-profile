define(
    [
        'Razoyo_CarProfile/js/model/url-builder',
        'Magento_Customer/js/customer-data',
        'Magento_Ui/js/model/messageList',
        'mage/translate',
        'mage/storage'
    ],
    function (
        urlBuilder,
        customerData,
        messageList,
        $t,
        storage
    ) {
        'use strict';

        return function (observable, method, params = {}) {
            var endpointUrl;

            endpointUrl = urlBuilder.createUrl('/razoyo/car/' + method, {});

            messageList.clear();

            return storage.post(
                endpointUrl,
                JSON.stringify(params),
                true
            ).fail(
                function () {
                    messageList.addErrorMessage({
                        'message': $t('Unable to load car information. Please try again later.')
                    });
                }
            ).done(
                function (response) {
                    observable(response);
                }
            );
        };
    }
);
