@foreach ($users as $user)
    <tr>
        <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->index + 1 }}</td>
        <td>{{ $user->first_name }}</td>
        <td>{{ $user->last_name}}</td>
        <td>
            @foreach ($user->hobbies as $hobby)
                {{ $hobby->hobbie_name }},
            @endforeach
        </td>
        <td> 
            <button class="btn btn-success edit-btn" data-bs-toggle="modal" data-bs-target="#edituser" data-user-id="{{ $user->id }}">Edit</button>
            <button class="btn btn-danger delete-btn" id="delete" data-user-id="{{ $user->id }}">Delete</button> 
        </td>    
    </tr>


<div class="modal fade" id="edituser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-user-id="{{ $user->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edituserform" action="{{ url('api/users/'.$user->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                <input type="hidden" id="edit_user_id" name="edit_user_id" value="">
                    <label for="edit_first_name">First Name:</label>
                    <input type="text" id="edit_first_name" name="edit_first_name" required>
                    <br>
                    <label for="edit_last_name">Last Name:</label>
                    <input type="text" id="edit_last_name" name="edit_last_name" required>
                    <br>
                    <label for="edit_hobbies">Hobbies:</label>
                    
                    <br>
                    @foreach ($hobbies as $hobby)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="edit_hobbies[]" value="{{ $hobby->id }}">
                            <label class="form-check-label" for="edit_hobbies[]">{{ $hobby->hobbie_name }}</label>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-user-id="">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endforeach
<tr>
    <td colspan="5" class="text-center">
    {{ $users->onEachSide(1)->links('bootstrap5') }}

    </td>
</tr>