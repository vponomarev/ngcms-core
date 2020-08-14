/**
 * [ResponseException description]
 * @param  {string}  message
 * @param  {object}  response
 */
function ResponseException(message, response) {
    this.message = message;
    this.response = response;
    this.name = 'AJAX Response Exception';
    this.toString = function() {
        return `${this.name}: ${this.message}`
    };
}

/**
 * Выполнить асинхронный POST-запрос (AJAX) на сервер.
 * @param  {string}  methodName  Имя метода.
 * @param  {object}  params  Параметры запроса.
 * @param  {boolean} notifyResult  Отобразить результат запроса во всплывающем сообщении.
 * @return {jqXHR}
 */
export function post(methodName, params = {}, notifyResult = true) {
    const token = $('input[name="token"]').val();

    return $.ajax({
            method: 'POST',
            url: NGCMS.admin_url + '/rpc.php',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
            },
            data: {
                json: 1,
                token: token,
                methodName: methodName,
                params: JSON.stringify(params)
            },
            beforeSend() {
                ngShowLoading();
            }
        })
        .then(function(response) {
            if (!response.status) {
                throw new ResponseException(`Error [${response.errorCode}]: ${response.errorText}`, response);
            }

            return response;
        })
        .done(function(response) {
            // title: `<h5>${NGCMS.lang.notifyWindowInfo}</h5>`,
            notifyResult && ngNotifySticker(response.errorText, {
                className: 'alert-success',
                closeBTN: true
            });

            return response;
        })
        .fail(function(error) {
            // title: `<h5>${NGCMS.lang.notifyWindowError}</h5>`,
            ngNotifySticker(error.message || NGCMS.lang.rpc_httpError, {
                className: 'alert-danger',
                closeBTN: true
            });
        })
        .always(function() {
            ngHideLoading();
        });
}
