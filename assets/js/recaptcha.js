if (typeof(RECAPTCHA_JS) === 'undefined') {

    if (typeof ci_url === 'undefined') {
        alert('올바르지 않은 접근입니다.');
    }

    var RECAPTCHA_JS = true;

    $(function () {
        $(document).on('click', '#captcha', function () {
            $.ajax({
                url: ci_url + '/captcha/recaptcha',
                type: 'get',
                cache: false,
                async: false,
                success: function (data) {
                    $('#recaptcha').html(data);
                }
            });
        });
        $('#captcha').trigger('click');

        if (typeof $.validator !== 'undefined') {
            $.validator.addMethod('recaptchaKey', function (value, element) {
                if ($('#g-recaptcha-response').val() === '') {
                    alert('자동등록방지코드에 체크해주세요');
                    return false;
                } else {
                    return true;
                }
            });
        }
    });
}