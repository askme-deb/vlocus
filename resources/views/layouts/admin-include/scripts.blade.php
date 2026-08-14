<!--bootstrap js-->
<script src="{{ asset('assets/dashboard-assets/assets/js/bootstrap.bundle.min.js') }}"></script>

<!--plugins-->
<script src="{{ asset('assets/dashboard-assets/assets/js/jquery.min.js') }}"></script>
<!--plugins-->
<script src="{{ asset('assets/dashboard-assets/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/dashboard-assets/assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>
<script>
    $(document).ready(function() {
        var table = $('#example2').DataTable({
            lengthChange: false,
            buttons: ['copy', 'excel', 'pdf', 'print']
        });

        table.buttons().container()
            .appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>

<script src="{{ asset('assets/dashboard-assets/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/dashboard-assets/assets/plugins/metismenu/metisMenu.min.js') }}"></script>
{{-- <script src="{{ asset('assets/dashboard-assets/assets/plugins/apexchart/apexcharts.min.js') }}"></script> --}}

<script src="{{ asset('assets/dashboard-assets/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/dashboard-assets/assets/plugins/peity/jquery.peity.min.js') }}"></script>
<script>
    $(".data-attributes span").peity("donut")
</script>
<script src="{{ asset('assets/dashboard-assets/assets/js/main.js') }}"></script>
<script src="{{ asset('assets/dashboard-assets/assets/js/dashboard1.js') }}"></script>
{{-- <script>
    new PerfectScrollbar(".user-list")
</script> --}}

<script src="{{ asset('assets/dashboard-assets/assets/plugins/notifications/js/lobibox.min.js') }}"></script>
<script src="{{ asset('assets/dashboard-assets/assets/plugins/notifications/js/notifications.min.js') }}"></script>
<script src="{{ asset('assets/dashboard-assets/assets/plugins/notifications/js/notification-custom-script.js') }}">
</script>

<!-- page js file -->
<!-- sweet alret -->

<script src="{{ asset('assets/dashboard-assets/vendors/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/dashboard-assets/assets/js/pages/ui/dialogs.js') }}"></script>
@include('layouts._notification')


<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.29.0/dist/feather.min.js"></script>
<script>
    feather.replace()
</script>

<!--tinymce js-->
<script src="{{ asset('assets/dashboard-assets/assets/js/tinymce/tinymce.min.js') }}"></script>

<!-- init js -->
<script src="{{ asset('assets/dashboard-assets/assets/js/form-editor.init.js') }}"></script>

<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>

<script>
    $('#imgInp').on('change', function() {
        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result).css('display', 'block');
            }
            reader.readAsDataURL(input.files[0]);
        }
    });
</script>

<script>
    $(document).ready(function() {
        // On page load, set the theme from localStorage
        const savedTheme = localStorage.getItem("theme");
        if (savedTheme) {
            $("html").attr("data-bs-theme", savedTheme);
        }

        // Function to change theme and save it to localStorage
        function changeTheme(theme) {
            $("html").attr("data-bs-theme", theme);
            localStorage.setItem("theme", theme);
        }

        // Event listeners for theme buttons
        $("#BlueTheme").on("click", function() {
            changeTheme("blue-theme");
        });

        $("#LightTheme").on("click", function() {
            changeTheme("light");
        });

        $("#DarkTheme").on("click", function() {
            changeTheme("dark");
        });

        $("#SemiDarkTheme").on("click", function() {
            changeTheme("semi-dark");
        });

        $("#BoderedTheme").on("click", function() {
            changeTheme("bodered-theme");
        });
    });
</script>
<script>
    function deleteItem(element) {
        if (!element || typeof element.getAttribute !== 'function') {
            console.error("Invalid element passed to showConfirmMessage.");
            return;
        }
        var url = element.getAttribute('data-url');
        var itemText = element.getAttribute('data-item');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this " + itemText + "!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function() {
            // swal("Deleted!", "Your imaginary file has been deleted.", "success");

            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        swal("Deleted!", response.success, "success");
                        location.reload();
                    } else {
                        swal("Error!", response.error, "error");
                    }
                },
                error: function(jqXHR) {

                    var errorMessage = jqXHR.responseJSON && jqXHR.responseJSON.error ? jqXHR
                        .responseJSON.error : 'Failed to delete the item.';
                    swal("Error!", errorMessage, "error");
                }
            });
        });
    }
</script>

@yield('scripts')
