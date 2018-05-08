$(function () {
        // モーダルウィンドウを開く
        function showModal(event) {
            event.preventDefault();

            var $shade = $('<div></div>');
            $shade
                .attr('id', 'shade')
                .on('click', hideModal);


            var $modalWin = $('#modal_window');
            var $window = $(window);
            var posX = ($window.width() - $modalWin.outerWidth()) / 2;
            var posY = ($window.height() - $modalWin.outerHeight()) / 3;

            $modalWin
                .before($shade)
                .css({left: posX, top: posY})
                .removeClass('hide')
                .addClass('show')
                .on('click', 'button', function () {
                    hideModal();
                });
        }

        function hideModal() {
            $('#shade').remove();
            $('#modal_window')
                .removeClass('show')
                .addClass('hide');
        }

        $('.show-modal').on('click', showModal);

    }());
