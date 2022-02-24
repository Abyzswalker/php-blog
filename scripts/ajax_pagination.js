$(document).ready(function () {
    $('#loadMore').on('click', function () {
        const btn = $(this);
        const loader = btn.find('span');
        var $target = $(this);
        var page = $target.attr('data-page');
        page++;
        if ($target.attr('data-cat')) {
            var catId = $target.attr('data-cat');
        }

        $.ajax({
            url: '/ajax_pagination.php?page=' + page,
            dataType: 'html',
            data: {catId:catId},
            beforeSend: function(){
                btn.attr('disabled', true);
                loader.addClass('d-inline-block')
                $('#loader-icon').show();
            },
            success: function(data){
                setTimeout(function () {
                    loader.removeClass('d-inline-block')
                    btn.attr('disabled', false)
                }, 1000)
                $('#showMore').append(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.status == 500) {
                    console.debug("Internal error: " + jqXHR.responseText)
                } else {
                    console.debug("Unexpected error.")
                }
                btn.attr('disabled', false)
            }
        });

        $target.attr('data-page', page);
        console.log(page, $target.attr('data-max'))
        if (page ==  $target.attr('data-max')) {
            $target.hide();
            $('#loadMore').after('<span style="color: red" class="error">Статей не найдено.</span>');
        }
    })
});