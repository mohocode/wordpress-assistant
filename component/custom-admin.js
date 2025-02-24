jQuery(document).ready(function ($) {
    $('.wp-swal-confirm').on('click', function (e) {
        e.preventDefault();
        var self = $(this);
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = self.attr('href');
            }
        });
    });
});
