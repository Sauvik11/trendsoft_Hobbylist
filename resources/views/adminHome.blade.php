@extends('layouts.mainlayout')

   
@section('content')
<head>
<style>
    .card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.header-content {
    display: flex;
    align-items: center;
    
}
.select2-container {
        z-index: 9999 !important;
    }
    
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body> 
    
<div class="card">
<div class="card-header">
    <div class="header-content">
        <h3>User List</h3>
        <button class="btn btn-success" data-bs-toggle="modal" name="e"data-bs-target="#adduser" style="margin-left: 1030px; ">Add User</button>
    </div>
</div>
<div class="filter">
    <input type="text" class="form-control" id="searchInput" placeholder="Filter notifications" style="width: 250px;">
</div>
    <!-- <div class="form-group">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by name">
        </div> -->
    <div class="card-body">
        
        <table class="table table-hover">
            
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Hobbies</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody id="usersTable"  >
            @include('pagination')
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="adduser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="adduserform" action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" required>
                    <br>
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" required>
                    <br>
                    
                    <label for="hobbies">Hobbies:</label>
                    <select class="select2-multiple form-control" multiple data-live-search="true" name="hobbies[]" multiple="multiple">
                        <option value="all">All Users</option>
                        @foreach ($hobbies as $hobby)
                        <option value="{{ $hobby->id }}">{{ $hobby->hobbie_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


</body>


<script>
$(document).ready(function() {
    // Handle form submission
    $('#adduserform').submit(function(event) {
        event.preventDefault(); // Prevent normal form submission

        // Get the form data
        var formData = $(this).serialize();

        // Send Ajax request
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: formData,
            success: function(response) {
                // Handle success response
               
                // Refresh the page or perform any other necessary actions
                location.reload();
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.log(xhr.responseText);
                // Display error message to the user or perform any other necessary actions
            }
        });
    });
    $(document).ready(function() {
        $('#searchInput').keyup(function() {
            var value = $(this).val().toLowerCase();
            $('table tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });


    $(document).on('click', '.pagination a', function(event) {
    event.preventDefault();
    var $prevButton = $('.pagination a').filter(function() {
        return $(this).text().trim() === '‹';
    });
    var $nextButton = $('.pagination a').filter(function() {
        return $(this).text().trim() === '›';
    });

    if ($(this).is($prevButton)) {
        var url = $(this).attr('href');
        getUsers(url);
    } else if ($(this).is($nextButton)) {
        var url = $(this).attr('href');
        getUsers(url);
    }else {
        var page = $(this).text();
        var url = $(this).attr('href') + '&page=' + page;
        getUsers(url);
    }
});
    getUsers('{{ route('users.index') }}');
    function getUsers(url) {
        // console.log(url,"url")
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'html',
            success: function(response) {
                $('#usersTable').html(response);
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    }
    $(document).on('click', '.edit-btn', function() {
        console.log("edit")
        var userData = $(this).data('user-id');
        var user = JSON.parse(userData);
        console.log("user",user)
        $.ajax({
            url: '/api/users/' + user,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Populate the form fields with the user's details
                $('#edit_user_id').val(response.id);
                $('#edit_first_name').val(response.user.first_name);
                $('#edit_last_name').val(response.user.last_name);
                console.log(response.id,"userid responded")
                // Check the hobbies checkboxes based on the user's selected hobbies
                var userHobbies = response.user.hobbies;
                $('input[name="edit_hobbies[]"]').prop('checked', false); // Uncheck all checkboxes
                $.each(userHobbies, function(index, hobby) {
                    $('input[name="edit_hobbies[]"][value="' + hobby.id + '"]').prop('checked', true);
                });
                
                // Open the edit user modal
                $('#edituser').modal('show');
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                // Handle error response
            }
        });
});
$(document).on('click', '.delete-btn', function(event) {
    event.preventDefault();

    // Retrieve the user ID from the data attribute
    var userId = $(this).data('user-id');

    // Confirm deletion with the user
    if (confirm('Are you sure you want to delete this user?')) {
      // Send an AJAX request to delete the user
      $.ajax({
        url: '/api/users/' + userId,
        type: 'DELETE',
        dataType: 'json',
        success: function(response) {
          // Handle success response
          console.log(response);
          // Optionally, you can update the UI or perform any other action
          // Reload the page to reflect the changes
          location.reload();
        },
        error: function(xhr, status, error) {
          // Handle error response
          console.log(xhr.responseText);
        }
      });
    }
  });
$(document).on('submit', '#edituserform', function(event) {
        event.preventDefault();
        
        // Retrieve the form data
        var formData = $(this).serialize();
        console.log("formData",formData);
        
       
       

        // Submit the form data via Ajax
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            
            data: formData,
           
            dataType: 'json',
            success: function(response) 
            
            {
                // Handle success response
                console.log(response)
                location.reload();
                // Close the edit user modal
                $('#edituser').modal('hide');
                // Optionally, you can update the user's details in the table or perform any other action
            },
            error: function(xhr, status, error) {
                
                console.log('erroe');
                // Handle error response
            }
        });
    });



});




</script>







@endsection
